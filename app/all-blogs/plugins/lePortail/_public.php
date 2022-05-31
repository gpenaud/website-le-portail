<?php

# -- BEGIN LICENSE BLOCK ----------------------------------
#
# This file is part of lePortail, a plugin for Dotclear 2
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

$core->tpl->addValue('CategoryID', array('dcPortail', 'CategoryID'));
$core->tpl->addValue('EntryCategoryParent', array('dcPortail', 'EntryCategoryParent'));
$core->tpl->addValue('CategoryParent', array('dcPortail', 'CategoryParent'));

class dcPortail {
    /* 	tpl:EntryCategoryParent : retrieves the top level category for the current post
     *   With no arg, returns the category title
     * 	 @param url retrieves the URL
     * 	 @param shorturl retrieves the short URL
     *   @param ID retrieves the category ID
     * 
     */

    public static function EntryCategoryParent($a) {
        $ret1 = 'if($_ctx->posts->cat_id != null) :' . "\n" .
                '$_ctx->entrycategories = $core->blog->getCategoryParents($_ctx->posts->cat_id);' . "\n" .
                '//while(!$_ctx->entrycategories->isEnd()){$_ctx->entrycategories->fetch();}' . "\n" .
                'if($_ctx->entrycategories->count()==0){$_ctx->entrycategories=$core->blog->getCategory($_ctx->posts->cat_id);}' . "\n";
        if (isset($a['url']) && $a['url']) {
            $ret = '$core->blog->url.$core->url->getURLFor("category",' .
                    '$_ctx->entrycategories->cat_url)';
        } elseif (isset($a['shorturl']) && $a['shorturl']) {
            $ret = '$_ctx->entrycategories->cat_url';
        } elseif (isset($a['id']) && $a['id']) {
            $ret = '$_ctx->entrycategories->cat_id';
        } else {
            $ret = '$_ctx->entrycategories->cat_title';
        }
        $ret2 = 'unset($_ctx->entrycategories);' . "\n" .
                'else:' . "\n" .
                '  echo "";' . "\n" .
                'endif'
        ;

        return '<?php ' . $ret1 . "echo " . sprintf($GLOBALS['core']->tpl->getFilters($a), $ret) . ';' . $ret2 . '?>';
    }

    public static function CategoryParent($a) {
        $ret1 = '$_ctx->pcategories = $core->blog->getCategoryParent($_ctx->categories->cat_id);' . "\n" .
                'if($_ctx->pcategories->count()==0){$_ctx->pcategories=$core->blog->getCategory($_ctx->categories->cat_id);}' . "\n";

        if (isset($a['url']) && $a['url']) {
            $ret = '$core->blog->url.$core->url->getURLFor("category",' .
                    '$_ctx->pcategories->cat_url)';
        } elseif (isset($a['shorturl']) && $a['shorturl']) {
            $ret = '$_ctx->pcategories->cat_url';
        } elseif (isset($a['id']) && $a['id']) {
            $ret = '$_ctx->pcategories->cat_id';
        } else {
            $ret = '$_ctx->pcategories->cat_title';
        }

        $ret2 = 'unset($_ctx->pcategories);' . "\n";

        return '<?php ' . $ret1 . "echo " . sprintf($GLOBALS['core']->tpl->getFilters($a), $ret) . ';' . $ret2 . '?>';
    }

    public static function CategoryID($a) {
        return '<?php echo ' . sprintf($GLOBALS['core']->tpl->getFilters($a), "\$_ctx->categories->cat_id") . '; ?>';
    }

}
