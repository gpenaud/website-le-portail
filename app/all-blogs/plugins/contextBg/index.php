<?php
# -- BEGIN LICENSE BLOCK ----------------------------------
#
# This file is part of contextBG a plugin for Dotclear 2.
# 
# Copyright (c) 2015 Onurbruno
# Licensed under the GPL version 2.0 license.
# A copy of this license is available in LICENSE file or at
# http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
#
# -- END LICENSE BLOCK ------------------------------------

if (!defined('DC_CONTEXT_ADMIN')) { exit; }

error_reporting( E_ALL );

$settings = $core->blog->settings->contextBg;

if($_REQUEST['save'])
{
	dcPage::addWarningNotice("<pre>".print_r($_REQUEST,true)."</pre>");

	if(isset($_REQUEST['active']))
		$settings->put('active',($_REQUEST['active']==1),'boolean',__('Enable this plugin'));
	if(isset($_REQUEST['excludehome']))
		$settings->put('excludehome',$_REQUEST['excludehome'],'boolean',__('Disable for homepage'));
	if(isset($_REQUEST['selector']))
		$settings->put('selector',base64_encode($_REQUEST['selector']),'string',__('Selector for the element to apply the background in'));
	if(isset($_REQUEST['pattern']))
		$settings->put('pattern',base64_encode($_REQUEST['pattern']),string,__('Pattern for background image files'));
	if(isset($_REQUEST['multipattern']))
		$settings->put('multipattern',base64_encode($_REQUEST['multipattern']),string,__('Pattern for multiple background image files'));
	if(isset($_REQUEST['default']))
		$settings->put('default',base64_encode($_REQUEST['default']),string,__('Default background image'));
	if(isset($_REQUEST['field']))
		$settings->put('field',base64_encode($_REQUEST['field']),string,__('Field to use for background selection'));	
	if(isset($_REQUEST['extracss']))	
		$settings->put('css',base64_encode($_REQUEST['extracss']),string,__('Extra css definitions'));
	$message=__("Settings saved!");
	http::redirect($p_url);
} 

?>
<html>
<head>
  <title><?php echo __("Context Background");?></title>
  <?php echo dcPage::jsConfirmClose('links-form','add-link-form','add-category-form'); ?>
  <?php echo dcPage::jsPageTabs($default_tab); ?>
  <script>
//<![CDATA[
		$(document).ready(function(){
			$("#settings").css("display",$("[name=active]").attr("checked")?"block":"none");
			$("[name=active]").click(function () {
				$("#settings").css("display",this.checked?"block":"none");
			})
		//]]>
  </script>
</head>

<body>
<h2><?php echo html::escapeHTML($core->blog->name); ?> &gt; <?php echo __("Context Background");?></h2>
<?php
	if(isset($message))
	{
?>
		<p class="message"><?php echo $message;?></p>
<?php		
	}
?>
<div>
<form action="plugin.php" method="post" id="contextBg-form">
<fieldset>
	<legend><?php echo __("Context Backgound activation");?></legend>
	<p><label class="classic">
		<?php echo(form::checkbox('active','1',$settings->active).' '.__('Enable')); ?>
	</label></p>
</fieldset>
<fieldset id="settings">
	<legend><?php echo __("Context Background settings");?></legend>
	<p><label class="classic">
		<?php echo(form::checkbox('excludehome','1',$settings->excludehome).' '.__('Disable for homepage')); ?>
	</label></p> 
	<p><label class="required classic" for="selector">
	<abbr title="<?php echo(__('Required field')); ?>">*</abbr>
	<?php echo __("Selector for the element to apply the background in");?>
	<?php echo form::field("selector",20,255,base64_decode($settings->selector)); ?> 
	</label></p>
	<p><label class="required classic" for="pattern">
	<abbr title="<?php echo(__('Required field')); ?>">*</abbr>
	<?php echo __("Pattern for background image files") ;?>
	<?php echo form::field("pattern",20,255,base64_decode($settings->pattern)); ?> 	
	<p><label class="required classic" for="multipattern">
	<abbr title="<?php echo(__('Required field')); ?>">*</abbr>
	<?php echo __("Pattern for multiple background image files") ;?>
	<?php echo form::field("multipattern",20,255,base64_decode($settings->multipattern)); ?> 
	<p><label class="required classic" for="field">
	<abbr title="<?php echo(__('Required field')); ?>">*</abbr>
	<?php echo __('Field to use for background selection') ;?>
	<?php echo form::field("field",20,255,base64_decode($settings->field)); ?> 	
	</label></p>
	<p><label class="required classic" for="default">
	<abbr title="<?php echo(__('Required field')); ?>">*</abbr>
	<?php echo __('Default background image') ;?>
	<?php echo form::field("default",20,255,base64_decode($settings->default)); ?> 	

	<p class="area"><label><?php echo __('Extra css definitions').form::textarea('extracss',80,5,base64_decode($settings->css));?></label>
</fieldset>
<p>
<?php echo $core->formNonce().form::hidden('p','contextBg'); ?>
<input type="submit" name="save" value="<?php echo __('Save'); ?>" /></p>
</form>
</div>
</body>
</html>