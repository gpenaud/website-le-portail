<?php

/* 
  *  This file is part of dcCKEWidget, a plugin for Dotclear 2.
  *  
  *  Copyright (c) 2019 Bruno Avet
  *  Licensed under the GPL version 2.0 license.
  *  A copy of this license is available in LICENSE file or at
  * http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
 */

if (!defined('DC_CONTEXT_ADMIN')) { exit; }

$this_version = $core->plugins->moduleInfo('dcCKEWidget','version');
$installed_version = $core->getVersion('dcCKEWidget');
if (version_compare($installed_version,$this_version,'>=')) {
	return;
}

$core->setVersion('dcCKEWidget',$this_version);

return true;
