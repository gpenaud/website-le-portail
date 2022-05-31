<?php
# -- BEGIN LICENSE BLOCK ----------------------------------
#
# This file is part of EHRepeat, an extension of eventHandler
# for dotclear 2
#
# (c)2019 Nurbo Teva for Association Du Grain à Moudre
#
# Licensed under the GPL version 2.0 license.
# A copy of this license is available in LICENSE file or at
# http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
#
# -- END LICENSE BLOCK ------------------------------------

if (!defined('DC_RC_PATH')) {return;}

$this->registerModule(
	/* Name */			"Le Portail",
	/* Description*/	"Extensions à Dotclear pour le site du Portail",
	/* Author */		"Nurbo Teva",
	/* Version */		'2019.02.28',
	/* Properties */
	array(
		'permissions' => 'usage,contentadmin',
		'type' => 'plugin',
		'dc_min' => '2.6'
		)
);

?>