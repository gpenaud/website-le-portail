
	<!-- nouvelles.html (dans dossier public) -->

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

		
	<title>Dernières nouvelles du Portail</title>
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
),'BlogLanguage'); ?>" content="<?php echo context::global_filters($_ctx->categories->cat_desc,array (
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
),'CategoryDescription'); ?>" />
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
	<?php if($_ctx->posts->trackbacksActive()) : ?><link rel="pingback" href="<?php echo context::global_filters($core->blog->url.$core->url->getURLFor('xmlrpc',$core->blog->id),array (
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
),'BlogXMLRPCURL'); ?>" /><?php endif; ?>
	<link rel="alternate" type="application/atom+xml" title="Atom 1.0" href="<?php echo context::global_filters($core->blog->url.$core->url->getURLFor("feed","atom"),array (
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
  'type' => 'atom',
),'BlogFeedURL'); ?>" />
	<!-- head-linkrel -->

		<?php try { echo $core->tpl->getData('_head.html'); } catch (Exception $e) {} ?>

	

	<script type="text/javascript" src="<?php echo context::global_filters($core->blog->getQmarkURL(),array (
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
),'BlogQmarkURL'); ?>pf=post.js"></script>
	<script type="text/javascript">
		var post_remember_str = '<?php echo __('Remember me on this blog'); ?>';
	</script>
	<!-- html-head -->
</head>
<body class="dc-page dc-news">
		<div id="contextBGNav">&nbsp;</div>
	
		<div id="page">
			
				
	<?php if ($_ctx->posts->trackbacksActive()) { echo $_ctx->posts->getTrackbackData('html'); } ?>

	
					<?php try { echo $core->tpl->getData('_top.html'); } catch (Exception $e) {} ?>

				
	<!-- page-top -->

				<div id="wrapper">
					
						<div id="main" role="main">
                          	<!--ancien emplacement de breadcrumb-->
							
								<div id="content">
									
	<div id="p<?php echo context::global_filters($_ctx->posts->post_id,array (
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
),'EntryID'); ?>" class="post" role="article">
		<h2 class="post-title"><?php echo context::global_filters($_ctx->posts->post_title,array (
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
),'EntryTitle'); ?></h2>

		<?php if ($core->hasBehavior('publicEntryBeforeContent')) { $core->callBehavior('publicEntryBeforeContent',$core,$_ctx);} ?>

		<?php if($_ctx->posts->isExtended()) : ?>
			<div class="post-excerpt"><?php echo context::global_filters($_ctx->posts->getExcerpt(0),array (
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
),'EntryExcerpt'); ?></div>
		<?php endif; ?>
		<div class="post-content"><?php echo context::global_filters($_ctx->posts->getContent(0),array (
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
),'EntryContent'); ?></div>

		<?php if ($core->hasBehavior('publicEntryAfterContent')) { $core->callBehavior('publicEntryAfterContent',$core,$_ctx);} ?>
		
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
$params['order'] = 'post_dt desc';
$params['post_selected'] = 1;$_ctx->post_params = $params;
$_ctx->posts = $core->blog->getPosts($params); unset($params);
?>
<?php while ($_ctx->posts->fetch()) : ?>
			<?php if ($_ctx->posts->isStart()) : ?>
				<ul class="news_list">
			<?php endif; ?>

			<li class="cat-<?php if($_ctx->posts->cat_id != null) :
$_ctx->entrycategories = $core->blog->getCategoryParents($_ctx->posts->cat_id);
//while(!$_ctx->entrycategories->isEnd()){$_ctx->entrycategories->fetch();}
if($_ctx->entrycategories->count()==0){$_ctx->entrycategories=$core->blog->getCategory($_ctx->posts->cat_id);}
echo context::global_filters($_ctx->entrycategories->cat_id,array (
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
  'id' => '1',
),'EntryCategoryParent');unset($_ctx->entrycategories);
else:
  echo "";
endif?>"><a
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
),'EntryURL'); ?>"><?php echo context::global_filters($_ctx->posts->post_title,array (
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
),'EntryTitle'); ?></a> <i><?php echo context::global_filters($_ctx->posts->getDate('%e %B %Y',''),array (
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
  'format' => '%e %B %Y',
),'EntryDate'); ?></i></li>

			<?php if ($_ctx->posts->isEnd()) : ?>
				</ul>
			<?php endif; ?>
		<?php endwhile; $_ctx->posts = null; $_ctx->post_params = null; ?>


	</div>

	<?php
if ($_ctx->posts !== null && $core->media) {
$_ctx->attachments = new ArrayObject($core->media->getPostMedia($_ctx->posts->post_id,null,"attachment"));
?>
<?php foreach ($_ctx->attachments as $attach_i => $attach_f) : $GLOBALS['attach_i'] = $attach_i; $GLOBALS['attach_f'] = $attach_f;$_ctx->file_url = $attach_f->file_url; ?>
		<?php if ($attach_i == 0) : ?>
			<div id="attachments">
				<h3><?php echo __('Attachments'); ?></h3>
				<ul>
		<?php endif; ?>
					<li class="<?php echo context::global_filters($attach_f->media_type,array (
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
),'AttachmentType'); ?>">
				<?php if($attach_f->type_prefix == "audio") : ?>
					<?php try { echo $core->tpl->getData('_audio_player.html'); } catch (Exception $e) {} ?>

				<?php endif; ?>
				<?php if($attach_f->type_prefix == "video") : ?>
					<?php if($attach_f->type != "video/x-flv") : ?>
						<?php try { echo $core->tpl->getData('_video_player.html'); } catch (Exception $e) {} ?>

					<?php else: ?>
						<?php try { echo $core->tpl->getData('_flv_player.html'); } catch (Exception $e) {} ?>

					<?php endif; ?>
				<?php endif; ?>
				<?php if($attach_f->type_prefix != "audio" && $attach_f->type_prefix != "video") : ?>
					<a href="<?php echo context::global_filters($attach_f->file_url,array (
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
),'AttachmentURL'); ?>"
						title="<?php echo context::global_filters($attach_f->basename,array (
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
),'AttachmentFileName'); ?> (<?php echo context::global_filters(files::size($attach_f->size),array (
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
),'AttachmentSize'); ?>)"><?php echo context::global_filters($attach_f->media_title,array (
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
),'AttachmentTitle'); ?></a>
				<?php endif; ?>
					</li>
		<?php if ($attach_i+1 == count($_ctx->attachments)) : ?>
				</ul>
			</div>
		<?php endif; ?>
	<?php endforeach; $_ctx->attachments = null; unset($attach_i,$attach_f,$_ctx->file_url); ?><?php } ?>


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