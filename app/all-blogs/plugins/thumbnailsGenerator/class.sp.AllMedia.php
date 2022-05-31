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

/* TODO : prendre en compte les autres foramts et utiliser F+ */
class spAllMedia extends dcMedia {
	//private $thumb_sizes_all;
	
	/**
	 * @param	core			<b>cdCore</b>
	 * @param	option_format 		mot clé qui donne les formats considéré par le gestionnaire de média
	 */
	public function __construct(&$core,$option_format)
	{
		parent::__construct($core);
		
		if ($option_format=="") $option_format = "dc";
		if ($option_format=="bsq") {
			$this->thumb_sizes = array(
				'bsq' => array(90,'crop','square')
			);
		}
		/*
		$this->thumb_sizes_all['ratio'] = array(); 
		$this->thumb_sizes_all['crop'] = array(); 
		foreach($this->thumb_sizes as $key => $info) {
			$this->thumb_sizes_all[$info[1]][$key] = $info;
		} /* */	
	}
	
	public function doSomething($doSomething,$rep_name,$all_formats) {
		$g_core = &$GLOBALS['core'];
		$doSomething = urldecode($doSomething);
		
		if ($doSomething==__('Create') || $doSomething==__('Update')) {
			$liste=$this->createPictures($rep_name,$all_formats);
		}
		else if ($doSomething==__('Delete')) {
			$liste=$this->deletePictures($rep_name,$all_formats);
		}
		else {
			return ;
		}
		
		if (count($liste) > 0) {
			if ($option_format==bsq) {
				if ($doSomething==__('Create') || $doSomething==__('Update')) {
					$msg = __('A thumbnail has been successfully created for the/each following picture:')."<br/>";
				}
				else {
					$msg = __('Thumbnail(s) successfully deleted:')."<br/>";
				}
			}
			else {
				if ($doSomething==__('Create') || $doSomething==__('Update')) {
					$msg = __('The thumbnails have been successfully created for the/each following picture:')."<br/>";
				}
				else {
					$msg = __('Thumbnail(s) successfully deleted:')."<br/>";
				}
			}
			foreach ($liste as $pictures) {
				$msg .= $pictures.'<br/>';
			}
		}
		else {
			if ($doSomething==__('Create') || $doSomething==__('Update')) {
				$msg = __('No thumbnail created')."<br/>";
			}
			else {
				$msg = __('No thumbnail deleted')."<br/>";
			}
		}
		return $msg;
	}

	public function createPictures($rep,$all_formats) {
		$liste = spPicturesTools::getPicturesOfDirName($rep,spFormatsTools::$original_pitures_format_id,$all_formats);
		//$thumb_sizes_memory = $this->thumb_sizes;
		//foreach ($this->thumb_sizes_all as $thumb_sizes_xx) {
			//$this->thumb_sizes = $thumb_sizes_xx;
			$created = array();
			try {
				foreach ($liste as $pictures) {
					if ($pictures[0]!="." && $this->createFile($rep.'/'.$pictures)) {
						$created[] = $rep.'/'.$pictures;
					}
					else if ($pictures[0]!=".") {
						$GLOBALS['core']->error->add(sprintf(__("%s can not be created."),$thumbnail_file_name));
					}
				}
			}
			catch (Exception $e) {
				$GLOBALS['core']->error->add(sprintf(__("%s can not be created."),$thumbnail_file_name));
			}
		//}
		//$this->thumb_sizes=$thumb_sizes_memory;
		$GLOBALS['core']->blog->triggerBlog();
		return $created;
	}
	
	public function deletePictures($rep,$all_formats) {
		$delete = array();
		foreach ($this->thumb_sizes as $type => $tmp) {
			$this->_deletePictures($rep,$all_formats,$type,$delete) ;
		}
		$GLOBALS['core']->blog->triggerBlog();
		return $delete;
	}
	
	private function _deletePictures($rep,$all_formats,$format_id,&$delete) {
		$liste = spPicturesTools::getPicturesOfDirName($rep,$format_id,$all_formats);

		$filemanager = new filemanager($this->core->blog->public_path."/".$rep);
		foreach ($liste as $thumbnail_file_name) {
			try {
				$filemanager->removefile($thumbnail_file_name);
				$delete[] = $rep.'/'.$thumbnail_file_name;
			}
			catch (Exception $e) {
				$GLOBALS['core']->error->add(sprintf(__("%s can not be deleted."),$thumbnail_file_name));
			}
		}
	}
}

?>
