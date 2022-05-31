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


require_once (dirname(__FILE__) . '/debug.php');

class ehDate {

    private $data; //data storage, contains date stored as unix timestamp

    //	and $adate, stored as an associative array :
    /* ["hour"=>0..23, #hours
      "min"=>0..59,  #minutes
      "mday"=>1..31, #day in the month
      "mon"=>1..12,  #month
      "year"=>int,   #year
      "wd"=>1..7]	   #week day from monday to sunday
     */

    public function __construct($date = null) {
        $this->data = ["date" => 0, "adate" => ["hour" => 0, "min" => 0, "mday" => 0, "mon" => 0, "year" => 0, "wd" => 0]];
        if ($date === null) {
            $this->date = time();
        } elseif (is_object($date) && get_class($date) == 'ehDate') {
            $this->date = $date->date;
        } else {
            $this->date = self::convertDate($date, self::T_TIMESTAMP);
        }
    }

    const ADATE_FIELDS = ["hour", "min", "mday", "mon", "year", "wd"];
    const T_TIMESTAMP = 1; //timestamp : nr of seconds since epoch
    const T_STRING = 2;    //database like string
    const T_STRING2 = 3;   //english natural string
    const T_AA = 4;        //strptime associative array format
    const T_AAA = 5;       //internal associative array format
    const T_OBJ = 6;     //this class
    const T_LSTRING = 7;     //Localized string
    const T_INVALID = -1;  //invalid date
    const T_STRING_UNKNOWN = -2; //invalide date string
    const T_UNDEF = -3;     //undefined date format
    const T2TXT = [1 => "T_TIMESTAMP", 2 => "T_STRING", 3 => "T_STRING2", 4 => "T_AA", 5 => "T_AAA", 6 => "T_OBJ", 7 => "T_LSTRING", -1 => "T_INVALID", -2 => "T_STRING_UNKNOWN", -3 => "T_UNDEF"];
    const F_STRING = "%F %H:%M"; //database like format
    const F_STRING2 = "%c"; //english natural date format 

    public function __get($name) {
        switch ($name) {
            case "date":
                return $this->data["date"];
                break;
            case "adate":
                return $this->data["adate"];
                break;
            case "hour":
            case "min":
            case "mday":
            case "mon":
            case "year":
            case "wd":
                return $this->data["adate"][$name];
                break;
            case "wom":
                $ret = 1 + (int) (($this->mday - 1) / 7);
                return $ret;
                break;
            default:
                return null;
        }
    }

    public function __set($name, $value) {
        switch ($name) {
            case "adate":
                if (is_array($value)) {
                    foreach ($value as $k => $v) {
                        if (in_array($k, self::ADATE_FIELDS)) {
                            $this->data["adate"][$k] = $v;
                        }
                    }
                }
                $this->shake();
                break;
            case "date":
                if ($value === null) {
                    $value = time();
                }
                $this->data["date"] = self::convertDate($value, self::T_TIMESTAMP);
                $this->data["adate"] = self::convertDate($value, self::T_AAA);
                break;
            case "hour":
            case "min":
            case "mday":
            case "mon":
            case "year":
                if (!is_numeric($value)) {
                    throw new Exception(sprintf(__("Invalid type for %s, integer expected, %s given."), $name, gettype($value)));
                    return;
                }
                $this->data["adate"][$name] = $value;
                $this->shake();
                break;
        }
    }

    public function __isset($name) {
        switch ($name) {
            case "date":
            case "hour":
            case "min":
            case "mday":
            case "mon":
            case "year":
            case "wd":
            case "wom":
                return true;
                break;
        }
        return false;
    }

    public function __unset($name) {
        switch ($name) {
            case "date":
                $this->data["date"] = 0;
                break;
            case "hour":
            case "min":
            case "mday":
            case "mon":
            case "year":
            case "wd":
                $this->data["adate"][$name] = 0;
                break;
        }
    }

    //This method computes the changes to the adate fields to get
    // a consistant & valid date.
    protected function shake() {
        $tdate = mktime($this->hour, $this->min, 0, $this->mon, $this->mday, $this->year);
        if ($tdate === false) {
            throw new Exception(__("Something went wrong when shaking"));
        }
        $this->date = $tdate;
    }

    public function addDay($num = 1) {
        $this->mday += 1;
    }

    public function toAA() {
        return self::convertDate($this->date, $this::T_AA);
    }

    public function toAAA() {
        return $this->data["adate"];
    }

    public function toString2() {
        return self::convertDate($this->date, $this::T_STRING2);
    }

    public function toString() {
        return (string) $this;
    }

    public function toJSON() {
        return json_encode($this->data["adate"]);
    }

    public function __toString() {
        return self::convertDate($this->date, $this::T_STRING);
    }

    /* Static methods */

    public static function __callStatic($name, $arguments) {
        return "$name called statically";
    }

    public static function convertDate($date, $format = self::T_TIMESTAMP, $type = self::T_UNDEF, $sformat = "") {
        $t_date = 0;
        if ($type == self::T_UNDEF) {
            $type = self::getDateType($date);
            //
            if ($type == $format) {
                return $date;
            }
        }

        switch ($type) {
            case self::T_TIMESTAMP:
                $t_date = $date;
                break;
            case self::T_AA:
                $t_date = self::aa2ts($date);
                break;
            case self::T_AAA:
                $t_date = self::aaa2ts($date);
                break;
            case self::T_STRING:
            case self::T_STRING2:
            case self::T_LSTRING:
                $t_date = strtotime($date);
                break;
            case self::T_OBJ:
                $t_date = $date->date;
        }
        switch ($format) {
            case self::T_TIMESTAMP:
                return $t_date;
            case self::T_STRING:
                return strftime(self::F_STRING, $t_date);
            case self::T_STRING2:
                return strftime(self::F_STRING2, $t_date);
            case self::T_LSTRING:
                return strftime(($sformat !== "" ? $sformat : __("%b %d %a %Y")), $t_date);
            case self::T_AA:
                $a_date = strptime(strftime(self::F_STRING, $t_date), self::F_STRING);
                return $a_date;
            case self::T_AAA:
                $a_date = strptime(strftime(self::F_STRING, $t_date), self::F_STRING);
                $adate = ["hour" => $a_date["tm_hour"],
                    "min" => $a_date["tm_min"],
                    "mday" => $a_date["tm_mday"],
                    "mon" => $a_date["tm_mon"] + 1,
                    "year" => $a_date["tm_year"] + 1900,
                    "wd" => ($a_date["tm_wday"] == 0 ? 7 : $a_date["tm_wday"])];
                return $adate;
            case self::T_OBJ:
                return new ehDate($t_date);
            default:
                echo("<pre>") && debug_print_backtrace();
                throw new Exception(sprintf(__("Unsupported format %d encountered."), $format));
        }
    }

    public static function convertDateArray($array, $format = self::T_TIMESTAMP, $type = self::T_UNDEF, $sformat = "") {
        if (!is_array($array) || count($array) == 0) {
            return null;
        }
        foreach ($array as $v) {
            $ret[] = self::convertDate($v, $format, $type, $sformat);
        }
        return $ret;
    }

    /* Converts strftime like assoc array to timestamp */

    public static function aa2ts($date) {
        return mktime($date["tm_hour"], $date["tm_min"], $date["tm_sec"], $date["tm_mon"] + 1, $date["tm_mday"], $date["tm_year"] + 1900);
    }

    /* Converts internal assoc array format to timestamp */

    public static function aaa2ts($date) {
        $ret = mktime($date["hour"], $date["min"], 0, $date["mon"], $date["mday"], $date["year"]);
    }

    /* Gives the mday for a given week day, a week number and the first day of the month */

    public static function dw2mday($d, $w, $firstd) {
        if ($d == 0)
            $d = 7;
        if ($firstd == 0)
            $firstd = 7;
        return ($d >= $firstd ? $d : $d + 7) - $firstd + 1 + ($w - 1) * 7;
    }

    public static function aaChange(&$array, $values = null) {
        if (!is_array($array) || (!is_array($values) && $values != null)) {
            return false;
        }
        if ($values !== null) {
            foreach ($values as $k => $v) {
                if (!array_key_exists($k, $array)) {
                    return false;
                }
                $array[$k] = $v;
            }
        }
        $array = self::convertDate(self::date2timestamp($array), self::T_AA);
        return true;
    }

    public static function getDateType($date) {
        if (is_null($date) || (is_string($date) && $date == "") || (is_array($date) && count($date) == 0) || (is_numeric($date) && $date < 0))
            return self::T_INVALID;
        if (is_numeric($date))
            return self::T_TIMESTAMP;
        if (is_array($date)) {
            if (array_key_exists("tm_hour", $date) && count($date) == 9)
                return self::T_AA;
            elseif (array_key_exists("hour", $date) && count($date) == 6)
                return self::T_AAA;
            else
                return self::T_INVALID;
        }
        if (is_string($date)) {
            if (strptime($date, self::F_STRING)) {
                return self::T_STRING;
            } else if (strptime($date, self::F_STRING2)) {
                return self::T_STRING2;
            } else {
                return self::T_STRING_UNKNOWN;
            }
        }
        if (is_object($date) && get_class($date) == "ehDate") {
            return self::T_OBJ;
        }
    }

    public static function get1stWeekDayOfMonth($date) {
        $oDate = new ehDate($date);
        $oDate->mday = 1;
        return $oDate->wd;
    }

}

?>