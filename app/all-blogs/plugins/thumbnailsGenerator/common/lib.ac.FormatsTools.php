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
 * Permet la gestion des formats .
 */
class spFormatsTools {
	public static $original_pitures_format_id = "o";

	/******************
	 * Outils simples        *
	 * ****************/
	/**
	 * @return false si $format_id n'est pas compris dans $all_formats ou est self::$original_pitures_format_id
	 */
	public static function isFormat($format_id,$all_formats){
		if ($format_id == null) return false;
		if ($format_id == self::$original_pitures_format_id) return false;
		
		return array_key_exists($format_id,$all_formats);
	}

	/**
	 * Recherche dans $all_formats le format avec l'identifiant $format_id.
	 * @return null si en trouve rien.
	 */
	public static function getOneFormat($format_id,$all_formats){
		if (array_key_exists($format_id,$all_formats)) {
			return $all_formats[$format_id];
		}
		return null;
	}
	
	/**
	 * Construit un tableau permettant d'afficher une combobox.
	 * Le tableau contient le format "Original" (o) ainsi que tous les formats contenus dans $all_formats.
	 * @return un tableau avec en clée le nom du format et en valeur son identifiant.
	 */
	public static function getFormatCombo($all_formats){
		$formats_combo = array();

		$formats_combo["--".__("Original")."--"] = self::$original_pitures_format_id;
		foreach ($all_formats as $format) {
			$formats_combo[$format->getName()] = $format->getId();
		}
		return $formats_combo;
	}
}
?>
