<?php

/* -- BEGIN LICENSE BLOCK ----------------------------------
 *
 * This file is part of ehRepeat, a plugin for Dotclear 2.
 *
 * Copyright(c) 2019 Nurbo Teva <dev@taktile.fr>
 *
 * Licensed under the GPL version 2.0 license.
 * A copy of this license is available in LICENSE file or at
 * http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
 *
 * -- END LICENSE BLOCK ------------------------------------ */

/*
  This class handles repetitive events frequencies :
  "weeks_of_month days_of_week" where
  weeks_of_month can be * for all, a digit 1..5 or a coma separated list of digits 1..5
  days_of_week can be * for all, a digit 1..7 or a coma separated list of digits 1..7
  "*:*" means every day
  "*:5" means every friday
  "3:*" means 3rd week
  "3:4" means every 3rd Thursday of month
  "3:2,5" means every 3rd Tuesday & Friday of month
 */


require_once (dirname(__FILE__) . '/debug.php');


define("MYSQL_DATE_FORMAT", "Y-m-d H:i:s");

class ehSimpleFreq {

    protected $WoM = array();
    protected $Wd = array();
    protected $sWd;
    protected $sWoM;

    const DUMMY = "0";

    public function __construct($freq) {
        $afreq = $this->valid($freq);
        if ($afreq === null) {
            return null;
        }
        $this->WoM = $afreq["WoM"];
        $this->Wd = $afreq["Wd"];
        $this->sWd = array(1 => __("Monday"), 2 => __("Tuesday"), 3 => __("Wednesday"), 4 => __("Thursday"), 5 => __("Friday"), 6 => __("Saturday"), 7 => __("Sunday"));
        $this->sWoM = array(1 => __("1st"), 2 => __("2nd"), 3 => __("3rd"), 4 => __("4th"), 5 => __("5th"));
    }

    //checks whether the given date matches the $this->freq
    public function dateValid($date) {
        $dt = new ehDate($date);
        return in_array($dt->wd, $this->Wd) && in_array($dt->wom, $this->WoM);
    }

    public static function valid($freq, $str = false) {
        $afreq = explode(":", $freq);
        if (count($afreq) != 2) {
            return null;
        }
        if (trim($afreq[0]) == "*") {
            $awd = range(1, 7);
        } else {
            $awd = explode(",", $afreq[0]);
            foreach ($awd as $wd) {
                $wd = (int) $wd;
                if (!is_numeric($wd) || !is_int($wd) || $wd < 0 || $wd > 7) {
                    return null;
                }
            }
        }
        if (trim($afreq[1]) == "*") {
            $awom = range(1, 5);
        } else {
            $awom = explode(",", $afreq[1]);
            foreach ($awom as $wom) {
                $wom = (int) $wom;
                if (!is_numeric($wom) || !is_int($wom) || $wom < 0 || $wom > 5) {
                    return null;
                }
            }
        }
        if ($str === true) {
            $ret = implode(",", $awom) . " " . implode(",", $awd);
        } else {
            $ret = array("WoM" => $awom, "Wd" => $awd);
        }
        return $ret;
    }

    public function toFreqString() {
        $ret = "";
        if (count($this->WoM) == 5) {
            $ret .= "*:";
        } else {
            $ret .= implode(",", $this->WoM) . ":";
        }
        if (count($this->Wd) == 7) {
            $ret .= "*";
        } else {
            $ret .= implode(",", $this->Wd);
        }
        return $ret;
    }

    protected function array2list($a) {
        if (!is_array($a)) {
            return "";
        }
        if (count($a) == 1) {
            return $a[0];
        }
        $a_deb = array_slice($a, 0, -1);
        $a_end = array_slice($a, -1);
        return implode(", ", $a_deb) . __(" & ") . $a_end[0];
    }

    protected function Wd2String() {
        $aRet = [];
        foreach ($this->Wd as $d) {
            $aRet[] = $this->sWd[$d];
        }
        return $this->array2list($aRet);
    }

    protected function WoM2String() {
        $aRet = array();
        foreach ($this->WoM as $d) {
            $aRet[] = $this->sWoM[$d];
        }
        return $this->array2list($aRet);
    }

    protected function isWdStar() {
        return (count($this->Wd) == 7);
    }

    protected function isWomStar() {
        return (count($this->WoM) == 5);
    }

    public function toString() {
        if ($this->isWdStar() && $this->isWomStar()) {
            return __("Every day");
        } elseif ($this->isWomStar()) {
            return __("Every") . " " . $this->Wd2String();
        } elseif ($this->isWdStar()) {
            return __("Every") . " " . $this->WoM2String() . " " . __("week");
        } else {
            return __("Every") . " " . $this->WoM2String() . " " . $this->Wd2String();
        }
    }

    /* Returns num dates from $basedate, according to the  
      pattern described by $freq
      $basedate : date de base du calcul en format mysql
      $refdate : date de référence du calcul en format mysql
      $freq : chaine de fréquence
      $num : nombre de mois à retourner
     */

    public static function getDates($basedate = null, $refdate = null, $freq, $num = 1, $outFormat = ehDate::T_STRING, $sformat = "") {
        // $xfreq va contenir un tableau {WoM => @WoM; Wd => @Wd}

        $xfreq = self::valid($freq);
        if ($xfreq === null) {
            return [];
        }
        // Si $basedate est null, initialisé avec maintenant.
        // sinon, timestamp
        if (is_object($basedate) && get_class($basedate) == 'ehDate') {
            $oBaseDate = $basedate;
        } elseif (( $basedate === null ) || ( strlen($basedate) == 0 )) {
            $oBaseDate = new ehDate();
        } else {
            list($y, $m, $d) = explode("-", explode(" ", $basedate)[0]);
            if (!checkdate($m, $d, $y)) {
                return [];
            }
            $oBaseDate = new ehDate($basedate);
        }

        if ($refdate !== null || (is_string($refdate) && strlen($refdate) > 0)) {
            if (is_object($refdate) && get_class($refdate) == 'ehDate') {
                $oRefDate = $refdate;
            } else {
                $oRefDate = new ehDate($refdate);
            }
            $oBaseDate->hour = $oRefDate->hour;
            $oBaseDate->min = $oRefDate->min;
        }

        /* We get all dates computed from frequency */

        $nextdates = array();
        foreach ($xfreq["Wd"] as $wd) {
            $nextdates = $nextdates + self::getDatesMatching($wd, $xfreq['WoM'], $oBaseDate, $num);
        }

        if (count($nextdates) > 1) {
            /* we sort this timestamps, older first */
            ksort($nextdates, SORT_NUMERIC);
        }

        $ret = ehDate::convertDateArray(array_keys($nextdates), $outFormat, ehDate::T_TIMESTAMP, $sformat);
        return $ret;
    }

    /*
      Computes all the dates matching weekday($wd) and weeks of month ($awom) from $date (ehDate)
      and for $num months.

     */

    private static function getDatesMatching($wd, $awom, $date, $num) {
        $cur_date = new ehDate($date);
        $res = [];
        $fuse = 100;

        $delta_wd = $wd - $date->wd;
        if ($delta_wd < 0) {
            $delta_wd += 7;
        }

        $cur_date->mday += $delta_wd; // cur_date est à la première date suivant $date dont le jour est $wd.
        //$start_mon = $cur_date->mon + 12 * $cur_date->year; //on enregistre le mois de départ pour vérifier le nombre d'itérations


        $cur_mon = $date->mon;

        while ($num > 0) { //vérif qu'on n'aie pas dépassé le nombre de mois.
            if (in_array($cur_date->wom, $awom)) {
                $res[$cur_date->date] = new ehDate($cur_date);
            }
            $cur_date->mday += 7;
            if ($cur_mon != $cur_date->mon) {
                $num--;
                $cur_mon = $cur_date->mon;
            }
            if (--$fuse == 0)
                break;
        }

        return $res;
    }

}
