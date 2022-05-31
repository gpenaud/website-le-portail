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
if (!defined('DC_CONTEXT_ADMIN')) { exit; }
$g_core = &$GLOBALS['core'];
if (!$g_core->auth->check('media,media_admin,admin',$g_core->blog->id)) { exit; }

include(dirname(__FILE__).'/admin_include/fonctions_process.php');

save_labels();

$rep_name = (isset($_POST['rep_name']))?$_POST['rep_name']:"";
## gestion des miniatures pour la galerie
if (($g_core->plugins->moduleExists("thumbnailsGenerator") === true) && $g_core->auth->check('media,media_admin,admin',$g_core->blog->id)) {
	if (!empty($_POST['dosomething'])) {
		$doSomething = $_POST['dosomething'];
		$rep_name = $_POST['rep_name'];
		$spAllMedia = new spAllMedia($g_core,'dc');
		
		$all_sp_formats = ptuMedia::getAllDcFormats();
		$all_sp_formats['bsq']=new ptuFormat('bsq', 'square','\.(.+)_(bsq)',1);
		$msg .= $spAllMedia->doSomething($doSomething,$rep_name,$all_sp_formats);
	}
}
load_ptu_pictures();
?>
<html>
<head>
	<title><?php echo __('Picture Title Updater'); ?></title>
	<script type="text/javascript" src="index.php?pf=pictureTitleUpdater/js/_picture_directory.js"></script>
	<script type="text/javascript" src="js/jquery/jquery.pageTabs.js"></script>
	<script type="text/javascript">
		//<![CDATA[
		$(function() {
			$.pageTabs();
		});

		//]]>
	</script>
</head>

<body>
<h2><?php echo html::escapeHTML($g_core->blog->name); ?> &gt; <?php echo __('Picture Title Updater'); ?></h2>
<?php if (!empty($msg)) echo '<p class="message">'.$msg.'</p>';?>
<div class="multi-part" id="image" title="<?php echo __('Picture Title Updater'); ?>">
  <!--h3><?php echo __("Picture Title Updater"); ?></h3-->
	
	<form action="plugin.php" method="post" id="form-directory">
		<div class="two-cols" style="clear:right;">
		<p class="col">
		
<?php
		$all_formats = ptuMedia::getAllDcFormats();
		$all_formats['bsq']=new ptuFormat('bsq', 'square','\.(.+)_(bsq)',1);
		
		echo form::hidden('choose_dir','1');
		echo form::hidden(array('p','directory_p'),'pictureTitleUpdater');
		echo form::combo(array('rep_name','rep_name_choose'),ptuPicturesTools::getDirNameComboBox(ptuFormatsTools::$original_pitures_format_id,true,$all_formats),$rep_name);
	
		echo $core->formNonce();
?>
	<br/>
		</p>
		<p class="col right">&nbsp;</p>
		</div>
	</form>

<?php		
	if (!isset($rep_name) || $rep_name == null)
	{
		echo '<br style="clear: both;"/><p>'.__('Choose a directory').'</p>';
	}
	else if ($pictures == null || count($pictures) == 0	)
	{
		echo '<br style="clear: both;"/><p>'.__('No picture').'</p>';
	}
	else
	{
?>
	<fieldset style="clear: both;">
	<legend><?php echo __('Pictures'); ?></legend>
	<form action="plugin.php" method="post" id="form-pictures">
		<div><?php
		echo form::hidden('p','pictureTitleUpdater');
		echo form::hidden('type','blog'); 
		echo form::hidden('rep_name',$rep_name);
		?></div>
		<?php include(dirname(__FILE__).'/admin_include/pictures_list_tab.php'); ?>
		
		<div style="clear:left; padding: 0.5em 0 0 0" class="two-cols">
		<p class="col">
		<input type="submit" name="savelabel" value="<?php echo __('Save');?>"/>
		</p>
		<p class="col right">
		</p>
		<?php echo $core->formNonce(); ?>
		</div>
	</form>
	</fieldset>		
<?php
		if ($g_core->plugins->moduleExists("thumbnailsGenerator") === true) {
?>
	<fieldset>
	<legend><?php echo __('Thumbnails'); ?></legend>
	<form action="plugin.php" method="post">
		<div><?php
		echo form::hidden(array('p','p_thumbnail'),'pictureTitleUpdater');
		echo form::hidden(array('rep_name','rep_name_thumbnail'),$rep_name);
		?>
		<p class="col">
			<input type="submit" name="dosomething" value="<?php echo __('Update');?>"/>
			<input type="submit" name="dosomething" value="<?php echo __('Delete');?>"/>
		</p>
		<p class="col right"></p>
		<?php echo $core->formNonce(); ?>
		</div>
	</form>
	</fieldset>
			
<?php
		}
	}
?>
</div>

</body>
</html>
