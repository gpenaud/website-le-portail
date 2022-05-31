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

if (!defined('DC_CONTEXT_ADMIN')) {
    return;
}

if (!$core->plugins->moduleExists("dcCKEWidget")) {
    dcPage::addErrorNotice(__("lePortail requires dcCKEWidget plugin to operate. Please install and enable it first"));
    return;
}



if (isset($__dashboard_icons) && $core->auth->check('ehRepeat', $core->blog->id)) {
    $__dashboard_icons[] = array(__('Repetitive events'), 'plugin.php?p=ehRepeat', 'index.php?pf=ehRepeat/icon.png');
}

$_menu['Plugins']->addItem(__('Repetitive events'), 'plugin.php?p=ehRepeat', 'index.php?pf=ehRepeat/icon-small.png', preg_match('/plugin.php\?p=ehRepeat(&.*)?$/', $_SERVER['REQUEST_URI']), $core->auth->check('usage,contentadmin', $core->blog->id));


/* Add ehrepeat events to events page */
$core->addBehavior('adminEventHandlerActionsCombo', array('dcEhRepeatAdmin', 'adminEventHandlerActionsCombo'));
//$core->addBehavior('adminEventHandlerEventsPageCustomize', array('dcEhRepeatAdmin', 'adminEventHandlerEventsPageCustomize'));

/* Add ehrepeat functionnality to event edition page */
$core->addBehavior('adminEventHandlerHeaders', array('dcEhRepeatAdmin', 'adminEventHandlerHeaders'));
$core->addBehavior('adminEventHandlerFormSidebar', array('dcEhRepeatAdmin', 'adminEventHandlerFormSidebar'));
$core->addBehavior('adminAfterEventHandlerCreate', array('dcEhRepeatAdmin', 'adminAfterEventHandlerUpdateCreate'));
$core->addBehavior('adminAfterEventHandlerUpdate', array('dcEhRepeatAdmin', 'adminAfterEventHandlerUpdateCreate'));


/* Manage event deletion by cascading to ehRepeat */
$core->addBehavior('adminBeforeEventHandlerDelete', array('dcEhRepeatAdmin', 'adminBeforeEventHandlerDelete'));

$core->addBehavior('ckeditorExtraPlugins', array('dcEhRepeatAdmin', 'ckeditorExtraPlugins'));


/* manage [event_list] at post create or update */
$core->addBehavior('coreAfterPostContentFormat', array('dcEhRepeatAdmin', 'coreAfterPostContentFormat'));

class dcEhRepeatAdmin {

    public function adminEventHandlerEventsPageCustomize($params, $sortby_combo, $show_filters, $redir, $hidden_fields) {
        
    }

    public function adminEventHandlerActionsCombo($combo_action) {
        
    }

    public static function adminEventHandlerHeaders() {
        echo '<link rel="stylesheet" href="index.php?pf=ehRepeat/css/eventsidebar.css">' . "\n";
        echo '<script src="index.php?pf=ehRepeat/js/eventsidebar.js" type="text/javascript">' . "\n" . '</script>' . "\n";
    }

    public static function adminEventHandlerFormSidebar($post) {
        /* Si l'événement est en cours de création, $id==null */
        /* Si $id==null, utiliser postAfterCreate pour enregistrer l'ehRepeat */
        global $core, $ehRepeat;
        if (!isset($ehRepeat))
            $ehRepeat = new dcEhRepeat($core);

        $id = $post ? $post->post_id : null;
        $rpt_freq = "";
        $rpt_freq_desc = __("Click to edit the repetition pattern");
        $rpt_id = -1;
        $event_repeatable = false;

        if ($id) {
            $rs = $ehRepeat->getEvents(array("event_id" => $id));
            if ($rs->isRepeat()) {
                $event_repeatable = true;
                $rpt_freq = $rs->rpt_freq;
                $rpt_id = $rs->rpt_id;
                $rpt_freq_desc = $rs->getReadableFreq();
            }
        }
        include ('tpl/eventsidebar.tpl');
    }

    public static function adminAfterEventHandlerUpdateCreate($cur_post, $cur_evt, $event_id) {
        global $core, $ehRepeat;
        if (!isset($ehRepeat))
            $ehRepeat = new dcEhRepeat($core);


        if (isset($_POST["event_repeatable"]) && !empty($_POST["event_repeatable"])) {
            $freq = $_POST["rpt_freq"];
            $rpt_id = $_POST["rpt_id"];
            if ($freq == "")
                return;
            if ($rpt_id == -1) {
                $ehRepeat->add_repeat($event_id, $freq);
            } else {
                $ehRepeat->update_repeat($rpt_id, $event_id, $freq);
            }
        } elseif (empty($_POST["event_repeatable"])) {
            $rpt_id = $_POST["rpt_id"];
            if ($rpt_id != -1) {
                $ehRepeat->delete($rpt_id);
            }
        }
        self::updatePostsWithEventList();
    }

    public static function adminBeforeEventHandlerDelete($evt_id) {
        /* Cascader l'effacement de.s ehrepeat lié.s */
        global $core, $ehRepeat;
        if (!isset($ehRepeat))
            $ehRepeat = new dcEhRepeat($core);
        $ehRepeat->delete_by_event($evt_id);
        self::updatePostsWithEventList();
    }

    public static function ckeditorExtraPlugins(ArrayObject $extraPlugins, $context) {
        if ($context !== 'post' && $context !== 'page')
            return;
        $extraPlugins[] = array(
            'name' => 'events',
            'button' => 'events',
            'url' => DC_ADMIN_URL . 'index.php?pf=ehRepeat/cke-addon/'
        );
    }

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

    public static function coreAfterPostContentFormat($arr) {
        $settings = & $GLOBALS['core']->blog->settings->dotslick;
        if (!$settings->dotslick_enabled)
            return;

        $ehrepeat_reg = "|(?:<p>)\[event_list\b([^\]]*)\](?:<\/p>)|is";
// Remplacement de la chaine par la galerie dans le billet
        if(preg_match($ehrepeat_reg,$arr["excerpt_xhtml"])){
            $arr["excerpt_xhtml"] = preg_replace_callback($ehrepeat_reg, array('self', 'parseEhRepeat'), $arr["excerpt_xhtml"]);
        }
        if(preg_match($ehrepeat_reg,$arr["content_xhtml"])){
            $arr["content_xhtml"] = preg_replace_callback($ehrepeat_reg, array('self', 'parseEhRepeat'), $arr["content_xhtml"]);
        }
    }

    protected static function parseEhRepeat($out) {
        global $core;
        $values = array("align" => "", "mode" => null, "use_post_title" => false, "set_link" => false, "none" => "");
        $tinout = array();
        preg_match_all('`(\w+)\s*=\s*\"([^\"]*)\"`isU', $out[1], $tinout);
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

        $ehRepeat = isset($core->ehRepeat) ? $core->ehRepeat : new dcEhRepeat($core);

        $repeats = $ehRepeat->getEvents($params);
        while ($repeats->fetch()) {
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
            $dt = dt::dt2str(__("%a %e %B"), $repeats->event_startdt);
            if (!array_key_exists($dt, $events_list))
                $events_list[$dt] = [];
            $events_list[$dt][] = ['time' => dt::dt2str(__(" at %H:%M"), $repeats->event_startdt),
                'link1' => $link1,
                'title' => $post_title,
                'link2' => $link2,
                'comment' => $comment];
        }
        $nbdates = count($events_list, COUNT_RECURSIVE);
        global $plural;
        $plural = ($nbdates > 1);

        $title = preg_replace_callback('|\{([^\|]*)\|([^\}]*)\}|', function ($matches) {
            global $plural;
            return $matches[1 + $plural];
        }, isset($values["title"]) ? $values["title"] : __("Next events"));

        $slaves_header = "<div class='slavelist " . $values['align'] . "'><h4>" . $title . "</h4>\n";
        if ($nbdates == 0) {
            $slaves_header .= "<h6>" . ($values['none'] ? $values['none'] : __("No scheduled events")) . "</h6></div>";
            if ($values['none'] == "") {
                return "<div class='slavelist none'></div>";
            } else {
                return $slaves_header;
            }
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
        return $slaves_list;
    }

    protected static function updatePostsWithEventList() {
        global $core;
        $res = $core->blog->getPosts(["post_type"=>["post","page"],
            "where" => ' AND ( P.post_content LIKE "%[event_list%" OR P.post_excerpt LIKE "%[event_list%") ']);
        $count=0;
        while ($res->fetch()) {
            try {
                $cur = $core->con->openCursor($core->prefix . 'post');
                $cur->post_title = $res->post_title;
                $cur->cat_id = ($res->cat_id ?: null);
                $cur->post_dt = $res->post_dt ? date('Y-m-d H:i:00', strtotime($res->post_dt)) : '';
                $cur->post_format = $res->post_format;
                $cur->post_password = $res->post_password;
                $cur->post_lang = $res->post_lang;
                $cur->post_title = $res->post_title;
                $cur->post_excerpt = $res->post_excerpt;
                $cur->post_excerpt_xhtml = $res->post_excerpt_xhtml;
                $cur->post_content = $res->post_content;
                $cur->post_content_xhtml = $res->post_content_xhtml;
                $cur->post_notes = $res->post_notes;
                $cur->post_status = $res->post_status;
                $cur->post_selected = (integer) $res->post_selected;
                $cur->post_open_comment = (integer) $res->post_open_comment;
                $cur->post_open_tb = (integer) $res->post_open_tb;

                $core->blog->con->writeLock('P');
                $core->blog->updPost($res->post_id, $cur);
                $core->blog->con->unlock();
                $count++;
            } catch (Exception $e) {
                $core->error->add(__METHOD__ . ':' . sprintf(__("problem with event list %d regeneration."),$res->post_id) . "\nError message is :" . $e->getMessage());
            }
        }
        if($count>0){
            dcPage::addSuccessNotice(sprintf(__("Updated %d post's eventlist","Updated %d posts' eventlists",$count),$count));
        }
    }
}
