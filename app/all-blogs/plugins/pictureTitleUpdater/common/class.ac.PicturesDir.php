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
 * Classe regroupant une image 'originale' et ses mini images.
 * L'image originale n'est pas forcément présente.
 *
 * Les images regroupées appartiennent à un même repertoire lui même se situant dans le répertoire ROOT (public).
 */
class ptuPicturesDir {
	private $dir_name;			///< <b>string</b> le nom du répertoire (chemin relatif)
	private $commentaries;		///< <b>string</b> les labels de chaque image du gestionnaire (directement à la racine)
	private $pictures=array();	///< <b>array{ptuPicture}</b>

	private function __construct($dir_name)
	{
		$g_core = &$GLOBALS['core'];
		$this->dir_name = $dir_name;
		
		$my_media = new ptuMedia($g_core);
		$my_media->chdir($dir_name);
		$this->commentaries = $my_media->getAllCommentaries();
	}

	/**************************
	 * Méthodes pour construir le PicturesDir
	 **************************/
	/**
	 * Créé et ajout le format $format à une image.
	 */
	public function addThumbnail($picture_id,$picture_file_name,$format) {
		$thumbnail = new ptuThumbnail($picture_file_name,$format);
		$this->getPicture($picture_id)->addThumbnail($thumbnail,$this->commentaries);
	}
	
	/**
	 * Retourne l'image dont l'identaifiant est $picture_id.
	 * S'il elle n'existe pas pas la créée.
	 * @return {ptuPicture}
	 */
	private function getPicture($picture_id) {

		if (!isset($this->pictures[$picture_id]))
		{
			$this->pictures[$picture_id] = new ptuPicture($picture_id);
		}

		return $this->pictures[$picture_id];
	}

	/**
	 * Méthode factory, permettant d'obtenir un objet {ptuPicturesDir} représentant les images trouvées
	 * dans le répertoire nomé $rep.
	 *
	 * @param	rep		nom du répertoire chemin relatif
	 * @param	all_formats	liste des formats gérés (le format original exclus)
	 * @return PicturesDir qui contient toutes les images trouvées dans le répertoire donné.
	 * Les sous-répertoires n'étant visités que si un format le nécéssite.
	 * @throws Exception si le répertoire n'existe pas. 
	 **/
	public static function getPicturesTree($rep,$all_formats)
	{
		$pictures_dir = new ptuPicturesDir($rep);

		$complet_dir_name = ptuMedia::getRootDevPath()."/".$rep;
		self::_getPicturesTree($complet_dir_name,'',$pictures_dir,$all_formats);

		return $pictures_dir;
	}

	private static function _getPicturesTree($root_dir,$under_dir,$pictures_dir,$all_formats)
	{
		$dir='';
		$file_under_dir = ($under_dir == '')?'':$under_dir.'/';
		$curent_dir = ($under_dir == '')?$root_dir:$root_dir.'/'.$under_dir;

		if ($dir = @opendir($curent_dir)) {
			try {
				while (false !==  ($file = readdir($dir))) {
					if ($file == '.' || $file === '..' ) continue ;

					if (is_dir($curent_dir.'/'.$file))
					{
						self::_getPicturesTree($root_dir,$file_under_dir.$file,$pictures_dir,$all_formats);
					}
					else if (is_file($curent_dir.'/'.$file) && ptuPicturesTools::isPictures($file))
					{
						$is_format = false;
						$extension = ptuPicturesTools::getRealExtension($file);
						foreach ($all_formats as $format)
						{
							if (preg_match('#^'.$format->getUserExpr().'\.'.$extension.'$#',$file_under_dir.$file,$m))
							{
								$id = $m[$format->getNumPlaceOfOriginName()];
								$pictures_dir->addThumbnail($id,$file_under_dir.$file,$format);
								$is_format=true;
								break;
							}
						}
						if ($under_dir == '' && $is_format === false
							&& preg_match('#^(.+)\.'.$extension.'$#',$file,$m))
						{
							$pictures_dir->addThumbnail($m[1],$file,ptuFormatsTools::$original_pitures_format_id);
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
			throw new Exception("Unable to open the directory $curent_dir.");
		}
	}

	/**************************
	 * Méthodes pour obtenir le contenu de PicturesDir
	 **************************/
	/**
	 * Retourne toutes les images trouvées ainsi que leur format.
	 * @return un tableau de {ptuPicture}
	 */
	public function getAllPictures() {
		return $this->pictures;
	}
	
	/**
	 * Retourne toutes les images trouvées ainsi que leur format.
	 * A chaque appel l'ordre est modifié.
	 * @return un tableau de {ptuPicture}
	 */
	public function getAllPicturesShuffle() {
		$shuffle_pictures = array();
		$tmp_shuffle_pictures = $this->pictures;
		// Les clées disparaissent
		shuffle($tmp_shuffle_pictures);
		// on les remet
		foreach($tmp_shuffle_pictures as &$pictures) {
			$shuffle_pictures[$pictures->getId()] = $pictures;
		}
		return $shuffle_pictures;
	}
	
	/**
	 * Regarde le nombre d'image possédant le format passé en paramêtre.
	 * La validité du format passé en paramêtre n'est pas vérifiée. 
	 */
	public function getNbPicturesByFormat($format) {
		$nb_pictures=0;
		foreach($this->pictures as $picture)  {
			if($picture->formatExist($format)) {
				$nb_pictures++;
			}
		}
		return $nb_pictures;
	}

	/**
	 * @return le nom du répertoire (chemin relatif) où sont contenues les images.
	 */
	public function getDirName() {
		return $this->dir_name;
	}
}

?>
