<?php
# -- BEGIN LICENSE BLOCK ----------------------------------
#
# This file is part of contextBG a plugin for Dotclear 2.
# 
# Copyright (c) 2015 Onurbruno
# Licensed under the GPL version 2.0 license.
# A copy of this license is available in LICENSE file or at
# http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
#
# -- END LICENSE BLOCK ------------------------------------

if (!defined('DC_CONTEXT_ADMIN')) {return;}
if(!$core->blog->settings->contextBg){
	$core->blog->settings->addNamespace('contextBg');
	$settings=$core->blog->settings->contextBg;
	$settings->put('active',false,'boolean',_('Enable this plugin'));
	$settings->put('selector','body','string',_('Selector for the element to apply the background in'));
	$settings->put('pattern','contextBg/bg-%d',string,__('Pattern for background image files'));
	$settings->put('multipattern','contextBg/bg-%d_%d',string,__('Pattern for multiple background image files'));
	$settings->put('css','',string,__('Extra css definitions'));
	$settings->put('default','contextBg/default.jpg',string,__('Default background image'));
	$settings->put('field','cat_id',string,__('Field to use for background selection'));	
	$settings->put('excludehome',true,'boolean',_('Disable for homepage'));
}

if (isset($__dashboard_icons) && $core->auth->check('contextBg',$core->blog->id)) {
		$__dashboard_icons[] = array(__('Context Background'),'plugin.php?p=contextBg','index.php?pf=contextBg/icon.png');
}

$_menu['Plugins']->addItem(__('Context Background'),'plugin.php?p=contextBg','index.php?pf=contextBg/icon-small.png',
                preg_match('/plugin.php\?p=contextBg(&.*)?$/',$_SERVER['REQUEST_URI']),
                $core->auth->check('usage,contentadmin',$core->blog->id));

/* require dirname(__FILE__).'/_widgets.php'; */
?>