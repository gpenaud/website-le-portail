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

if (!defined('DC_CONTEXT_ADMIN')) { exit; }

$version = $core->plugins->moduleInfo('contextBg','version');



if (version_compare($core->getVersion('contextBg'),$version,'>=')) {
	return;
}

$core->setVersion('contextBg',$version);

return true;
?>