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
 * Gestion générale d'images.
 * */
class spPicturesTools {

	/******************
	 * Outils simples *
	 * ****************/

	public static function isPictures($file_name) {
		$mine_type = files::getMimeType($file_name);
		if (($pos = strpos($mine_type, 'image/') )  === 0 )
		{
			return true;
		}
		else
		{
			return false;
		}
	}
	
	public static function getRealExtension($f)
	{
		$f = explode('.',basename($f));

		if (count($f) <= 1)
		{
			return '';
		}

		return $f[count($f)-1];
	}
	
	/*********************************
	 * Gestion complexe des pictures       *
	 * *******************************/

	/**
	 * Donne, sous la forme d'un tableau, la liste des images d'un format donné pour un repertoire.
	 * avec en clée l'dentifiant de l'image originale.
	 *
	 * @param	dir_name 	<b>String</b> nom du répertoire (chemin relatif)
	 * @param	format_id	<b>String</b> identifiant du format, si vide ou inexistant on retourne une liste vide.
	 * @param	all_formats	<b>Array</b> liste des formats gérés (le format original exclus)
	 * @throws Exception si le repertoire n'existe pas.
	 **/
	public static function getPicturesOfDirName($dir_name,$format_id,$all_formats)
	{

		if ($format_id != spFormatsTools::$original_pitures_format_id
			&& !spFormatsTools::isFormat($format_id,$all_formats) )
		{
			return array();
		}

		$root_dir_name = spMedia::getRootDevPath()."/".$dir_name;
		return self::_searchPicturesOfDirName($root_dir_name,'.',$format_id,$all_formats);
	}

	/**
	 * @param	root_dir	<b>String</b> nom du répertoire de départ (ne change pas)
	 * @param	under_dir	<b>String</b> nom du sous répertoire que l'on est entrain de parcourir
	 * @param	format_id	<b>String</b> identifiant du format recherché
	 * @param	all_formats	<b>Array</b>liste des formats gérés (le format original exclus)
	 * @param	if_option	<b>boolean</b> si à true on se contente de regarder si le repertoire possè au moins
	 *				une image du format précisé.
	 **/
	private static function _searchPicturesOfDirName($root_dir,$under_dir,$format_id,$all_formats,$if_option=false)
	{
		$dir=null;
		$pictures_list = array();

		if ($format_id == spFormatsTools::$original_pitures_format_id ) {
			if (false !==  ($dir = @opendir($root_dir))) {
				try {
					while(false !==  ($file = readdir($dir))) {
						if ($file == '.' || $file == '..' ) continue;
						if (is_file($root_dir.'/'.$file) && spPicturesTools::isPictures($file)) {
							$is_picture_original=true;
							$extension = spPicturesTools::getRealExtension($file);
							foreach ($all_formats as $format) {
								if (preg_match('#^'.$format->getUserExpr().'\.'.$extension.'$#',$file,$m))
								{
									$is_picture_original=false;
									break;
								}
							}

							if  ($is_picture_original === true
								&& preg_match('#^(.*)\.'.$extension.'$#',$file,$m))
							{
								if ($if_option == true) return true;
								$pictures_list[$m[1]]=$file;
							}
						}
					}
					closedir($dir);
				}
				catch (Exception $e) {
					@closedir($dir);
					throw $e;
				}
			}
			else {
				throw new Exception("Unable to open the directory $root_dir.");
			}
		}
		else {
			$format = $all_formats[$format_id]; // le format existe l'appel à FormatsTools::getOneFormat ne sert pas
			if ($dir = @opendir($root_dir.'/'.$under_dir)) {
				try {
					while($file = readdir($dir)) {
						if ($file == '.' || $file == '..' ) continue;
						
						if (is_dir($root_dir.'/'.$under_dir.'/'.$file))
						{
							$resultat = self::_searchPicturesOfDirName($root_dir, $under_dir.'/'.$file, $format_id,$all_formats,$if_option);
							/*
							 * On enlève "." pour éviter que "./toto/.image_s.jpg" soit validé par l'expression "^\.(.+)_(s)\.jpg"
							 */
							if ($under_dir == ".") {
								$resultat = self::_searchPicturesOfDirName($root_dir, $file, $format_id,$all_formats,$if_option);
							}
							else {
								$resultat = self::_searchPicturesOfDirName($root_dir, $under_dir.'/'.$file, $format_id,$all_formats,$if_option);
							}
							if ($if_option == true && $resultat === true)
							{
								return true;
							}
							elseif ($if_option == false)
							{
								$pictures_list += $resultat;
							}
						}
						elseif (is_file($root_dir.'/'.$under_dir.'/'.$file) && spPicturesTools::isPictures($file))
						{
							/*
							 * On enlève "." pour éviter que "./toto/image_s.jpg" soit validé par l'expression "^\.(.+)_(s)\.jpg"
							 */
							if ($under_dir == ".") {
								$file_name = $file;
							}
							else {
								$file_name = $under_dir."/".$file;
							}
							if (preg_match('#^'.spFormatsTools::getOneFormat($format_id,$all_formats)->getUserExpr().'\.'.spPicturesTools::getRealExtension($file).'$#',$file_name,$m))
							{
								if ($if_option == true ) return true;
								$pictures_list[$m[$format->getNumPlaceOfOriginName()]]=$file;
							}
						}
					}
					closedir($dir);
				}
				catch (Exception $e) {
					@closedir($dir);
					throw $e;
				}
			}
			else {
				if ($if_option == true) return false;

				throw new Exception("Unable to open the directory $root_dir/$under_dir.");
			}
		}

		if ($if_option == true) return false;
		return $pictures_list;
	}

	/**
	 * Vérifie qu'il existe un repertoire et qu'il posséde au moins une image avec le format $pictures_format_id.
	 * (Ne retourne pas d'exeption si le répertoire n'existe pas, juste si on rencontre un autre type de problème)
	 *
	 * @param	rep	 		<b>String</b> nom du répertoire (chemin relatif)
	 * @param	pictures_format_id	<b>String</b> identifiant du format, si vide ou inexistant on retourne false.
	 * @param	all_formats		<b>Array</b>liste des formats gérés (le format original exclus)
	 */
	public static function isDirWithPictures($rep,$pictures_format_id,$all_formats)
	{

		if ($rep == null)
		{
			return false;
		}
		if  ($pictures_format_id != spFormatsTools::$original_pitures_format_id
			&& !spFormatsTools::isFormat($pictures_format_id,$all_formats))
		{
			return false;
		}

		$root_dir_name = spMedia::getRootDevPath()."/".$rep;

		return self::_searchPicturesOfDirName($root_dir_name,'.',$pictures_format_id,$all_formats,true);
	}

	/**
	 * Liste l'ensemble des repertoires contenus dans le répertoire 'public'.
	 * @return un tableau de <b>fileItem</b>
	 */
	private static function getAllDirs() {
		$g_core = &$GLOBALS['core'];

		if ($g_core->media  === null) {
			$g_core->media = new dcMedia($g_core);
		}

		return $g_core->media->getRootDirs();
	}

	/**
	 * Donne un tableau indexé permettant de faire une combobox qui liste
	 * l'ensemble des repertoires (pour le blog courant) qui  contiennent des images d'un type donné.
	 * @param	format_id	<b>String</b> identifiant du format, si vide ou inexistant on retourne false.
	 * @param	get_none	<b>boolean</b> par defaut à true. Si à true, on rajoute le choix "None" au tableau retourné.
	 * @param	all_formats	<b>Array</b> liste des formats gérés (le format original exclus)
	 * */
	public static function getDirNameComboBox($format_id,$get_none,$all_formats)
	{
		$dirs_combo = array();
		if ($get_none) {
			$dirs_combo["--".__("None")."--"] = '';
		}

		foreach (self::getAllDirs() as $v) {
			if ($v->w) {
				if ($v->relname != null && $v->relname != ''
					&& self::isDirWithPictures($v->relname,$format_id,$all_formats))
				{
					$dirs_combo[$v->relname] = $v->relname;
				}
			}
		}
		return $dirs_combo;
	}
}
?>
