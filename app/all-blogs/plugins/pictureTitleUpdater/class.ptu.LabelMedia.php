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

class ptuLabelMedia extends dcMedia {

	public function __construct(&$core)
	{
		parent::__construct($core);
	}

	public function setLabel($rep_name,$file_name,$media_title)
	{
		if (!$this->core->auth->check('media,media_admin',$this->core->blog->id)) {
			throw new Exception(__('Permission denied.'));
		}
		$file = $this->getFileByName($rep_name,$file_name);
		$this->updateLabel($file,$media_title);
	}
	
	private function getFileByName($media_dir,$name)
	{
		$media_dir = ($media_dir=="") ? '.' : $media_dir;
		
		$strReq =
		'SELECT media_id, media_path, media_title, '.
		'media_file, media_meta, media_dt, media_creadt, '.
		'media_upddt, media_private, user_id '.
		'FROM '.$this->table.' '.
		"WHERE media_path = '".$this->path."' ".
		"AND media_dir = '".$media_dir."' ".
		"AND media_file = '".$media_dir."/".$name."' ";
		
		if (!$this->core->auth->check('media_admin',$this->core->blog->id))
		{
			$strReq .= 'AND (media_private <> 1 ';
			
			if ($this->core->auth->userID()) {
				$strReq .= "OR user_id = '".$this->con->escape($this->core->auth->userID())."'";
			}
			$strReq .= ') ';
		}
		
		$rs = $this->con->select($strReq);
		return $this->fileRecord($rs);
	}
	
	public function updateLabel($file,$media_title)
	{
		if (!$this->core->auth->check('media,media_admin',$this->core->blog->id)) {
			throw new Exception(__('Permission denied.'));
		}
		
		$id = (integer) $file->media_id;
		
		if (!$id) {
			throw new Exception('No file ID');
		}
		
		if (!$this->core->auth->check('media_admin',$this->core->blog->id)
		&& $this->core->auth->userID() != $file->media_user) {
			throw new Exception(__('You are not the file owner.'));
		}
		
		$cur = $this->con->openCursor($this->table);
		
		$cur->media_title = (string) $media_title;
		$cur->media_upddt = array('NOW()');
		
		$cur->update('WHERE media_id = '.$id);
	}
}
?>
