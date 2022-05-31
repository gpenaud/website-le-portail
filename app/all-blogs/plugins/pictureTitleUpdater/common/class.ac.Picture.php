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
 * Classe regroupant les différents formats d'une même image.
 */
class ptuPicture {
	private $id;				///< <b>string</b> l'identifiant
	private $label;			///< <b>string</b> le label
	private $dc_label;			///< <b>string</b> le label du gestionnaire
	private $is_selected;		///< <b>boolean</b> à true si l'image à été choisie pour être vu dans le blog
	private $thumbnails=array();	///< <b>array{Thumbnail}</b> liste des miniatures + originale

	public function __construct($id)
	{
		$this->id = $id;
		$this->is_selected = true;
	}

	/**
	 * Rajout un format à l'image.
	 * @param thumbnail		<b>ptuFormat</b> le format à rajouter
	 * @param commentaries	<b>array</b>
	 */
	public function addThumbnail($thumbnail,$commentaries)
	{
		if ($thumbnail->getFormat() === ptuFormatsTools::$original_pitures_format_id)
		{
			$this->thumbnails[ptuFormatsTools::$original_pitures_format_id]=$thumbnail;
			
			if (isset($commentaries[$thumbnail->getFileName()])) {
				$this->label = $commentaries[$thumbnail->getFileName()];
				$this->dc_label = $commentaries[$thumbnail->getFileName()];
			}
			else {
				$this->label = $thumbnail->getFileName();
			}
		}
		else
		{
			$this->thumbnails[$thumbnail->getFormat()->getId()] = $thumbnail;
			if( $this->label === null ) {
				$this->label = $this->id.".".ptuPicturesTools::getRealExtension($thumbnail->getFileName());
			}
		}
	}

	public function getId()
	{
		return $this->id;
	}

	public function getLabel()
	{
		return $this->label;
	}

	public function getDcLabel()
	{
		return $this->dc_label;
	}

	public function getIsSelected()
	{
		return $this->is_selected;
	}

	public function setIsSelected($is_selected)
	{
		$this->is_selected = $is_selected;
	}

	public function getThumbnailByFormatId($format_id)
	{
		if (isset($this->thumbnails[$format_id]))
		{
			return $this->thumbnails[$format_id];
		}
		return null;
	}

	public function formatExist($format)
	{
		if ($format === ptuFormatsTools::$original_pitures_format_id)
		{
			return isset($this->thumbnails[$format]);
		}
		elseif (is_object($format))
		{
			return isset($this->thumbnails[$format->getId()]);
		}
		else
		{
			return isset($this->thumbnails[$format]);
		}
	}
}

?>
