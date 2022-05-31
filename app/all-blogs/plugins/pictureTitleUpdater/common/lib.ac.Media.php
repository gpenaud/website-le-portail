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
 * Permet :
 * <ul>
 *   <li> de rechercher  les formats du  gestionnaire de média de DC2.</li>
 *   <li> de connaitre les différents types de chemin d'accès au repertoires ROOT (public).</li>
 *   <li> de gérer les droits d'accés des images</li>
 * </ul>
 */
class ptuMedia extends dcMedia {

	/**
	 * On regarde si le contenu (géré par dcMedia) est éditable par l'utilisateur.
	 * Soit l'utilisateur est admin_media, soit toutes les médias contenues dans le répertoire
	 * et ses sous répertoires lui appartiennent.
	 * @param	writable	<b>boolean</b>Par défaut vaut false, si à true on regarde si le contenu est modifiable, sinon juste s'il est visible.
	 * @return	<b>fileItem</b> ou <b>null</b> si rien n'est trouvé
	 */
	public function getEditableDir($writable=false)
	{
		$curent_pwd = $this->pwd;
		// on regarde si on a au moins une image que l'utilisateur ne peut modifier
		if (!$this->core->auth->check('media_admin,admin',$this->core->blog->id))
		{
			$media_dir = $this->relpwd ? $this->relpwd : '.';
			
			$strReq =
			'SELECT COUNT(media_id)  as count '.
			'FROM '.$this->table.' '.
			"WHERE media_path = '".$this->path."' ".
			"AND media_dir = '".$this->con->escape($media_dir)."' ";
			
			if ($writable==true) {
				//	on peut écrire que	si on trouve que des images appartenant à l'utilisateur
				// <=>	on NE peut PAS écrire	si on trouve UNE images N'appartenant PAS à l'utilisateur
				if ($this->core->auth->userID()) {
					$strReq .= "AND user_id <> '".$this->con->escape($this->core->auth->userID())."'";
				}
			}
			else {
				//	on peut lire que	si on trouve que des images (appartenant à l'utilisateur OU possédant un droite de lecture)
				// <=>	on NE peut PAS lire	si on trouve UNE image (N'appartenant PAS à l'utilisateur ET NE possédant PAS un droite de lecture)
				$strReq .= 'AND (media_private = 1 ';
				
				if ($this->core->auth->userID()) {
					$strReq .= "AND user_id <> '".$this->con->escape($this->core->auth->userID())."'";
				}
				$strReq .= ') ';
			}
			

			$rs = $this->con->select($strReq);
			
			while ($rs->fetch())
			{
				if ($rs->count > 0) {
					return null;
				}
			}
			// on regarde les sous répertoires
			filemanager::chdir($media_dir);
			filemanager::getDir();
			$p_dir = $this->dir['dirs'];
			
			foreach ( $p_dir as $k => $dir )
			{
				// le premier répertoire correspond au répertoire '..'
				if($k != 0) {
					$this->chdir($dir->relname);
					if($this->getEditableDir($writable)===null) { return null; }
				}
			}
		}
		
		return new fileItem($curent_pwd,$this->root,$this->root_url);
	}
	
	/**
	 * Ordonne les repertoires
	 */
	public function orderDirs(&$dirs) {
		usort($dirs,array($this,'sortHandlerByRelname'));
	}
	
	protected function sortHandlerByRelname($a,$b)
	{
		if ($a->parent && !$b->parent || !$a->parent && $b->parent) {
			return ($a->parent) ? -1 : 1;
		}
		return strcasecmp($a->relname,$b->relname);
	}

	/**
	 * Recherche  les formats du  gestionnaire de média de DC2.
	 */
	public static function getAllDcFormats() {
		$g_core = &$GLOBALS['core'];
		$formats = array();

		// on fait une initialisation pseudo dynamique par rapport
		// au gestionnaire de média de DC2
		if ($g_core->media == null) {
			$g_core->media = new dcMedia($g_core);
		}
		if ($g_core->media->thumb_tp == '%s/.%s_%s.jpg') {
			foreach ($g_core->media->thumb_sizes as $key => $info) {
				$formats[$key]=new ptuFormat($key, $info[2],'\.(.+)_('.$key.')',1);
			}
		}
		return $formats;
	}
	
	/**
	 * Retourne tous les commentaires des fichiers contenus dans le repertoir courant.
	 * (pas dans les sous répertoires)
	 */
	public function getAllCommentaries(){
		$commentary_list = array() ;
		$media_dir = $this->relpwd ? $this->relpwd : '.';
		$media_dir_bd = $this->con->escape($media_dir);
		
		$strReq =
		'SELECT media_file, media_title '.
		'FROM '.$this->table.' '.
		"WHERE media_path = '".$this->path."' ".
		"AND media_dir = '".$media_dir_bd."' ";
		//"AND media_file = '".$this->con->escape($media_dir)."/".$media_name."' ";
		
		if (!$this->core->auth->check('media_admin',$this->core->blog->id))
		{
			$strReq .= 'AND (media_private <> 1 ';
			
			if ($this->core->auth->userID()) {
				$strReq .= "OR user_id = '".$this->con->escape($this->core->auth->userID())."'";
			}
			$strReq .= ') ';
		}
		
		$rs = $this->con->select($strReq);
		
		while ($rs->fetch())
		{
			if( $media_dir_bd != ".") {
				$commentary_list[substr($rs->media_file,strlen($media_dir_bd)+1)] = $rs->media_title;
			}
			else {
				$commentary_list[$rs->media_file] = $rs->media_title;
			}
		}
		return $commentary_list;
		
	}
	
	/****************************************
	 * Récupération du repertoire ROOT               *
	 * **************************************/

	/**
	  * Donne le chemin developpeur d'accés aux données par defaut.
	  * C'est a dire le chemin systéme du repertoire utilisé par dcMédia.
	  */
	private static function getDefaultRootDevPath(){
		$g_core = &$GLOBALS['core'];

		// $g_core->media correspond au média du blog courant
		if ($g_core->media === null) {
			$g_core->media = new dcMedia($g_core);
		}
		return $g_core->media->root;
	}
	
	/**
	 * Donne URL d'accès, defini par défaut et utilisé par les visiteurs du blog, au repertoire Root.
	 * C'est a dire donne URL du repertoire public utilisisé par dcMédia.
	 */
	private static function getDefaultUrlBlogOfRootPath($completed=true){
		$g_core = &$GLOBALS['core'];

		// $g_core->media correspond au média du blog courant
		if ($g_core->media === null) {
			$g_core->media = new dcMedia($g_core);
		}
		if ($completed==true) {
			return $g_core->media->root_url;
		}
		else {
			return preg_replace('|^'.$g_core->blog->host.'(.*)$|','$1',$g_core->media->root_url);
		}
	}

	/**
	 * Donne le chemin developpeur d'accés au repertoire ROOT, c'est-à-dire
	 * contenant les autres repertoires qui contiennent les images.
	 * */
	public static function getRootDevPath(){
		return self::getDefaultRootDevPath();
	}

	/**
	 * Donne URL d'acces, utilisée par les visiteurs du blog, au repertoire Root,
	 * c'est a dire au répertoire contenant tous les repertoires qui contiennent
	 * les images.
	 *
	 * @param with_host <b>boolean</b>Si est à true (valeur par defaut) la méthose retourne l'URL
	 * avec l'host du blog, sinon elle la retourne sans
	 * (à condition que celui-ci soit le même que celui du blog)
	 * */
	public static function getUrlBlogOfRootDir($with_host=true){
		return self::getDefaultUrlBlogOfRootPath($with_host);
	}
}
?>
