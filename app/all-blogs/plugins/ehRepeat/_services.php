<?php

/* -- BEGIN LICENSE BLOCK ----------------------------------
 *
 * This file is part of ehRepeat, a plugin for Dotclear 2.
 *
 * Copyright(c) 2015 Onurb Teva <dev@taktile.fr>
 *
 * Licensed under the GPL version 2.0 license.
 * A copy of this license is available in LICENSE file or at
 * http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
 *
 * -- END LICENSE BLOCK ------------------------------------ */

if (!defined('DC_CONTEXT_ADMIN')) {
    return;
}

class ehRepeatRestMethods {

    public static function freqToString($core, $get) {
        $freq = $get['freq'];
        $rsp = new xmlTag();
        if ($freq == null)
            throw new Exception(__("Frequency string missing"), 1);
        $xfreq = new ehSimpleFreq($freq);
        $freqdesc = $xfreq->toString();
        $rsp->value(json_encode($freqdesc, JSON_PRETTY_PRINT));
        return $rsp;
    }

    public static function computeDates($core, $get) {
        $rsp = new xmlTag();
        try {
            $dummy = ehDate::T_STRING;
            $dummy2 = ehSimpleFreq::DUMMY;
            $freq = $get['freq'];
            $date = $get['date'];
            $bdate = isset($get['bdate']) ? $get['bdate'] : null;
            $num = $get['num'];
            $format = isset($get['format']) ? "T_" . mb_strtoupper($get['format']) : "T_STRING";
            $sformat = isset($get['sformat']) ? $get['sformat']:"";
            if ($freq == null)
                throw new Exception(__("Frequency string missing"), 1);
            if ($date == null)
                throw new Exception(__("Date is missing"), 1);
            if ($num == null)
                $num = 1;

            if ($k = array_search($format, ehDate::T2TXT)) {
                $format = $k;
            }
            setlocale(LC_TIME,"fr_FR.UTF8");

            $params = new xmlTag();
            $params->date($date);
            $params->bdate($bdate);
            $params->freq($freq);
            $params->num($num);
            $params->format(ehDate::T2TXT[$format]);
            $params->request(http::getSelfURI());
            $rsp->params($params);


            $cdates = ehSimpleFreq::getDates($bdate, $date, $freq, $num, $format,$sformat);
            if ($cdates === null) {
                $rsp->error(__("No dates found for this request."));
                return $rsp;
            }
            $dates = new xmlTag();
            foreach ($cdates as $d) {
                $dates->date($d);
            }
            $rsp->dates($dates);
            return $rsp;
        } catch (Exception $e) {
            $rsp->error($e->getMessage());
            return $rsp;
        }
    }

    public static function checkCal($core, $get) {
        $dummy = ehDate::T_STRING;
        $dummy2 = ehSimpleFreq::DUMMY;
        $freq = $get['freq'];
        $rsp = new xmlTag();
        $date = $get['date'];
        $bdate = isset($get['bdate']) ? $get['bdate'] : null;
        $num = $get['num'];
        if ($freq == null)
            throw new Exception(__("Frequency string missing"), 1);
        if ($date == null)
            throw new Exception(__("Date is missing"), 1);
        if ($num == null)
            $num = 1;

        checkResults($num, $date, $freq);

        return $rsp;
    }

}
