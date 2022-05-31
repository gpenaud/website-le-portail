<?php

/* 
  *  This file is part of dcCKEWidget, a plugin for Dotclear 2.
  *  
  *  Copyright (c) 2019 Bruno Avet
  *  Licensed under the GPL version 2.0 license.
  *  A copy of this license is available in LICENSE file or at
  * http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
 */

if (!defined('DC_RC_PATH')) { return; }

$this->registerModule(
	/* Name */			"dcCKEWidget",
	/* Description*/                _("Add widgets to the post editor"),
	/* Author */			"Bruno Avet",
	/* Version */			'20190702',
	/* Permissions */		['permissions'=>'usage,contentadmin',
                                        'priority'=>49]
);