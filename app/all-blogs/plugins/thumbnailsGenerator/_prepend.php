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
$GLOBALS['__autoload']['spPicturesFormat']    = dirname(__FILE__).'/common/interface/lib.ac.PicturesFormat.php';
$GLOBALS['__autoload']['spMedia']    = dirname(__FILE__).'/common/lib.ac.Media.php';
$GLOBALS['__autoload']['spFormatsTools']    = dirname(__FILE__).'/common/lib.ac.FormatsTools.php';
$GLOBALS['__autoload']['spPicturesTools'] = dirname(__FILE__).'/common/lib.ac.PicturesTools.php';
$GLOBALS['__autoload']['spPicturesDir'] = dirname(__FILE__).'/common/class.ac.PicturesDir.php';
$GLOBALS['__autoload']['spPicture'] = dirname(__FILE__).'/common/class.ac.Picture.php';
$GLOBALS['__autoload']['spThumbnail'] = dirname(__FILE__).'/common/class.ac.Thumbnail.php';
$GLOBALS['__autoload']['spFormat'] = dirname(__FILE__).'/common/class.ac.Format.php';

$GLOBALS['__autoload']['spAllMedia'] = dirname(__FILE__).'/class.sp.AllMedia.php';
?>
