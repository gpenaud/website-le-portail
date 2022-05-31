<?php
# ***** BEGIN LICENSE BLOCK *****
# This file is part of DotClear Thumbnails Generator plugin.
# Copyright (c) 2010 Anne-Cécile Calvot and contributors. 
# All rights reserved.
#
# Licensed under the GPL version 2.0 license.
# See LICENSE file or
# http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
#
# ***** END LICENSE BLOCK *****

$dir_name = 'thumbnailsGenerator';

$_menu['Blog']->addItem(
	__('Thumbnails Generator'),
	'plugin.php?p='.$dir_name,
	'index.php?pf='.$dir_name.'/icon.png',
	preg_match('/plugin.php\?p='.$dir_name.'(&.*)?$/',$_SERVER['REQUEST_URI']),
	$core->auth->check('media,media_admin,admin',$core->blog->id)
);

?>
