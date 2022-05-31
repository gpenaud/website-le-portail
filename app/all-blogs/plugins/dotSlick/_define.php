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

if (!defined('DC_RC_PATH')) { return; }

$this->registerModule(
	/* Name */			"dotSlick",
	/* Description*/                __("Insert slick galleries in your posts"),
	/* Author */			"Bruno Avet",
	/* Version */			'20190709.1',
	/* Permissions */		['permissions'=>'usage,contentadmin',
                                         'priority'=>70]
);

