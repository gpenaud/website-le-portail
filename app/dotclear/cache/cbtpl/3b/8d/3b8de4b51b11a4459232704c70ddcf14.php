
	<!--__layout.html-->

<!DOCTYPE html>
<html lang="<?php echo context::global_filters($core->blog->settings->system->lang,array (
  0 => NULL,
  'encode_xml' => 0,
  'encode_html' => 0,
  'cut_string' => 0,
  'lower_case' => 0,
  'upper_case' => 0,
  'encode_url' => 0,
  'remove_html' => 0,
  'capitalize' => 0,
  'strip_tags' => 0,
),'BlogLanguage'); ?>">
<head>
	
		<meta charset="UTF-8" />

		
	<title><?php echo __('Archives'); ?> - <?php echo context::global_filters(dt::dt2str('%B %Y',$_ctx->archives->dt),array (
  0 => NULL,
  'encode_xml' => 0,
  'encode_html' => 0,
  'cut_string' => 0,
  'lower_case' => 0,
  'upper_case' => 0,
  'encode_url' => 0,
  'remove_html' => 0,
  'capitalize' => 0,
  'strip_tags' => 0,
),'ArchiveDate'); ?> - <?php echo context::global_filters($core->blog->name,array (
  0 => NULL,
  'encode_xml' => 0,
  'encode_html' => '1',
  'cut_string' => 0,
  'lower_case' => 0,
  'upper_case' => 0,
  'encode_url' => 0,
  'remove_html' => 0,
  'capitalize' => 0,
  'strip_tags' => 0,
),'BlogName'); ?></title>
	<!-- head-title -->

		
			<meta name="copyright" content="<?php echo context::global_filters($core->blog->settings->system->copyright_notice,array (
  0 => NULL,
  'encode_xml' => 0,
  'encode_html' => '1',
  'cut_string' => 0,
  'lower_case' => 0,
  'upper_case' => 0,
  'encode_url' => 0,
  'remove_html' => 0,
  'capitalize' => 0,
  'strip_tags' => 0,
),'BlogCopyrightNotice'); ?>" />
			
	<meta name="ROBOTS" content="<?php echo context::robotsPolicy($core->blog->settings->system->robots_policy,'NOINDEX'); ?>" />
	<!-- meta-robots -->
			
				<meta name="description" lang="<?php echo context::global_filters($core->blog->settings->system->lang,array (
  0 => NULL,
  'encode_xml' => 0,
  'encode_html' => 0,
  'cut_string' => 0,
  'lower_case' => 0,
  'upper_case' => 0,
  'encode_url' => 0,
  'remove_html' => 0,
  'capitalize' => 0,
  'strip_tags' => 0,
),'BlogLanguage'); ?>" content="<?php echo context::global_filters($core->blog->desc,array (
  0 => NULL,
  'encode_xml' => 0,
  'encode_html' => '1',
  'cut_string' => '180',
  'lower_case' => 0,
  'upper_case' => 0,
  'encode_url' => 0,
  'remove_html' => '1',
  'capitalize' => 0,
  'strip_tags' => 0,
),'BlogDescription'); ?><?php if(!context::PaginationStart()) : ?> - <?php echo __('page'); ?> <?php echo context::global_filters(context::PaginationPosition(0),array (
  0 => NULL,
  'encode_xml' => 0,
  'encode_html' => 0,
  'cut_string' => 0,
  'lower_case' => 0,
  'upper_case' => 0,
  'encode_url' => 0,
  'remove_html' => 0,
  'capitalize' => 0,
  'strip_tags' => 0,
),'PaginationCurrent'); ?><?php endif; ?>" />
				<meta name="author" content="<?php echo context::global_filters($core->blog->settings->system->editor,array (
  0 => NULL,
  'encode_xml' => 0,
  'encode_html' => '1',
  'cut_string' => 0,
  'lower_case' => 0,
  'upper_case' => 0,
  'encode_url' => 0,
  'remove_html' => 0,
  'capitalize' => 0,
  'strip_tags' => 0,
),'BlogEditor'); ?>" />
				<meta name="date" content="<?php echo context::global_filters(dt::iso8601($core->blog->upddt,$core->blog->settings->system->blog_timezone),array (
  0 => NULL,
  'encode_xml' => 0,
  'encode_html' => 0,
  'cut_string' => 0,
  'lower_case' => 0,
  'upper_case' => 0,
  'encode_url' => 0,
  'remove_html' => 0,
  'capitalize' => 0,
  'strip_tags' => 0,
  'iso8601' => '1',
),'BlogUpdateDate'); ?>" />
				<!-- meta-entry -->
			
				<meta name="viewport" content="width=device-width, initial-scale=1">
			    <!-- meta-viewport -->
			<!-- head-meta -->

		
	<link rel="contents" href="<?php echo context::global_filters($core->blog->url.$core->url->getURLFor("archive"),array (
  0 => NULL,
  'encode_xml' => 0,
  'encode_html' => 0,
  'cut_string' => 0,
  'lower_case' => 0,
  'upper_case' => 0,
  'encode_url' => 0,
  'remove_html' => 0,
  'capitalize' => 0,
  'strip_tags' => 0,
),'BlogArchiveURL'); ?>" title="<?php echo __('Archives'); ?>" />
	<?php
if (!isset($params)) $params = array();
$params['type'] = 'month';
$params['next'] = $_ctx->archives->dt;$_ctx->archives = $core->blog->getDates($params); unset($params);
?>
<?php while ($_ctx->archives->fetch()) : ?><link rel="next" href="<?php echo context::global_filters($_ctx->archives->url($core),array (
  0 => NULL,
  'encode_xml' => 0,
  'encode_html' => 0,
  'cut_string' => 0,
  'lower_case' => 0,
  'upper_case' => 0,
  'encode_url' => 0,
  'remove_html' => 0,
  'capitalize' => 0,
  'strip_tags' => 0,
),'ArchiveURL'); ?>"
	title="<?php echo context::global_filters(dt::dt2str('%B %Y',$_ctx->archives->dt),array (
  0 => NULL,
  'encode_xml' => 0,
  'encode_html' => '1',
  'cut_string' => 0,
  'lower_case' => 0,
  'upper_case' => 0,
  'encode_url' => 0,
  'remove_html' => 0,
  'capitalize' => 0,
  'strip_tags' => 0,
),'ArchiveDate'); ?>" /><?php endwhile; $_ctx->archives = null; ?>
	<?php
if (!isset($params)) $params = array();$params['type'] = 'month';
$params['previous'] = $_ctx->archives->dt;$_ctx->archives = $core->blog->getDates($params); unset($params);
?>
<?php while ($_ctx->archives->fetch()) : ?><link rel="prev" href="<?php echo context::global_filters($_ctx->archives->url($core),array (
  0 => NULL,
  'encode_xml' => 0,
  'encode_html' => 0,
  'cut_string' => 0,
  'lower_case' => 0,
  'upper_case' => 0,
  'encode_url' => 0,
  'remove_html' => 0,
  'capitalize' => 0,
  'strip_tags' => 0,
),'ArchiveURL'); ?>"
	title="<?php echo context::global_filters(dt::dt2str('%B %Y',$_ctx->archives->dt),array (
  0 => NULL,
  'encode_xml' => 0,
  'encode_html' => '1',
  'cut_string' => 0,
  'lower_case' => 0,
  'upper_case' => 0,
  'encode_url' => 0,
  'remove_html' => 0,
  'capitalize' => 0,
  'strip_tags' => 0,
),'ArchiveDate'); ?>" /><?php endwhile; $_ctx->archives = null; ?>

	<?php if ($_ctx->exists("meta") && ($_ctx->meta->meta_type == "tag")) { if (!isset($params)) { $params = array(); }
if (!isset($params['from'])) { $params['from'] = ''; }
if (!isset($params['sql'])) { $params['sql'] = ''; }
$params['from'] .= ', '.$core->prefix.'meta META ';
$params['sql'] .= 'AND META.post_id = P.post_id ';
$params['sql'] .= "AND META.meta_type = 'tag' ";
$params['sql'] .= "AND META.meta_id = '".$core->con->escape($_ctx->meta->meta_id)."' ";
} ?>
<?php
if (!isset($_page_number)) { $_page_number = 1; }
$nb_entry_first_page=$_ctx->nb_entry_first_page; $nb_entry_per_page = $_ctx->nb_entry_per_page;
if (($core->url->type == 'default') || ($core->url->type == 'default-page')) {
    $params['limit'] = ($_page_number == 1 ? $nb_entry_first_page : $nb_entry_per_page);
} else {
    $params['limit'] = $nb_entry_per_page;
}
if (($core->url->type == 'default') || ($core->url->type == 'default-page')) {
    $params['limit'] = array(($_page_number == 1 ? 0 : ($_page_number - 2) * $nb_entry_per_page + $nb_entry_first_page),$params['limit']);
} else {
    $params['limit'] = array(($_page_number - 1) * $nb_entry_per_page,$params['limit']);
}
if ($_ctx->exists("users")) { $params['user_id'] = $_ctx->users->user_id; }
if ($_ctx->exists("categories")) { $params['cat_id'] = $_ctx->categories->cat_id.($core->blog->settings->system->inc_subcats?' ?sub':'');}
if ($_ctx->exists("archives")) { $params['post_year'] = $_ctx->archives->year(); $params['post_month'] = $_ctx->archives->month(); unset($params['limit']); }
if ($_ctx->exists("langs")) { $params['post_lang'] = $_ctx->langs->post_lang; }
if (isset($_search)) { $params['search'] = $_search; }
$params['order'] = 'post_dt desc';
$params['no_content'] = true;
$_ctx->post_params = $params;
$_ctx->posts = $core->blog->getPosts($params); unset($params);
?>
<?php while ($_ctx->posts->fetch()) : ?>
		<link rel="chapter" href="<?php echo context::global_filters($_ctx->posts->getURL(),array (
  0 => NULL,
  'encode_xml' => 0,
  'encode_html' => 0,
  'cut_string' => 0,
  'lower_case' => 0,
  'upper_case' => 0,
  'encode_url' => 0,
  'remove_html' => 0,
  'capitalize' => 0,
  'strip_tags' => 0,
),'EntryURL'); ?>" title="<?php echo context::global_filters($_ctx->posts->post_title,array (
  0 => NULL,
  'encode_xml' => 0,
  'encode_html' => '1',
  'cut_string' => 0,
  'lower_case' => 0,
  'upper_case' => 0,
  'encode_url' => 0,
  'remove_html' => 0,
  'capitalize' => 0,
  'strip_tags' => 0,
),'EntryTitle'); ?>" />
	<?php endwhile; $_ctx->posts = null; $_ctx->post_params = null; ?>
	<!-- head-linkrel -->

		<?php try { echo $core->tpl->getData('_head.html'); } catch (Exception $e) {} ?>

		<!-- html-head -->
</head>
<body class="dc-archive-month">
		<div id="contextBGNav">&nbsp;</div>
	
		<div id="page">
			
				
					<?php try { echo $core->tpl->getData('_top.html'); } catch (Exception $e) {} ?>

					<!-- page-top -->

				<div id="wrapper">
					
						<div id="main" role="main">
                          	<!--ancien emplacement de breadcrumb-->
							
								<div id="content">
									
	<p id="navlinks">
		<?php
if (!isset($params)) $params = array();$params['type'] = 'month';
$params['previous'] = $_ctx->archives->dt;$_ctx->archives = $core->blog->getDates($params); unset($params);
?>
<?php while ($_ctx->archives->fetch()) : ?><a href="<?php echo context::global_filters($_ctx->archives->url($core),array (
  0 => NULL,
  'encode_xml' => 0,
  'encode_html' => 0,
  'cut_string' => 0,
  'lower_case' => 0,
  'upper_case' => 0,
  'encode_url' => 0,
  'remove_html' => 0,
  'capitalize' => 0,
  'strip_tags' => 0,
),'ArchiveURL'); ?>" class="prev">&#171; <?php echo context::global_filters(dt::dt2str('%B %Y',$_ctx->archives->dt),array (
  0 => NULL,
  'encode_xml' => 0,
  'encode_html' => '1',
  'cut_string' => 0,
  'lower_case' => 0,
  'upper_case' => 0,
  'encode_url' => 0,
  'remove_html' => 0,
  'capitalize' => 0,
  'strip_tags' => 0,
),'ArchiveDate'); ?></a>
		- <?php endwhile; $_ctx->archives = null; ?>
		<a href="<?php echo context::global_filters($core->blog->url.$core->url->getURLFor("archive"),array (
  0 => NULL,
  'encode_xml' => 0,
  'encode_html' => 0,
  'cut_string' => 0,
  'lower_case' => 0,
  'upper_case' => 0,
  'encode_url' => 0,
  'remove_html' => 0,
  'capitalize' => 0,
  'strip_tags' => 0,
),'BlogArchiveURL'); ?>"><?php echo __('Archives'); ?></a>
		<?php
if (!isset($params)) $params = array();
$params['type'] = 'month';
$params['next'] = $_ctx->archives->dt;$_ctx->archives = $core->blog->getDates($params); unset($params);
?>
<?php while ($_ctx->archives->fetch()) : ?> - <a href="<?php echo context::global_filters($_ctx->archives->url($core),array (
  0 => NULL,
  'encode_xml' => 0,
  'encode_html' => 0,
  'cut_string' => 0,
  'lower_case' => 0,
  'upper_case' => 0,
  'encode_url' => 0,
  'remove_html' => 0,
  'capitalize' => 0,
  'strip_tags' => 0,
),'ArchiveURL'); ?>" class="next"><?php echo context::global_filters(dt::dt2str('%B %Y',$_ctx->archives->dt),array (
  0 => NULL,
  'encode_xml' => 0,
  'encode_html' => '1',
  'cut_string' => 0,
  'lower_case' => 0,
  'upper_case' => 0,
  'encode_url' => 0,
  'remove_html' => 0,
  'capitalize' => 0,
  'strip_tags' => 0,
),'ArchiveDate'); ?> &#187;</a><?php endwhile; $_ctx->archives = null; ?>
	</p>

	<div id="content-info">
		<h2><?php echo context::global_filters(dt::dt2str('%B %Y',$_ctx->archives->dt),array (
  0 => NULL,
  'encode_xml' => 0,
  'encode_html' => 0,
  'cut_string' => 0,
  'lower_case' => 0,
  'upper_case' => 0,
  'encode_url' => 0,
  'remove_html' => 0,
  'capitalize' => 0,
  'strip_tags' => 0,
),'ArchiveDate'); ?></h2>
	</div>

	<div class="content-inner">
		<?php if ($_ctx->exists("meta") && ($_ctx->meta->meta_type == "tag")) { if (!isset($params)) { $params = array(); }
if (!isset($params['from'])) { $params['from'] = ''; }
if (!isset($params['sql'])) { $params['sql'] = ''; }
$params['from'] .= ', '.$core->prefix.'meta META ';
$params['sql'] .= 'AND META.post_id = P.post_id ';
$params['sql'] .= "AND META.meta_type = 'tag' ";
$params['sql'] .= "AND META.meta_id = '".$core->con->escape($_ctx->meta->meta_id)."' ";
} ?>
<?php
if (!isset($_page_number)) { $_page_number = 1; }
$nb_entry_first_page=$_ctx->nb_entry_first_page; $nb_entry_per_page = $_ctx->nb_entry_per_page;
if (($core->url->type == 'default') || ($core->url->type == 'default-page')) {
    $params['limit'] = ($_page_number == 1 ? $nb_entry_first_page : $nb_entry_per_page);
} else {
    $params['limit'] = $nb_entry_per_page;
}
if (($core->url->type == 'default') || ($core->url->type == 'default-page')) {
    $params['limit'] = array(($_page_number == 1 ? 0 : ($_page_number - 2) * $nb_entry_per_page + $nb_entry_first_page),$params['limit']);
} else {
    $params['limit'] = array(($_page_number - 1) * $nb_entry_per_page,$params['limit']);
}
if ($_ctx->exists("users")) { $params['user_id'] = $_ctx->users->user_id; }
if ($_ctx->exists("categories")) { $params['cat_id'] = $_ctx->categories->cat_id.($core->blog->settings->system->inc_subcats?' ?sub':'');}
if ($_ctx->exists("archives")) { $params['post_year'] = $_ctx->archives->year(); $params['post_month'] = $_ctx->archives->month(); unset($params['limit']); }
if ($_ctx->exists("langs")) { $params['post_lang'] = $_ctx->langs->post_lang; }
if (isset($_search)) { $params['search'] = $_search; }
$params['order'] = 'post_dt desc';
$params['no_content'] = true;
$_ctx->post_params = $params;
$_ctx->posts = $core->blog->getPosts($params); unset($params);
?>
<?php while ($_ctx->posts->fetch()) : ?>

			<?php if ($_ctx->posts->firstPostOfDay()) : ?><p class="day-date"><?php echo context::global_filters($_ctx->posts->getDate('',''),array (
  0 => NULL,
  'encode_xml' => 0,
  'encode_html' => 0,
  'cut_string' => 0,
  'lower_case' => 0,
  'upper_case' => 0,
  'encode_url' => 0,
  'remove_html' => 0,
  'capitalize' => 0,
  'strip_tags' => 0,
),'EntryDate'); ?></p><?php endif; ?>

			<h2 id="p<?php echo context::global_filters($_ctx->posts->post_id,array (
  0 => NULL,
  'encode_xml' => 0,
  'encode_html' => 0,
  'cut_string' => 0,
  'lower_case' => 0,
  'upper_case' => 0,
  'encode_url' => 0,
  'remove_html' => 0,
  'capitalize' => 0,
  'strip_tags' => 0,
),'EntryID'); ?>" class="post-title" lang="<?php if ($_ctx->posts->post_lang) { echo context::global_filters($_ctx->posts->post_lang,array (
  0 => NULL,
  'encode_xml' => 0,
  'encode_html' => 0,
  'cut_string' => 0,
  'lower_case' => 0,
  'upper_case' => 0,
  'encode_url' => 0,
  'remove_html' => 0,
  'capitalize' => 0,
  'strip_tags' => 0,
),'EntryLang'); } else {echo context::global_filters($core->blog->settings->system->lang,array (
  0 => NULL,
  'encode_xml' => 0,
  'encode_html' => 0,
  'cut_string' => 0,
  'lower_case' => 0,
  'upper_case' => 0,
  'encode_url' => 0,
  'remove_html' => 0,
  'capitalize' => 0,
  'strip_tags' => 0,
),'EntryLang'); } ?>"><a
			href="<?php echo context::global_filters($_ctx->posts->getURL(),array (
  0 => NULL,
  'encode_xml' => 0,
  'encode_html' => 0,
  'cut_string' => 0,
  'lower_case' => 0,
  'upper_case' => 0,
  'encode_url' => 0,
  'remove_html' => 0,
  'capitalize' => 0,
  'strip_tags' => 0,
),'EntryURL'); ?>" title="<?php echo __('Read'); ?> <?php echo context::global_filters($_ctx->posts->post_title,array (
  0 => NULL,
  'encode_xml' => 0,
  'encode_html' => '1',
  'cut_string' => 0,
  'lower_case' => 0,
  'upper_case' => 0,
  'encode_url' => 0,
  'remove_html' => 0,
  'capitalize' => 0,
  'strip_tags' => 0,
),'EntryTitle'); ?>"><?php echo context::global_filters($_ctx->posts->post_title,array (
  0 => NULL,
  'encode_xml' => 0,
  'encode_html' => '1',
  'cut_string' => 0,
  'lower_case' => 0,
  'upper_case' => 0,
  'encode_url' => 0,
  'remove_html' => 0,
  'capitalize' => 0,
  'strip_tags' => 0,
),'EntryTitle'); ?></a></h2>

			<p class="post-info"><?php echo __('By'); ?> <?php echo context::global_filters($_ctx->posts->getAuthorLink(),array (
  0 => NULL,
  'encode_xml' => 0,
  'encode_html' => 0,
  'cut_string' => 0,
  'lower_case' => 0,
  'upper_case' => 0,
  'encode_url' => 0,
  'remove_html' => 0,
  'capitalize' => 0,
  'strip_tags' => 0,
),'EntryAuthorLink'); ?>
			<?php if(($_ctx->posts->hasComments() || $_ctx->posts->commentsActive())) : ?>
			- <a href="<?php echo context::global_filters($_ctx->posts->getURL(),array (
  0 => NULL,
  'encode_xml' => 0,
  'encode_html' => 0,
  'cut_string' => 0,
  'lower_case' => 0,
  'upper_case' => 0,
  'encode_url' => 0,
  'remove_html' => 0,
  'capitalize' => 0,
  'strip_tags' => 0,
),'EntryURL'); ?>#comments"><?php if ($_ctx->posts->nb_comment == 0) {
  printf(__('no comments'),$_ctx->posts->nb_comment);
} elseif ($_ctx->posts->nb_comment == 1) {
  printf(__('one comment'),$_ctx->posts->nb_comment);
} else {
  printf(__('%d comments'),$_ctx->posts->nb_comment);
} ?></a>
			<?php endif; ?>
			<?php if(($_ctx->posts->hasTrackbacks() || $_ctx->posts->trackbacksActive())) : ?>
			- <a href="<?php echo context::global_filters($_ctx->posts->getURL(),array (
  0 => NULL,
  'encode_xml' => 0,
  'encode_html' => 0,
  'cut_string' => 0,
  'lower_case' => 0,
  'upper_case' => 0,
  'encode_url' => 0,
  'remove_html' => 0,
  'capitalize' => 0,
  'strip_tags' => 0,
),'EntryURL'); ?>#pings"><?php if ($_ctx->posts->nb_trackback == 0) {
  printf(__('no trackbacks'),$_ctx->posts->nb_trackback);
} elseif ($_ctx->posts->nb_trackback == 1) {
  printf(__('one trackback'),$_ctx->posts->nb_trackback);
} else {
  printf(__('%d trackbacks'),$_ctx->posts->nb_trackback);
} ?></a><?php endif; ?>
			<?php if($_ctx->posts->countMedia('attachment')) : ?>
			- <a href="<?php echo context::global_filters($_ctx->posts->getURL(),array (
  0 => NULL,
  'encode_xml' => 0,
  'encode_html' => 0,
  'cut_string' => 0,
  'lower_case' => 0,
  'upper_case' => 0,
  'encode_url' => 0,
  'remove_html' => 0,
  'capitalize' => 0,
  'strip_tags' => 0,
),'EntryURL'); ?>#attachments"><?php if ($_ctx->posts->countMedia('attachment') == 0) {
  printf(__('no attachments'),$_ctx->posts->countMedia('attachment'));
} elseif ($_ctx->posts->countMedia('attachment') == 1) {
  printf(__('one attachment'),$_ctx->posts->countMedia('attachment'));
} else {
  printf(__('%d attachments'),$_ctx->posts->countMedia('attachment'));
} ?></a><?php endif; ?>
			</p>
		<?php endwhile; $_ctx->posts = null; $_ctx->post_params = null; ?>
	</div>
	<!-- main-content -->
								</div> <!-- End #content -->
								<!-- wrapper-main -->
						</div> <!-- End #main -->

						
							<div id="sidebar" role="complementary">
								<div id="blognav">
									<?php publicWidgets::widgetsHandler('nav',''); ?>
								</div> <!-- End #blognav -->
								<div id="blogextra">
									<?php publicWidgets::widgetsHandler('extra',''); ?>
								</div> <!-- End #blogextra -->
							</div>
							<!-- wrapper-sidebar -->
						<!-- page-wrapper -->
				</div> <!-- End #wrapper -->

				
					<?php try { echo $core->tpl->getData('_footer.html'); } catch (Exception $e) {} ?>

					<!-- page-footer -->
				<!-- body-page -->
		</div> <!-- End #page -->
		<!-- html-body -->
</body>
</html>