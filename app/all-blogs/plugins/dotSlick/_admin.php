<?php

// -- BEGIN LICENSE BLOCK ----------------------------------
//
// This file is part of dotSlick, a plugin for Dotclear 2.
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
    dcPage::addErrorNotice(__("dotSlick requires dcCKEWidget plugin to operate. Please install and enable it first"));
    return;
}

if (isset($__dashboard_icons) && $core->auth->check('dotSlick', $core->blog->id)) {
    $__dashboard_icons[] = array(__('dotSlick'), 'plugin.php?p=dotSlick', 'index.php?pf=dotSlick/dotslick64.png');
}

$_menu['Blog']->addItem('dotSlick', 'plugin.php?p=dotSlick', 'index.php?pf=dotSlick/dotslick16.png', preg_match('/plugin.php\?p=dotSlick(&.*)?$/', $_SERVER['REQUEST_URI'])
);

$core->addBehavior('adminPostHeaders', array('dotSlickAdminBehaviors', 'jsLoad'));
$core->addBehavior('adminPageHeaders', array('dotSlickAdminBehaviors', 'jsLoad'));
$core->addBehavior('adminRelatedHeaders', array('dotSlickAdminBehaviors', 'jsLoad'));
$core->addBehavior('adminDashboardHeaders', array('dotSlickAdminBehaviors', 'jsLoad'));

$core->addBehavior('ckeditorExtraPlugins', array('dotSlickAdminBehaviors', 'ckeditorExtraPlugins'));

$core->addBehavior('adminPopupMedia', array('dotSlickAdminBehaviors', 'adminPopupMedia'));
$core->addBehavior('adminPostEditor', array('dotSlickAdminBehaviors', 'adminPostEditor'));

$core->addBehavior('adminDashboardFavs', array('dotSlickAdminBehaviors', 'dashboardFavs'));

class dotSlickAdminBehaviors {

    public static function dashboardFavs($core, $favs) {
        $favs['dotSlick'] = new ArrayObject(array(
            'dotSlick',
            __('dotSlick gallery'),
            'plugin.php?p=dotSlick',
            'index.php?pf=dotSlick/dotslick16.png',
            'index.php?pf=dotSlick/dotslick64.png',
            'usage,contentadmin',
            null,
            null));
    }

    public static function jsLoad() {
        # Settings
        if (isset($GLOBALS['core']->blog->settings->dotslick) && !$GLOBALS['core']->blog->settings->dotslick->dotslick_enabled)
            return;

        return dcPage::jsLoad(DC_ADMIN_URL . 'index.php?pf=dotSlick/cke-addon/dotSlick.js');
    }

    public static function ckeditorExtraPlugins(ArrayObject $extraPlugins, $context) {
        if ($context !== 'post' && $context !== 'page')
            return;

        $extraPlugins[] = array(
            'name' => 'dotslick',
            'button' => 'dotSlick',
            'url' => DC_ADMIN_URL . 'index.php?pf=dotSlick/cke-addon/'
        );
    }

//    public static function adminPopupMedia($editor = '') {
//        if (empty($editor) || $editor != 'dotSlick') {
//            return;
//        }
//
//        return dcPage::jsLoad(DC_ADMIN_URL . 'index.php?pf=dotSlick/js/popup_media.js');
//    }

    public static function adminPostEditor($editor = '', $context = '', array $tags = array(), $syntax = 'xhtml') {
        if (empty($editor) || $editor != 'dcCKEditor' || $syntax != 'xhtml') {
            return;
        }
        if ($context == "post" || $context == "page") {
            $jsurl = 'plugin.php?p=dotSlick&dotslickjs=1';



            $res = dcPage::jsLoad($jsurl) . '
        <script type="text/javascript">
            //dotslick CKEditor tweaks go here

        </script>
';

            return $res;
        } else {
            return "";
        }
    }

}

$core->addBehavior('coreAfterPostContentFormat', array('dotSlickBehaviorContent', 'coreAfterPostContentFormat'));

class dotSlickBehaviorContent {

    public static function coreAfterPostContentFormat($arr) {
        $settings = & $GLOBALS['core']->blog->settings->dotslick;
        if (!$settings->dotslick_enabled)
            return;
        global $dsTitles;
        global $descs;
        global $run_count; // because of a bug? in post.php coreAfterPostContentFormat is triggered twice
        // and causes multiple notifications.
        if (!isset($run_count)) {
            $run_count = [];
        }

        $savedotslick = (isset($_POST["savedotslick"])?$_POST["savedotslick"]:null);
        $dsTitles = [];
        $dotslick_reg = "|<div class=\"wds\">\s*<h4>([^<]*)</h4>\s*<p>::dotslick([^:]*)::</p>\s*</div>|i";
        $dotslick_reg_min = "|<p>::dotslick[^:]*::<\/p>|";
        // Remplacement de la chaine par la galerie dans le billet
        $count = 0;
        $descs = [];
        if (preg_match($dotslick_reg_min, $arr["excerpt_xhtml"])) {
            $count_e = 0;
            $arr["excerpt_xhtml"] = preg_replace_callback($dotslick_reg, array('self', 'ParseDotSlick'), $arr["excerpt_xhtml"], -1, $count_e);
            $count += $count_e;
        }
        if (preg_match($dotslick_reg_min, $arr["content_xhtml"])) {
            $count_c = 0;
            $arr["content_xhtml"] = preg_replace_callback($dotslick_reg, array('self', 'ParseDotSlick'), $arr["content_xhtml"], -1, $count_c);
            $count += $count_c;
        }

        if ($count == 0)
            return;

        /* Check to see if the current page is the post.php edited page by comparing the hidden desc id inputs
          with the ids present in descs
          if true, we display the success notice. Else, it means that the current dotslick is on a recomputed page
         */
        if ($savedotslick !== null) {
            $ok = true;
            if (!is_array($savedotslick)) {
                $savedotslick = [$savedotslick];
            }
            foreach ($descs as $D) {
                $ok &= in_array($D, $savedotslick);
            }

            $updating = (((basename($_SERVER["SCRIPT_NAME"]) == "post.php") || ((basename($_SERVER["SCRIPT_NAME"]) == "plugin.php") && ($_GET["p"] == "pages"))) && $_POST["id"]);

            $d = $descs[0]; //we use the first description in the page as a marker for the run _count variable.
            if (!isset($run_count[$d])) {
                $run_count[$d] = 0;
            }

            if ($updating && $run_count[$d] == 0) {
                $run_count[$d] = 1;
                $ok = false;
            }
            if ($ok && $count > 0) {
                array_walk($dsTitles, function(&$e) {
                    $e = "«" . $e . "»";
                });
                $title = count($dsTitles) === 1 ? $dsTitles[0] : join(__(" & "), [join(", ", array_slice($dsTitles, 0, -1)), (array_slice($dsTitles, -1, 1)[0])]);
                dcPage::addSuccessNotice(sprintf(__("dotSlick Gallery successfully generated for %s"), $title));
            }
        }

        unset($dsTitles);
        unset($descs);
    }

    protected static function ParseDotSlick($m) {
        $desc = $m[2];
        $title = htmlentities($m[1], ENT_QUOTES);
        global $descs;
        global $dsTitles;
        try {
            $dotSlick = new dotSlickAdmin($desc);
            $descs[] = (string) $dotSlick->id;
            $dsTitles[] = $title;
            $code = $dotSlick->code;
            return "<div class=\"wds\"><h4>" . $title . "</h4>\n" . $code . "\n</div>\n<!--initial desc :\n" . $desc . "-->\n";
        } catch (Exception $e) {
            dcPage::addErrorNotice(sprintf(__("dotSlick Gallery compilation error with string «%s»<br/>%s"), $desc, $e->getMessage()));
        }
    }

}
