<?php
# -- BEGIN LICENSE BLOCK ----------------------------------
#
# This file is part of Colorbox, a plugin for Dotclear 2.
#
# Copyright (c) 2010-2013 Philippe aka amalgame and Tomtom
# Licensed under the GPL version 2.0 license.
# See LICENSE file or
# http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
#
# -- END LICENSE BLOCK ------------------------------------

if (!defined('DC_RC_PATH')) { return; }

$this->registerModule(
	/* Name */				"Colorbox",
	/* Description*/		"A lightweight customizable lightbox",
	/* Author */			"Philippe aka amalgame and Tomtom",
	/* Version */			'3.1',
	array(
		'permissions' =>	'contentadmin',
		'type' => 'plugin',
		'dc_min' => '2.6'
	)
);
?>