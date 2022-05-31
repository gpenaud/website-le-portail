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

function add_html_head_presentation() {
	global $g_core;
	
	# on fait marcher le drag and drop et on fait une belle présentation
	if (psConf::getIfActif() && $g_core->auth->check('media,media_admin,admin',$g_core->blog->id)) {
?>
	<style type="text/css">
	<!--
		#boxes {
			font-family: Arial, sans-serif;
			list-style-type: none;
			margin: 0px;
			padding: 0px;
			width: 100%;
		}
		#boxes li {
			position: relative;
			float: left;
			margin: 2px 2px 0px 0px;
			width: 265px;
			height: 80px;
			border: 1px solid #000;
			text-align: center;
			padding-top: 5px;
			background-color: #eeeeff;
		}
		#boxes li.ghost {
			border: 1px solid #000;
			background-color: #ffffff;
		}
	//-->
	</style>
<?php
	}
}
function add_html_gallery() {
	global $g_core, $pictures;
	$rep_name = (!empty($_POST['rep_name']))? $_POST['rep_name']:'';
	
	echo "<p>".form::hidden(array('savelabel'),1)."</p>";
?>
		<ul id="boxes">
		
		<?php		
		foreach ($pictures as $picture) {
		?>
			<li class="box <?php echo ($picture->getIsSelected()) ? "on" : "off";?>" id="p_<?php echo $picture->getId(); ?>">
			<?php
			if ($picture->formatExist('sq')) {
			?>
				<img src="<?php echo ptuMedia::getUrlBlogOfRootDir(false)."/".$rep_name."/".$picture->getThumbnailByFormatId('sq')->getFileName(); ?>" alt="<?php  $picture->getThumbnailByFormatId('sq')->getFileName();?>"/>
			<?php
			}
			else {
			?>
				<img src="index.php?pf=pictureTitleUpdater/img/image.png" alt="<?php  echo __("No picture");?>"/>
			<?php
			}
			?>
			<br/>
			<?php
			if ($picture->getDcLabel()!==null) {
				echo form::hidden(array('files['.$picture->getId().'][file_name]'),$picture->getThumbnailByFormatId('o')->getFileName());
				echo form::field(array('files['.$picture->getId().'][label]'),35,255,html::escapeHTML($picture->getDcLabel()));
			}
			else {
				echo __('Does not exist in DC Media');
			}
			echo form::hidden(array('display['.$picture->getId().']'), ($picture->getIsSelected()) ? "+" : "-", '', '', true);
		?>
			</li>
		<?php
		}
		?>
		</ul>
<?php
}
?>