<?php
# ***** BEGIN LICENSE BLOCK *****
# This file is part of DotClear Picture Title Update plugin.
# Copyright (c) 2010 Anne-Cécile Calvot and contributors. 
# All rights reserved.
#
# Licensed under the GPL version 2.0 license.
# See LICENSE file or
# http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
#
# ***** END LICENSE BLOCK *****

$GLOBALS['__autoload']['ptuPicturesFormat']    = dirname(__FILE__).'/common/interface/lib.ac.PicturesFormat.php';
$GLOBALS['__autoload']['ptuMedia']    = dirname(__FILE__).'/common/lib.ac.Media.php';
$GLOBALS['__autoload']['ptuFormatsTools']    = dirname(__FILE__).'/common/lib.ac.FormatsTools.php';
$GLOBALS['__autoload']['ptuPicturesTools'] = dirname(__FILE__).'/common/lib.ac.PicturesTools.php';
$GLOBALS['__autoload']['ptuPicturesDir'] = dirname(__FILE__).'/common/class.ac.PicturesDir.php';
$GLOBALS['__autoload']['ptuPicture'] = dirname(__FILE__).'/common/class.ac.Picture.php';
$GLOBALS['__autoload']['ptuThumbnail'] = dirname(__FILE__).'/common/class.ac.Thumbnail.php';
$GLOBALS['__autoload']['ptuFormat'] = dirname(__FILE__).'/common/class.ac.Format.php';

$GLOBALS['__autoload']['ptuLabelMedia'] = dirname(__FILE__).'/class.ptu.LabelMedia.php';
?>
