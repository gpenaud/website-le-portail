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

if (!defined('DC_CONTEXT_ADMIN')) { return; }

$_menu['Blog']->addItem(

	__('Colorbox'),
	'plugin.php?p=colorbox','index.php?pf=colorbox/icon.png',
	preg_match('/plugin.php\?p=colorbox(&.*)?$/',$_SERVER['REQUEST_URI']),
	$core->auth->check('admin',$core->blog->id));

$core->addBehavior('adminDashboardFavs',array('colorboxBehaviors','dashboardFavs'));
 
class colorboxBehaviors
{
    public static function dashboardFavs($core,$favs)
    {
        $favs['colorbox'] = new ArrayObject(array(
            'colorbox',
            __('Colorbox'),
            'plugin.php?p=colorbox',
            'index.php?pf=colorbox/icon.png',
            'index.php?pf=colorbox/icon-big.png',
            'usage,contentadmin',
            null,
            null));
    }
}
?>