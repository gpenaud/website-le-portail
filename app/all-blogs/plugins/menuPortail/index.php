<?php
# ***** BEGIN LICENSE BLOCK *****
# This file is part of DotClear.
# Copyright (c) 2005 Olivier Meunier and contributors. All rights
# reserved.
#
# DotClear is free software; you can redistribute it and/or modify
# it under the terms of the GNU General Public License as published by
# the Free Software Foundation; either version 2 of the License, or
# (at your option) any later version.
# 
# DotClear is distributed in the hope that it will be useful,
# but WITHOUT ANY WARRANTY; without even the implied warranty of
# MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
# GNU General Public License for more details.
# 
# You should have received a copy of the GNU General Public License
# along with DotClear; if not, write to the Free Software
# Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
#
# ***** END LICENSE BLOCK *****
if (!defined('DC_CONTEXT_ADMIN')) { exit; }

require_once dirname(__FILE__).'/class.dc.blogmenu.php';

$menu = new dcBlogMenu($core->blog);

if (!empty($_REQUEST['edit']) && !empty($_REQUEST['id'])) {
	include dirname(__FILE__).'/edit.php';
	return;
}

$is_super_admin = $core->auth->isSuperAdmin();
$menu_enabled = $core->blog->settings->menuportail->active;

$default_tab = $menu_enabled?'menu':'settings';
$link_title = $link_href = $link_desc = $link_lang = '';
$cat_title = '';

$extra_post_types = $core->blog->settings->menuportail->extra_post_types;

if (!empty($_POST['set_settings']))
{
    $default_tab = 'settings';
    $menu_enabled = isset($_POST['enable_menu']);
    $extra_post_types = isset($_POST['extra_post_types']) ? $_POST['extra_post_types'] : $extra_post_types;
    $core->blog->settings->menuportail->put('active',$menu_enabled,'boolean');
    $core->blog->settings->menuportail->put('extra_post_types',$extra_post_types,'string');
}

if (!empty($_POST['update_all_links']))
{
    global $core;
    $menu = new dcBlogMenu($core->blog);
    $menu->updateAllLinks();
    dcPage::addSuccessNotice(__('All menus have been regenerated'));
}

# Add link
if (!empty($_POST['add_link']))
{
	$link_title = $_POST['link_title'];
	$link_href = $_POST['link_href'];
	$link_desc = $_POST['link_desc'];
	$link_lang = $_POST['link_lang'];
	
	try {
		$menu->addLink($link_title,$link_href,$link_desc,$link_lang);
		http::redirect($p_url.'&addlink=1');
	} catch (Exception $e) {
		$core->error->add($e->getMessage());
		$default_tab = 'add-link';
	}
}


# Add category
if (!empty($_POST['add_cat']))
{
	$cat_title = $_POST['cat_title'];
	
	try {
		$menu->addCategory($cat_title);
		http::redirect($p_url.'&addcat=1');
	} catch (Exception $e) {
		$core->error->add($e->getMessage());
		$default_tab = 'add-cat';
	}
}


# Delete link
if (!empty($_POST['removeaction']) && !empty($_POST['remove'])) {
	foreach ($_POST['remove'] as $k => $v)
	{
		try {
			$menu->delItem($v);
		} catch (Exception $e) {
			$core->error->add($e->getMessage());
			break;
		}
	}
	
	if (!$core->error->flag()) {
		http::redirect($p_url.'&removed=1');
	}
}

# Order links
$order = array();
if (empty($_POST['links_order']) && !empty($_POST['order'])) {
	$order = $_POST['order'];
	asort($order);
	$order = array_keys($order);
} elseif (!empty($_POST['links_order'])) {
	$order = explode(',',$_POST['links_order']);
}

if (!empty($_POST['saveorder']) && !empty($order))
{
	foreach ($order as $pos => $l) {
		$pos = ((integer) $pos)+1;
		
		try {
			$menu->updateOrder($l,$pos);
		} catch (Exception $e) {
			$core->error->add($e->getMessage());
		}
	}
	
	if (!$core->error->flag()) {
		http::redirect($p_url.'&neworder=1');
	}
}


# Get links
try {
	$rs = $menu->getLinks();
} catch (Exception $e) {
	$core->error->add($e->getMessage());
}

?>
<html>
<head>
  <title>Menu Portail</title>
	<?php //echo dcPage::jsToolMan(); ?>
	<?php echo dcPage::jsLoad('index.php?pf=menuPortail/js/jquery-ui.js'); ?>
  <?php echo dcPage::jsConfirmClose('links-form','add-link-form','add-category-form'); ?>
  <script type="text/javascript">
  //<![CDATA[
  		
//  var dragsort = ToolMan.dragsort();
  $(function() {
  	$("#links-list").sortable({
  		update: function(event,ui){
  		var index=1;
  		$("#links-list>.line td:first-child>input").each(function(){
  			$(this).val(index++);
  		});
  	}
  	});
  });
  //]]>
  </script>
  <?php echo dcPage::jsPageTabs($default_tab); ?>
</head>

<body>
<h2><?php echo html::escapeHTML($core->blog->name); ?> &gt; Menu</h2>

<?php
if (!empty($_GET['neworder'])) {
	echo '<p class="message">'.__('Items order has been successfully updated').'</p>';
}

if (!empty($_GET['removed'])) {
		echo '<p class="message">'.__('Items have been successfully removed.').'</p>';
}

if (!empty($_GET['addlink'])) {
		echo '<p class="message">'.__('Link has been successfully created.').'</p>';
}

if (!empty($_GET['addcat'])) {
		echo '<p class="message">'.__('category has been successfully created.').'</p>';
}

if (!empty($_GET['importlinks'])) {
		echo '<p class="message">'.__('links have been successfully imported.').'</p>';
}

?>

<div class="multi-part" id="menu" title="<?php echo __('Menu'); ?>">
<form action="plugin.php" method="post" id="links-form">
<table class="maximal dragable">
<thead>
<tr>
  <th colspan="3"><?php echo __('Title'); ?></th>
  <th><?php echo __('Description'); ?></th>
  <th><?php echo __('URL'); ?></th>
  <th><?php echo __('Lang'); ?></th>
</tr>
</thead>
<tbody id="links-list">
<?php
while ($rs->fetch())
{
	$position = (string) $rs->index()+1;
	
	echo
	'<tr class="line" id="l_'.$rs->link_id.'">'.
//	'<td class="handle minimal">'.form::field(array('order['.$rs->link_id.']'),2,5,$position).'</td>'.
	'<td class="handle minimal">'.form::hidden(array('order['.$rs->link_id.']'),$position).'</td>'.
	'<td class="minimal">'.form::checkbox(array('remove[]'),$rs->link_id).'</td>';
	
	
	if ($rs->is_cat)
	{
		echo
		'<td colspan="5"><strong><a href="'.$p_url.'&amp;edit=1&amp;id='.$rs->link_id.'">'.
		html::escapeHTML($rs->link_desc).'</a></strong></td>';
	}
	else
	{
		echo
		'<td><a href="'.$p_url.'&amp;edit=1&amp;id='.$rs->link_id.'">'.
		html::escapeHTML($rs->link_title).'</a></td>'.
		'<td>'.html::escapeHTML($rs->link_desc).'</td>'.
		'<td>'.html::escapeHTML($rs->link_href).'</td>'.
		'<td>'.html::escapeHTML($rs->link_lang).'</td>';
	}
	
	echo '</tr>';
}
?>
</tbody>
</table>

<div class="two-cols">
<p class="col"><?php echo form::hidden('links_order','');
echo form::hidden(array('p'),'menuPortail');
echo $core->formNonce(); ?>
<input type="submit" name="saveorder" value="<?php echo __('Save order'); ?>" />
<input type="submit" name="update_all_links" value="<?php echo __('Regenerate all menus'); ?>" />
</p>

<p class="col right"><input type="submit" name="removeaction"
value="<?php echo __('Delete selected links'); ?>"
onclick="return window.confirm('<?php echo html::escapeJS(
__('Are you sure you you want to delete selected links?')); ?>');" /></p>
</div>

</form>
</div>

<?php
echo
'<div class="multi-part" id="add-link" title="'.__('Add a link').'">'.
'<form action="plugin.php" method="post" id="add-link-form">'.
'<fieldset class="two-cols"><legend>'.__('Add a new link').'</legend>'.
'<p class="col"><label class="required" title="'.__('Required field').'">'.__('Title:').' '.
form::field('link_title',30,255,$link_title,'',2).
'</label></p>'.

'<p class="col"><label class="required" title="'.__('Required field').'">'.__('URL:').' '.
form::field('link_href',30,255,$link_href,'',3).
'</label></p>'.

'<p class="col"><label>'.__('Description:').' '.
form::field('link_desc',30,255,$link_desc,'',4).
'</label></p>'.

'<p class="col"><label>'.__('Language:').' '.
form::field('link_lang',5,5,$link_lang,'',5).
'</label></p>'.
'<p>'.form::hidden(array('p'),'menuPortail').
$core->formNonce().
'<input type="submit" name="add_link" value="'.__('save').'" tabindex="6" /></p>'.
'</fieldset>'.
'</form>'.
'</div>';


//echo
//'<div class="multi-part" id="add-cat" title="'.__('Add a category').'">'.
//'<form action="plugin.php" method="post" id="add-category-form">'.
//'<fieldset><legend>'.__('Add a new category').'</legend>'.
//'<p><label class=" classic required" title="'.__('Required field').'">'.__('Title:').' '.
//form::field('cat_title',30,255,$cat_title,'',7).'</label> '.
//form::hidden(array('p'),'menuPortail').
//$core->formNonce().
//'<input type="submit" name="add_cat" value="'.__('save').'" tabindex="8" /></p>'.
//'</fieldset>'.
//'</form>'.
//'</div>';

if($is_super_admin ){
        echo
        '<div class="multi-part" id="settings" title="'.__('Settings').'">'.
        '<form action="plugin.php" method="post" id="settings-form">'.
        '<label class=" classic required" title="'.__('Required field').'">'.__('Menu enabled :').' '.
        form::checkbox('enable_menu',1, $menu_enabled).
        '<fieldset '. ($menu_enabled?'style="transition:display 1s;"':'style="transition:display 1s;display:none;"') .'><legend>'.__('Settings').'</legend>'.
        '<label class=" classic" title="'.__('Extra post types to include in menus').'">'.__('Extra post types to include in menus').': '.
        form::field('extra_post_types',30,255,$extra_post_types,'',7).'</label>'.
        '</fieldset>'.
        form::hidden(array('p'),'menuPortail').
        $core->formNonce().
        '<br/><input type="submit" name="set_settings" value="'.__('save').'" />'.
        '</form>'.
        '</div>';
}
?>

</body>
</html>