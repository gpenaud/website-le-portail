<?php
# ***** BEGIN LICENSE BLOCK *****
# This file is part of a commun lib for some DotClear plugin.
# Copyright (c) 2010 Anne-Cécile Calvot. All rights reserved.
#
# Licensed under the GPL version 2.0 license.
# See LICENSE file or
# http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
#
# ***** END LICENSE BLOCK *****
/**
 * Une "mini image" ou l'image originale.
 */
class ptuThumbnail {

	private $format;		///< <b>Format</b> ou <b>string</b>
	private $file_name;		///< <b>string</b>

	public function __construct($file_name,$format)
	{
		$this->file_name = $file_name;
		$this->format = $format;
	}

	public function getFormat() {
		return $this->format;
	}

	public function getFileName() {
		return $this->file_name;
	}

}

?>
