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

if (!defined('DC_CONTEXT_ADMIN')) { return; }

if (!empty($_GET['popup'])) {
	include dirname(__FILE__).'/popup.php';
} elseif (!empty($_GET['dotslickjs'])) {
	include dirname(__FILE__).'/inc/dotslick.js.php';
        exit();
}else{
	include dirname(__FILE__).'/admin.php';
}
