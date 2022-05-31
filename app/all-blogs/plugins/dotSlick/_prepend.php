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

if (!defined('DC_RC_PATH')) {
    return;
}

error_reporting(E_ALL);
ini_set('display_errors', '1');

global $__autoload, $core;

$__autoload['dotSlickAdmin'] = dirname(__FILE__) . '/inc/class.dotslickadmin.php';
$__autoload['dsMedia'] = dirname(__FILE__) . '/inc/class.dsmedia.php';
$__autoload['dsGalleries'] = dirname(__FILE__) . '/inc/class.dsgalleries.php';

$__autoload['dotSlickRestMethods'] = dirname(__FILE__) . '/_services.php';



# parsefreq rest method  (for ajax service)
$core->rest->addFunction('dotSlickGetHTML', array('dotSlickRestMethods', 'dotSlickGetHTML'));
$core->rest->addFunction('getMediaTree', array('dotSlickRestMethods', 'getMediaTree'));
$core->rest->addFunction('getMedias', array('dotSlickRestMethods', 'getMedias'));
$core->rest->addFunction('getGalleryImage', array('dotSlickRestMethods', 'getGalleryImage'));



