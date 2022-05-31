<?php

# -- BEGIN LICENSE BLOCK ----------------------------------
#
# This file is part of EHRepeat, an extension of eventHandler
# for dotclear 2
#
# (c)2019 Nurbo Teva for Association Du Grain à Moudre
#
# Licensed under the GPL version 2.0 license.
# A copy of this license is available in LICENSE file or at
# http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
#
# -- END LICENSE BLOCK ------------------------------------



if (!defined('DC_RC_PATH')) {
    return;
}


$core->tpl->addValue('EhRepeatReadableFrequency', array('dcEhRepeatPublic', 'EhRepeatReadableFrequency'));
$core->tpl->addValue('EhRepeatEventDate', array('dcEhRepeatPublic', 'EhRepeatEventDate'));
$core->tpl->addValue('EhRepeatInterval', array('dcEhRepeatPublic', 'EhRepeatInterval'));
$core->tpl->addBlock('EhRepeatEventDates', array('dcEhRepeatPublic', 'EhRepeatEventDates'));
$core->tpl->addBlock('EhRepeatEventDatesHeader', array('dcEhRepeatPublic', 'EhRepeatEventDatesHeader'));
$core->tpl->addBlock('EhRepeatEventDatesFooter', array('dcEhRepeatPublic', 'EhRepeatEventDatesFooter'));
$core->tpl->addBlock('EhRepeatIf', array('dcEhRepeatPublic', 'EhRepeatIf'));
$core->tpl->addBlock('EventIfIsRepeat', array('dcEhRepeatPublic', 'EventIfIsRepeat'));


/* eventhandler improvements : Breadcrumbs */
$core->addBehavior('publicBreadcrumb', array('EHExtension', 'EhPublicBreadcrumb'));


/* Manage special ehRepeat tags as [event_list] */
//$core->addBehavior('publicBeforeContentFilter', array('dcEhRepeatPublic', 'publicBeforeContentFilter'));

/* Manage special ehRepeat tags as [eventhandler_list] */
//$core->addBehavior('publicBeforeContentFilter', array('dcEhRepeatPublic', 'publicBeforeContentFilterEh'));


/* Insert js files in public pages */
$core->addBehavior('publicHeadContent', array('dcEhRepeatPublic', 'publicHeadContent'));

#public ajax service dotajax
if (isset($core->pubrest)) {
    $__autoload['ehRepeatPublicRestMethods'] = dirname(__FILE__) . '/_publicservices.php';
    $core->pubrest->register('ehrepeat', 'ehRepeatPublicRestMethods');
}

class dcEhRepeatPublic {
    /* Handling of extra xhtml markup [event_list]
     * This markup has the following attributes :
     * all : All events (Repeat or not)
     * limit : # of events to display
     * id : the master event id whom slaves are to add to the list
     * by_title : selects the events to display using their titles (use _ for a character wildcard and % for any caracter, \_ for _ and \% for %)
     * comment : an optionnal comment given for the previous id. if a comment is given for one id please specify comments (even empty) for all ids.
     * id and comment can be repeted if several master events have to be inserted (in that case, you may want to specify a mode)
     * mode : mix or evt : mix will mix all the slave events and sort them by date, evt will group all the events master by master.
     * title : a title to display for the list (defaults to __("Next events") )
     * use_title : displays the event title along with the date
     * eg : [event_list id="26" comment="" id="118" comment="(Marché festif)" title="Prochains marchés" mode="mix"]
     * eg : [event_list by_title="Marché%"]
     */

    public static function publicBeforeContentFilter($core, $tag, $arr) {
        if ($tag == 'EntryContent' || $tag == 'EntryExcerpt') {
            $txt = $arr[0];
            $out = array();
            $all = false;
            $debug = "";
            if (preg_match_all("/(?:<p>)\[event_list\b([^\]]*)\](?:<\/p>)/is", $txt, $out) > 0) {
                foreach ($out[0] as $k => $v) {
                    $values = array("align" => "", "mode" => null, "use_post_title" => false, "set_link" => false, "none" => "");
                    $tinout = array();
                    preg_match_all('`(\w+)\s*=\s*\"([^\"]*)\"`isU', $out[1][$k], $tinout);
                    foreach ($tinout[1] as $k2 => $v2) {
                        if (!isset($values[$v2])) {
                            $values[$v2] = array();
                        }
                        switch ($v2) {
                            case "id":
                                $values["id"][] = $tinout[2][$k2];
                                $values['comment'][$tinout[2][$k2]] = "";
                                break;
                            case "by_title":
                                $values["by_title"][] = $tinout[2][$k2];
                                $values['comment'][$tinout[2][$k2]] = "";
                                break;
                            case "comment":
                                if (count($values['id'] > 0))
                                    $ref = &$values['id'];
                                else if (count($values['by_title']))
                                    $ref = &$values['by_title'];
                                else
                                    break;
                                $values["comment"][$ref[count($ref) - 1]] = $tinout[2][$k2];
                                break;
                            case "use_post_title":
                                $values['use_post_title'] = (boolean) $tinout[2][$k2];
                                break;
                            case "set_link":
                                $values['set_link'] = (boolean) $tinout[2][$k2];
                                break;
                            case "none":
                                $values['none'] = $tinout[2][$k2];
                                break;
                            case "limit":
                                $values['limit'] = (int) $tinout[2][$k2];
                                break;
                            case "all":
                                $values['use_post_title'] = true;
                                break;
                            default:
                                $values[$v2] = $tinout[2][$k2];
                        }
                    }

                    $events_list = [];
                    $params = array();
                    if (isset($values["id"])) {
                        $params["master_event_id"] = $values["id"][0];
                    } else if (isset($values["by_title"])) {
                        $params["by_title"] = $values["by_title"];
                    } else if (isset($values["all"])) {
                        $params["all"] = true;
                    }
                    if (is_array($values['mode'])) {
                        $values['mode'] = $values['mode'][0];
                    }
                    if ($values['mode'] == "mix") {
                        $params["order"] = "event_startdt ASC";
                    } elseif ($values['mode'] == "evt") {
                        $params["order"] = "post_url ASC";
                    }

                    $params["event_period"] = "notfinished";

                    $debug .= "Values : " . print_r($values, true) . "\n";

                    $debug .= "Params : " . print_r($params, true) . "\n";
                    $ehRepeat = isset($core->ehRepeat) ? $core->ehRepeat : new dcEhRepeat($core);

                    $repeats = null;
                    //$repeats = $ehRepeat->getEvents(array_merge($params,["sql_only"=>true]));					
                    if (is_string($repeats)) {
                        $debug .= "strreq :\n" . $repeats . "\n";
                    }
                    $repeats = $ehRepeat->getEvents($params);
                    $counter = 0;
                    while ($repeats->fetch()) {
                        $debug .= "fetch " . ++$counter . "\n";
                        if (isset($values['id'])) {
                            $comment = $values['comment'][(integer) $repeats->rpt_evt];
                        } elseif (isset($values['by_title'][(integer) $repeats->post_id])) { //by_title
                            $comment = $values['comment'][$values['by_title'][0]];
                        } else {
                            $comment = "";
                        }
                        if (strlen($comment) > 0)
                            $comment = " title='" . $comment . "'";
                        $post_title = "";
                        if ($values['use_post_title'])
                            $post_title = " : " . $repeats->post_title;
                        $link1 = $link2 = "";
                        if ($values['set_link']) {
                            $link1 = "<a href='" . $repeats->getMasterURL() . "'>";
                            $link2 = "</a>";
                        }
                        $dt = dt::dt2str(__("%a %e %B"), $repeats->event_startdt, $repeats->post_tz);
                        if (!array_key_exists($dt, $events_list))
                            $events_list[$dt] = [];
                        $events_list[$dt][] = ['time' => dt::dt2str(__(" at %H:%M"), $repeats->event_startdt, $repeats->post_tz),
                            'link1' => $link1,
                            'title' => $post_title,
                            'link2' => $link2,
                            'comment' => $comment];
                    }
                    $debug .= '$events_list contient ' . count($events_list) . " dates.\n";
                    $debug .= '$events_list : ' . print_r($events_list, true) . "\n";
                    $dates_list = "";
                    $nbdates = count($events_list, COUNT_RECURSIVE);
                    global $plural;
                    $plural = ($nbdates > 1);


                    $title = isset($values["title"]) ? $values["title"] : __("Next events");
                    $title = preg_replace_callback('|\{([^\|]*)\|([^\}]*)\}|', function ($matches) {
                        global $plural;
                        return $matches[1 + $plural];
                    }, $title);

                    $slaves_header = "<div class='slavelist " . $values['align'] . "'><h4>" . $title . "</h4>\n";
                    if ($nbdates == 0) {
                        $slaves_header .= "<h6>" . ($values['none'] ? $values['none'] : __("No scheduled events")) . "</h6></div>";
                        $txt = str_replace($out[0][$k], $slaves_header, $txt);
                        continue;
                    } else if ($nbdates > 10) {
                        $slaves_header .= "<ul class='event-slaves'>\n";
                    } else {
                        $slaves_header .= "<ul class='event-slave'>\n";
                    }
                    $slaves_list = $slaves_header;
                    foreach ($events_list as $d => $events) {
                        if (count($events) == 1) {
                            $slaves_list .= "<li " . $events[0]['comment'] . ">" . $d . $events[0]['time'] .
                                    $events[0]['link1'] . $events[0]['title'] . $events[0]['link2'] . "</li>\n";
                            continue;
                        } else {
                            $slaves_list .= "<li> $d <ul>\n";
                        }
                        foreach ($events_list[$d] as $e) {
                            $slaves_list .= "\t<li " . $e['comment'] . ">" . $e['time'] . $e['link1'] . $e['title'] . $e['link2'] . "</li>\n";
                        }
                        $slaves_list .= "</ul></li>\n";
                    }
                    $slaves_list .= "</ul></div>\n";
                    $txt = str_replace($out[0][$k], $slaves_list, $txt);
                }
                //$txt.="\n<br><textarea rows=30 cols=120 class='debug' style='overflow:no'>".$debug."</textarea>";
            }
            $arr[0] = $txt;
        }
    }

    public static function publicHeadContent($core, $_ctx) {
        echo "<!--ehRepeat headers-->\n<script type=\"text/javascript\" src=\"" . $core->blog->getQmarkURL() . 'pf=ehRepeat/js/ehrepeat.public.js"></script>' . "\n";
        echo "<link rel=\"stylesheet\" type=\"text/css\" href=\"" . $core->blog->getQmarkURL() . "pf=ehRepeat/css/ehrepeat.public.css\" />\n";
    }

    /* Balises tpl */

    public static function EhRepeatReadableFrequency($attr) {
        return '<?php echo $_ctx->posts->getReadableFreq(); ?>';
    }

    public static function EhRepeatIf($attr, $content) {
        
    }

    public static function EventIfIsRepeat($attr, $content) {
        return '<?php if($_ctx->posts->isRepeat()) :?>' . $content . '<?php endif; ?>';
    }

    /* tpl:EhRepeatEventDates :
      loops through the available dates
      attr event : selects
     */

    public static function EhRepeatEventDates($attr, $content) {
        $p = '';
        if (isset($attr['event'])) {
            $p = "\$params['siblings_from_id'] = '" . abs((integer) $attr['event']) . "';\n";
        } else {
            $p = 'if ($_ctx->exists("posts") && $_ctx->posts->post_id) { ' .
                    "\$params['siblings_from_id'] = \$_ctx->posts->post_id;\n" .
                    "} else {\n" .
                    "\$params['all'] = true;\n" .
                    "}\n";
        }

        return "<?php\n" .
                'if(!isset($ehRepeat)) { $ehRepeat = new dcEhRepeat($core); } ' . "\n" .
                $p .
                '$_ctx->rpteventdates_params = $params;' . "\n" .
                /* 				'$rptsql = $ehRepeat->getEvents(array_merge($params,["sql_only"=>true]));'."\n".
                  'echo "<pre style=\"word-wrap: break-word;overflow: hidden;word-break: break-all;white-space: normal;\">Requête :\n$rptsql</pre>";'."\n". */
                '$_ctx->rpteventdates = $ehRepeat->getEvents($params); unset($params); ' . "\n" .
                'while ($_ctx->rpteventdates->fetch()) : ?>' . $content . '<?php endwhile; ' . "\n" .
                '$_ctx->rpteventdates = null; $_ctx->rpteventdates_params = null; ?>';
    }

    public static function EhRepeatEventDatesHeader($attr, $content) {
        return
                "<?php if (\$_ctx->rpteventdates->isStart()) : ?>" .
                $content .
                "<?php endif; ?>";
    }

    public static function EhRepeatEventDatesFooter($attr, $content) {
        return
                "<?php if (\$_ctx->rpteventdates->isEnd()) : ?>" .
                $content .
                "<?php endif; ?>";
    }

    public static function EventsEntryTime($a) {
        $format = !empty($a['format']) ? addslashes($a['format']) : '';
        $type = '';
        if (!empty($a['creadt'])) {
            $type = 'creadt';
        }
        if (!empty($a['upddt'])) {
            $type = 'upddt';
        }
        if (!empty($a['enddt'])) {
            $type = 'enddt';
        }
        if (!empty($a['startdt'])) {
            $type = 'startdt';
        }

        return self::tplValue($a, "\$_ctx->rpteventdates->getEventTime('" . $format . "','" . $type . "')");
    }

    public static function EhRepeatEventDate($a) {
        $format = !empty($a['format']) ? addslashes($a['format']) : '';
        $iso8601 = !empty($a['iso8601']);
        $rfc822 = !empty($a['rfc822']);
        $type = '';
        if (!empty($a['creadt'])) {
            $type = 'creadt';
        }
        if (!empty($a['upddt'])) {
            $type = 'upddt';
        }
        if (!empty($a['enddt'])) {
            $type = 'enddt';
        }
        if (!empty($a['startdt'])) {
            $type = 'startdt';
        }
        if ($rfc822) {
            return self::tplValue($a, "\$_ctx->rpteventdates->getEventRFC822Date('" . $type . "')");
        } elseif ($iso8601) {
            return self::tplValue($a, "\$_ctx->rpteventdates->getEventISO8601Date('" . $type . "')");
        } else {
            return self::tplValue($a, "\$_ctx->rpteventdates->getEventDate('" . $format . "','" . $type . "')");
        }
    }

    public static function EhRepeatInterval($a) {
        $code = <<<EOC
    <?php
        \$req = \$_SERVER['REQUEST_URI'];
        if (strpos(\$req, '/of/') === FALSE) {
            return "";
        }
        \$aReq = explode("/",explode("?",\$req)[0]);
        \$of=array_search("of",\$aReq);
        \$year=1*\$aReq[\$of+1];
        \$month=(isset(\$aReq[\$of+2]))?1*\$aReq[\$of+2]:-1;
            
EOC;
        $code .= "\$months=[-1=>'',1=>'" . __("January") . "','" . __("February") . "','" . __("March") . "','" .
                __("April") . "','" . __("May") . "','" . __("June") . "','" . __("July") . "','" . __("August") . "','" .
                __("September") . "','" . __("October") . "','" . __("November") . "','" . __("December") . "'];\n";
        $code .= "\$format='".__(" in %s %4d")."';\n";
        $code .= "echo sprintf(\$format,\$months[\$month],\$year);\n?>\n";
        return $code;        
    }

    # Generic template value

    protected static function tplValue($a, $v) {
        return '<?php echo ' . sprintf($GLOBALS['core']->tpl->getFilters($a), $v) . ';
        ?>';
    }

}

class EHExtension {

    public static function EhPublicBreadcrumb($urltype, $separator) {
        global $core;
        $ret = '';
        if ($urltype == 'eventhandler_list') {
            $ret = '<a id="bc-home" href="' . $core->blog->url . '">' . __('Home') . '</a>';
            $ret .= $separator . "Au Programme";
        } else if ($urltype == 'eventhandler_single') {
            global $_ctx;
            $ret = '<a id="bc-home" href="' . $core->blog->url . '">' . __('Home') . '</a>';
            $ret .= $separator . "Au Programme : " . $_ctx->posts->post_title;
        }
        return $ret;
    }

}
?>