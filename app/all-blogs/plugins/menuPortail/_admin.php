<?php
# ***** BEGIN LICENSE BLOCK *****
# This file is part of DotClear.
# Copyright (c) 2005 Olivier Meunier and contributors. All rights
# reserved.
#
# DotClear is free software; you can redistribute it and/or modify
# it under the terms of the GNU General Public License as published by
# the Free Software Foundation; either version 2 of the License, or
# (at your option) any later version.
# 
# DotClear is distributed in the hope that it will be useful,
# but WITHOUT ANY WARRANTY; without even the implied warranty of
# MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
# GNU General Public License for more details.
# 
# You should have received a copy of the GNU General Public License
# along with DotClear; if not, write to the Free Software
# Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
#
# ***** END LICENSE BLOCK *****

if (isset($__dashboard_icons) && $core->auth->check('menuPortail',$core->blog->id)) {
	$__dashboard_icons[] = array(__('Menu Portail'),'plugin.php?p=menuPortail','index.php?pf=menuPortail/icon.png');
}


$_menu['Blog']->addItem('Menu Portail','plugin.php?p=menuPortail','index.php?pf=menuPortail/icon-small.png',
                preg_match('/plugin.php\?p=menuPortail(&.*)?$/',$_SERVER['REQUEST_URI']),
                $core->auth->check('usage,contentadmin',$core->blog->id));

$core->auth->setPermissionType('menuPortail',__('manage menu'));

$core->addBehavior('adminAfterCategoryCreate','menuPortailOnCategoryCreate');

$core->addBehavior('adminAfterCategoryUpdate','menuPortailOnCategoryUpdate');

$core->addBehavior('adminAfterPostCreate','menuPortailOnPostCreate');

$core->addBehavior('adminAfterPostUpdate','menuPortailOnPostUpdate');

$core->addBehavior('adminPageHTMLHead','menuPortailPageHTMLHead');

require_once dirname(__FILE__).'/class.dc.blogmenu.php';

function menuPortailOnCategoryCreate($cur,$cat_id){
	global $core;
	$menu = new dcBlogMenu($core->blog);
	$menu->updateAllLinks();
}

function menuPortailOnCategoryUpdate($cur,$cat_id){
	global $core;
	$menu = new dcBlogMenu($core->blog);
	$menu->updateAllLinks();
}

function menuPortailOnPostCreate($cur,$post_id){
	global $core;
	$menu = new dcBlogMenu($core->blog);
	$menu->updateAllLinks();
}

function menuPortailOnPostUpdate($cur,$post_id){
	global $core;
	$menu = new dcBlogMenu($core->blog);
	$menu->updateAllLinks();
}

function menuPortailPageHTMLHead(){
    echo <<< EOF
    <script type="text/javascript">
    //<![CDATA[
        $(document).ready(function(){
            $("#enable_menu").change(function(){
                if($(this).is(":checked")){
                    $("#enable_menu+fieldset").show();
                }else{
                    $("#enable_menu+fieldset").hide();
                }
            });
        });
    //]]>
    </script>
EOF;
}

$core->addBehavior('adminDashboardFavs', 'adminDashboardFavs');

function adminDashboardFavs($core, $favs){
       $favs['menuPortail'] = new ArrayObject(
               array(
                   'menuPortail',
                   __('Menu Portail'),
                   'plugin.php?p=menuPortail',
                   'index.php?pf=menuPortail/icon-small.png',
                   'index.php?pf=menuPortail/icon.png',
                    'usage,contentadmin',
                    null,
                    null));
    
}

/* require dirname(__FILE__).'/_widgets.php'; */
?>