
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

		
	<title><?php echo __('Tags'); ?> - <?php echo context::global_filters($core->blog->name,array (
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
	<link rel="meta" type="application/xbel+xml" title="Blogroll" href="<?php echo context::global_filters($core->blog->url.$core->url->getURLFor("xbel"),array (
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
),'BlogrollXbelLink'); ?>" />
	<!-- head-linkrel -->

		<?php try { echo $core->tpl->getData('_head.html'); } catch (Exception $e) {} ?>

		<!-- html-head -->
</head>
<body class="dc-tags">
		<div id="contextBGNav">&nbsp;</div>
	
		<div id="page">
			
				
					<?php try { echo $core->tpl->getData('_top.html'); } catch (Exception $e) {} ?>

					<!-- page-top -->

				<div id="wrapper">
					
						<div id="main" role="main">
                          	<!--ancien emplacement de breadcrumb-->
							
								<div id="content">
									
	<div id="content-info">
		<h2><?php echo __('Tags'); ?></h2>
	</div>

	<div class="content-inner">
		<ul class="tags">
		<?php
$_ctx->meta = $core->meta->computeMetaStats($core->meta->getMetadata(array('meta_type'=>'tag','limit'=>null))); $_ctx->meta->sort('meta_id_lower','asc'); ?><?php while ($_ctx->meta->fetch()) : ?>
			<li><a href="<?php echo context::global_filters($core->blog->url.$core->url->getURLFor("tag",rawurlencode($_ctx->meta->meta_id)),array (
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
),'TagURL'); ?>" class="tag<?php echo $_ctx->meta->roundpercent; ?>"><?php echo context::global_filters($_ctx->meta->meta_id,array (
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
),'TagID'); ?></a></li>
		<?php endwhile; $_ctx->meta = null; ?>
		</ul>
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