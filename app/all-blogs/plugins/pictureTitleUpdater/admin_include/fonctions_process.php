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

function load_ptu_pictures() {
	global $g_core, $pictures ;
	
	if ($g_core->auth->check('media,media_admin,admin',$g_core->blog->id)) {
		if (!empty($_POST['rep_name'])) {
			$all_formats = ptuMedia::getAllDcFormats();
			$all_formats['bsq']=new ptuFormat('bsq', 'square','\.(.+)_(bsq)',1);
			# load gallery and pictures
			$pictures = ptuPicturesDir::getPicturesTree($_POST['rep_name'],$all_formats)->getAllPictures();
			if ( ($g_core->plugins->moduleExists("picturesShow") === true)
				&& psConf::getIfActif() )
			{
				$pictures_sorter = new psAdminPicturesSorter($_POST['rep_name']);
				$pictures = $pictures_sorter->applyOrder($pictures);
			}
		}
		else {
			$pictures = null;
		}
	}
}

function save_labels() {
	global $g_core;
	
	if (!empty($_POST['savelabel'])) {
		$rep_name = $_POST['rep_name'];
		$files = $_POST['files'];
		
		try {
			foreach($files as $infos) {
				$ptuLabelMedia = new ptuLabelMedia($g_core);
				$ptuLabelMedia->setLabel($rep_name,$infos['file_name'],$infos['label']);
			}
		} catch (Exception $e) {
			$g_core->error->add($e->getMessage());
		}
	}
}