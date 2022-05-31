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

$this->registerModule(
	/* Name */			"contextBg",
	/* Description*/		"Manage context related background",
	/* Author */			"Onurbruno",
	/* Version */			'2019-01-09',
		array(
		/* Permissions */	'permissions' =>	'contentadmin',
		/* Type */			'type' =>			'plugin',
		/* Priority */		'priority' => 		100
	)
);
?>