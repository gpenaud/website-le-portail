<?php

/* 
  *  This file is part of dcCKEWidget, a plugin for Dotclear 2.
  *  
  *  Copyright (c) 2019 Bruno Avet
  *  Licensed under the GPL version 2.0 license.
  *  A copy of this license is available in LICENSE file or at
  * http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
 */

if (!defined('DC_CONTEXT_ADMIN')) {
    return;
}

global $core;

$core->addBehavior('ckeditorExtraPlugins', array('dcCKEWidgetExtraPlugins', 'ckeditorExtraPlugins'));

$core->addBehavior('adminPostHeaders',array('dcCKEWidgetAdminBehaviors','jsLoad'));
$core->addBehavior('adminPageHeaders',array('dcCKEWidgetAdminBehaviors','jsLoad'));
$core->addBehavior('adminRelatedHeaders',array('dcCKEWidgetAdminBehaviors','jsLoad'));
$core->addBehavior('adminDashboardHeaders',array('dcCKEWidgetAdminBehaviors','jsLoad'));

class dcCKEWidgetExtraPlugins{
    public static function ckeditorExtraPlugins(ArrayObject $extraPlugins, $context) {
        $extraPlugins[] = array(
          'name' => 'lineutils',
          'button' => 'lineutils',
          'url' => DC_ADMIN_URL.'index.php?pf=dcCKEWidget/cke-addons/lineutils/'
          );
        $extraPlugins[] = array(
          'name' => 'widgetselection',
          'button' => 'widgetselection',
          'url' => DC_ADMIN_URL.'index.php?pf=dcCKEWidget/cke-addons/widgetselection/'
          );
        $extraPlugins[] = array(
          'name' => 'stylesheetparser',
          'button' => 'stylesheetparser',
          'url' => DC_ADMIN_URL.'index.php?pf=dcCKEWidget/cke-addons/stylesheetparser/'
          );
        $extraPlugins[] = array(
          'name' => 'widget',
          'button' => 'widget',
          'url' => DC_ADMIN_URL.'index.php?pf=dcCKEWidget/cke-addons/widget/'
          );
    }
}

class dcCKEWidgetAdminBehaviors{
    public static function jsLoad(){
          $jsurl = 'plugin.php?p=dcCKEWidget&ckewidgetjs=1';
          return dcPage::jsLoad($jsurl);
    }
}