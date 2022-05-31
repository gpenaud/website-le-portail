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
?>
		<table>
		
		<?php		
		foreach ($pictures as $picture) {
		?>
			<tr><td>
			<?php
			if ($picture->formatExist('sq')) {
			?>
				<img src="<?php echo ptuMedia::getUrlBlogOfRootDir(true)."/".$rep_name."/".$picture->getThumbnailByFormatId('sq')->getFileName(); ?>" alt="<?php  $picture->getThumbnailByFormatId('sq')->getFileName();?>"/>
			<?php
			}
			else {
			?>
				<img src="index.php?pf=pictureTitleUpdater/img/image.png" alt="<?php  echo __("No picture");?>"/>
			<?php
			}?>
			</td><td>
			<?php
			if ($picture->getDcLabel()!==null) {
				echo form::hidden(array('files['.$picture->getId().'][file_name]'),$picture->getThumbnailByFormatId('o')->getFileName());
				echo form::field(array('files['.$picture->getId().'][label]'),35,255,html::escapeHTML($picture->getDcLabel()));
			}
			else {
				echo __('Does not exist in DC Media');
			}
			?>
			</td></tr>
		<?php
		}
		?>
		</table>