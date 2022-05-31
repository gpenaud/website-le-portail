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
if (!defined('DC_CONTEXT_ADMIN')) { exit; }
$msg = "";
$g_core = &$GLOBALS['core'];
if (!$g_core->auth->check('media,media_admin,admin',$g_core->blog->id)) { exit; }
$all_formats = spMedia::getAllDcFormats();
$all_formats['bsq']=new spFormat('bsq', 'square','\.(.+)_(bsq)',1);

if (!empty($_POST['dosomething'])) {
	$doSomething = $_POST['dosomething'];
	$rep_name = $_POST['rep_name'];
	$option_format = $_POST['option_format'];
	$spAllMedia = new spAllMedia($g_core,$option_format);
	$msg = $spAllMedia->doSomething($doSomething,$rep_name,$all_formats);
}
?>
<html>
<head>
	<title><?php echo __('Thumbnails Generator'); ?></title>
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
<h2><?php echo html::escapeHTML($g_core->blog->name); ?> &gt; <?php echo __('Thumbnails Generator'); ?></h2>
<?php if (!empty($msg)) echo '<p class="message">'.$msg.'</p>';?>
<div class="multi-part" id="image" title="<?php echo __('Create or delete thumbnail'); ?>">
  <!--h3><?php echo __("Create or delete thumbnail"); ?></h3-->

	<form method="post" action="plugin.php">
	<fieldset>
		<legend><?php echo __('Create'); ?></legend>
		<?php 
		//echo "<p>".form::combo(array('option_format','option_format_create'),array( "Dotclear Format" => "dc", "bsq"=>"bsq"))."</p>";
		echo '<input type="hidden" name="option_format" value="dc" />';
		?>
		<p class="field">
		<label class=" classic">
		<?php echo __('Pictures\'s directory to process');
		echo form::combo(array('rep_name','rep_name_create'),spPicturesTools::getDirNameComboBox(spFormatsTools::$original_pitures_format_id,false,$all_formats)); ?>
		</label>
		</p>
		<p>
			<input type="hidden" name="p" value="thumbnailsGenerator" />
			<?php echo $g_core->formNonce(); ?>
			<input type="submit" name="dosomething" value="<?php echo __('Create'); ?>" />
		</p>
	</fieldset>
	</form>

<br />

	<form method="post" action="plugin.php">
	<fieldset>
		<legend><?php echo __('Delete'); ?></legend>
		<?php 
		//echo "<p>".form::combo(array('option_format','option_format_del'),array( "Dotclear Format" => "dc", "bsq"=>"bsq"))."</p>";
		echo '<input type="hidden" name="option_format" value="dc" />';
		?>
		<p class="field">
		<label class=" classic">
		<?php echo __('Pictures\'s directory to process');?>
		<?php echo form::combo(array('rep_name','rep_name_del'),spPicturesTools::getDirNameComboBox(spFormatsTools::$original_pitures_format_id,false,$all_formats)); ?>
		</label>
		</p>
		<p>
			<input type="hidden" name="p" value="thumbnailsGenerator" />
			<?php echo $g_core->formNonce(); ?>
			<input type="submit" name="dosomething" value="<?php echo __('Delete'); ?>" />
		</p>
	</fieldset>
	</form>
</div>

</body>
</html>
