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

if (!defined('DC_CONTEXT_ADMIN')) { exit; }

$this_version = $core->plugins->moduleInfo('dotSlick','version');
$installed_version = $core->getVersion('dotSlick');
if (version_compare($installed_version,$this_version,'>=')) {
	return;
}

if(!$core->plugins->moduleExists("dcCKEWidget")){
    dcPage::addErrorNotice(__("dotSlick requires dcCKEWidget plugin to operate. Please install and enable it first"));
    return false;
}

# Settings
$core->blog->settings->addNamespace('dotslick');
$s =& $core->blog->settings->dotslick;

$s->put('dotslick_enabled',true,'boolean',__('Enable dotSlick plugin'));
$s->put('post_types','post,page','string',__('Extra post types which use dotslick'));
$s->put('autoplay',true,'boolean',__('Autoplay galleries'));
$s->put('infinite',true,'boolean',__('Loop galleries infinitely'));
$s->put('pauseOnHover',true,'boolean',__('Pause slideshow when cursor is over'));
$s->put('autoplaySpeed',5000,'integer',__('Gallery autoplay slide changing delay'));
$s->put('mousewheel',true,'boolean',__('Enable mousewheel navigation'));

$s->put('linkto',true,'boolean',__('Make gallery pictures clickable'));
$s->put('dots',true,'boolean',__('Display navigation dots'));
$s->put('arrows',true,'boolean',__('Display navigation arrows'));
$s->put('height',400,'integer',__('Gallery height in pixels'));

mkdir(dirname(__FILE__) .'/cache');

$core->setVersion('dotSlick',$this_version);
return true;

