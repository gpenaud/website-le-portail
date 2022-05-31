<?php

// -- BEGIN LICENSE BLOCK ----------------------------------
//
// This file is part of lePortail, a plugin for Dotclear 2.
// 
// Copyright (c) 2019 Bruno Avet
// Licensed under the GPL version 2.0 license.
// A copy of this license is available in LICENSE file or at
// http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
//
// -- END LICENSE BLOCK ------------------------------------

if (!defined('DC_CONTEXT_ADMIN')) {
    return;
}

if (!$core->plugins->moduleExists("dcCKEWidget")) {
    dcPage::addErrorNotice(__("lePortail requires dcCKEWidget plugin to operate. Please install and enable it first"));
    return;
}

$core->addBehavior('ckeditorExtraPlugins', array('lePortailAdminBehaviors', 'ckeditorExtraPlugins'));
$core->addBehavior('coreAfterPostContentFormat', array('lePortailAdminBehaviors', 'coreAfterPostContentFormat'));

$core->addBehavior('adminAfterPostUpdate', array('lePortailAdminBehaviors', 'adminPostAfterUpdateCreateDelete'));
$core->addBehavior('adminAfterPostCreate', array('lePortailAdminBehaviors', 'adminPostAfterUpdateCreateDelete'));
$core->addBehavior('adminBeforePostDelete', array('lePortailAdminBehaviors', 'adminPostAfterUpdateCreateDelete'));

class lePortailAdminBehaviors {

    public static function ckeditorExtraPlugins(ArrayObject $extraPlugins, $context) {
        if ($context !== 'post' && $context !== 'page')
            return;

        $extraPlugins[] = array(
            'name' => 'leportail',
            'button' => 'leportail',
            'url' => DC_ADMIN_URL . 'index.php?pf=lePortail/cke-addon/'
        );
    }

    static function coreAfterPostContentFormat($arr) {
        $news_reg = "/(?:<p>)\[news_list\b([^\]]*)\](?:<\/p>)/is";

        if (preg_match($news_reg, $arr["excerpt_xhtml"])) {
            $arr["excerpt_xhtml"] = preg_replace_callback($news_reg, ["self", "parseNews"], $arr["excerpt_xhtml"]);
        }
        if (preg_match($news_reg, $arr["content_xhtml"])) {
            $arr["content_xhtml"] = preg_replace_callback($news_reg, ["self", "parseNews"], $arr["content_xhtml"]);
        }
    }

    protected static function parseNews($out) {
        global $core;
        $values = array("order" => ["post_dt DESC"], "align" => "", "set_title" => false, "set_link" => false);
        $tinout = array();
        preg_match_all('`(\w+)\s*=\s*\"([^\"]*)\"`isU', $out[1], $tinout);
        foreach ($tinout[1] as $k2 => $v2) {
            if (!isset($values[$v2])) {
                $values[$v2] = array();
            }
            switch ($v2) {
                case "set_link":
                    $values['set_link'] = (boolean) $tinout[2][$k2];
                    break;
                default:
                    $values[$v2] = $tinout[2][$k2];
            }
        }

        $news_list = [];
        $params = ["sql" => " AND post_selected = 1 "];
        if (isset($values["order"])) {
            $params["order"] = $values["order"][0];
        }

        $posts = $core->blog->getPosts($params);
        while ($posts->fetch()) {
            if ($values['set_title']) {
                $tooltip = strip_tags(str_replace('"', '\"', $posts->post_excerpt));
            }
            $link1 = $link2 = "";
            if ($values['set_link']) {
                $link1 = "<a title=\"" . $tooltip . "\" href='" . $posts->getURL() . "'>";
                $link2 = "</a>";
            }
            $news_list[] = ['date' => dt::dt2str(__("%e/%m/%Y"), $posts->post_dt, $posts->post_tz),
                'link1' => $link1,
                'title' => $posts->post_title,
                'link2' => $link2];
        }
        $nbposts = count($news_list, COUNT_RECURSIVE);
        global $plural;
        $plural = ($nbposts > 1);

        $title = preg_replace_callback('|\{([^\|]*)\|([^\}]*)\}|', function ($matches) {
            global $plural;
            return $matches[1 + $plural];
        }, isset($values["title"]) ? $values["title"] : __("Fresh news"));

        $news_header = "<div class='newslist " . $values['align'] . "'><h4>" . $title . "</h4>\n";
        if ($nbposts == 0) {
            if ($values["none"] == "") {
                return "<div class='newslist none'></div>";
            } else {
                $news_header .= "<h6>" . ($values['none'] ? $values['none'] : __("No fresh news")) . "</h6></div>";
                return $news_header;
            }
        } else if ($nbposts > 10) {
            $news_header .= "<dl class='news10'>\n";
        } else {
            $news_header .= "<dl class='news'>\n";
        }
        $newshtml = $news_header;
        foreach ($news_list as $news) {
            $newshtml .= sprintf("<dt>%s</dt><dd>%s%s%s</dd>\n", $news['date'], $news['link1'], $news['title'], $news['link2']);
        }
        $newshtml .= "</dl></div>\n";
        return $newshtml;
    }

    /* Refresh all news when a post is modified */

    static function adminPostAfterUpdateCreateDelete($a, $b = null) {
        global $core;
        $res = $core->blog->getPosts(["post_type" => ["post", "page"],
            "where" => ' AND ( P.post_content LIKE "%[news_list%" OR P.post_excerpt LIKE "%[news_list%") ']);
        $count = 0;
        if($a instanceof cursor){ //create or update
            $id = $b;
        }else{  //delete
            $id = $a;
        }
        
        while ($res->fetch()) {
            try {
                if(isset($res->post_id) && ($res->post_id === $id)){
                    continue;
                }
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
                $core->error->add(__METHOD__ . ':' . sprintf(__("problem with news %d regeneration."), $res->post_id) . "\nError message is :" . $e->getMessage());
            }
        }
        if ($count > 0) {
            dcPage::addSuccessNotice(sprintf(__("Updated %d post's newslist","Updated %d posts' newslists",$count), $count));
        }
    }

}
