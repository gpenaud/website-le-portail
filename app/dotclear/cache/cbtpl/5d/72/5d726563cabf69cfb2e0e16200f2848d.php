
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

		
	<title><?php echo context::global_filters($_ctx->posts->post_title,array (
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
),'EntryTitle'); ?> - <?php echo context::global_filters($core->blog->name,array (
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
			
				<meta name="ROBOTS" content="<?php echo context::robotsPolicy($core->blog->settings->system->robots_policy,''); ?>" />
				<!-- meta-robots -->
			
	<meta name="description" lang="<?php if ($_ctx->posts->post_lang) { echo context::global_filters($_ctx->posts->post_lang,array (
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
),'EntryLang'); } ?>" content="<?php echo context::global_filters($_ctx->posts->getExcerpt(0)." ".$_ctx->posts->getContent(0),array (
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
  'full' => '1',
),'EntryContent'); ?>" />
	<meta name="author" content="<?php echo context::global_filters($_ctx->posts->getAuthorCN(),array (
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
),'EntryAuthorCommonName'); ?>" />
	<meta name="date" content="<?php echo context::global_filters($_ctx->posts->getISO8601Date(''),array (
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
),'EntryDate'); ?>" />
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
<body class="dc-page">
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
		
			<!-- Ici viennent s'ajouter des éléments grâce à des templates dérivés -->
		

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