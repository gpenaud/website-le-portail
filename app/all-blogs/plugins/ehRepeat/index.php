<?php
/* -- BEGIN LICENSE BLOCK ----------------------------------
 *
 * This file is part of ehRepeat, a plugin for Dotclear 2.
 *
 * Copyright(c) 2019 Onurb Teva <dev@taktile.fr>
 *
 * Licensed under the GPL version 2.0 license.
 * A copy of this license is available in LICENSE file or at
 * http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
 *
 * -- END LICENSE BLOCK ------------------------------------*/


if (!defined('DC_CONTEXT_ADMIN')) { exit; }

require_once dirname(__FILE__).'/inc/class.dcehrepeat.php';

if (!empty($_REQUEST['settings'])) {
	include dirname(__FILE__).'/settings.php';
	return;
}

$default_tab = 'events';
$p_url 	= 'plugin.php?p=ehRepeat';

$ehRepeat = new dcEhRepeat($core);

if (!empty($_REQUEST['delete'])) {
	$rpts_2_delete = $_REQUEST['rpts'];
	foreach ($rpts_2_delete as $rpt) {
		try{
			$ehRepeat->delete($rpt);		
		}catch(Exception $e){
			$core->error->add("EhRepeat : ".$e);
		}
	}
	http::redirect($p_url);
}

$rs=$ehRepeat->getEhRepeat(array());

$hidden_fields =
form::hidden(array('p'),'ehRepeat').
$core->formNonce();

$delete_ok=$core->auth->check('delete,contentadmin',$core->blog->id);

?>
<html>
<head>
  <title><?php echo __("Repetitive events")?></title>
	  <?php //echo dcPage::jsConfirmClose('activate-form','add-link-form','add-category-form'); ?>
  <?php echo dcPage::jsPageTabs($default_tab); ?>
</head>

<body>
<h2><?php echo html::escapeHTML($core->blog->name); ?> &gt; <?php echo __("Repetitive events")?></h2>
<div class="multi-part" title="<?php echo __('Events'); ?>" id="events">
<form action="plugin.php" method="post" id="events_form">
<table class="clear">
<thead>
<tr>
  <th colspan="2"><?php echo __('Title'); ?></th>
  <th><?php echo __('Start date'); ?></th>
  <th><?php echo __('End date'); ?></th>
  <th><?php echo __('Repetition pattern'); ?></th>
</tr>
</thead>
<tbody id="events_list">
<?php
while ($rs->fetch())
{
	echo
	'<tr class="line" id="l_'.$rs->rpt_id.'">'.
	'<td class="nowrap">'.form::checkbox(array('rpts[]'),$rs->rpt_id).'</td>'.
	'<td class="maximal">'.'<a href="'.$core->getPostAdminURL($rs->post_type,$rs->post_id).'">'.html::escapeHTML($rs->post_title).'</td>'.
	'<td class="nowrap">'.html::escapeHTML($rs->event_startdt).'</td>'.
	'<td class="nowrap">'.html::escapeHTML($rs->event_enddt).'</td>'.
	'<td class="nowrap">'.html::escapeHTML($rs->rpt_freq).'</td>'.
	'</tr>'."\n";
}
?>
</tbody>
</table>
<div class="two-cols">
<p class="col checkboxes-helpers"></p>
<p class="col right">
	<?php if($delete_ok){
		echo __('Selected entries action:');
	?>
		<input type="submit" value ="<?php echo __('Delete');?>" name="delete"/>
	<?php }

	  echo $hidden_fields;
	?>
</p>
</div>
</form>

</div>
</body>
</html>