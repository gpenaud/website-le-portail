
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

	<?php $next_post = $core->blog->getNextPost($_ctx->posts,1,0,0); ?>
<?php if ($next_post !== null) : ?><?php $_ctx->posts = $next_post; unset($next_post);
while ($_ctx->posts->fetch()) : ?><link rel="next" href="<?php echo context::global_filters($_ctx->posts->getURL(),array (
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
),'EntryURL'); ?>"
	title="<?php echo context::global_filters($_ctx->posts->post_title,array (
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
),'EntryTitle'); ?>" /><?php endwhile; $_ctx->posts = null; ?><?php endif; ?>


	<?php $prev_post = $core->blog->getNextPost($_ctx->posts,-1,0,0); ?>
<?php if ($prev_post !== null) : ?><?php $_ctx->posts = $prev_post; unset($prev_post);
while ($_ctx->posts->fetch()) : ?><link rel="prev" href="<?php echo context::global_filters($_ctx->posts->getURL(),array (
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
),'EntryURL'); ?>"
	title="<?php echo context::global_filters($_ctx->posts->post_title,array (
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
),'EntryTitle'); ?>" /><?php endwhile; $_ctx->posts = null; ?><?php endif; ?>


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
<body class="dc-post">
		<div id="contextBGNav">&nbsp;</div>
	
		<div id="page">
			
				
	<?php if ($_ctx->posts->trackbacksActive()) { echo $_ctx->posts->getTrackbackData('html'); } ?>

	
					<?php try { echo $core->tpl->getData('_top.html'); } catch (Exception $e) {} ?>

				
	<!-- page-top -->

				<div id="wrapper">
					
						<div id="main" role="main">
                          	<!--ancien emplacement de breadcrumb-->
							
								<div id="content">
									
	<p id="navlinks">
		<?php $prev_post = $core->blog->getNextPost($_ctx->posts,-1,0,0); ?>
<?php if ($prev_post !== null) : ?><?php $_ctx->posts = $prev_post; unset($prev_post);
while ($_ctx->posts->fetch()) : ?><a href="<?php echo context::global_filters($_ctx->posts->getURL(),array (
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
),'EntryURL'); ?>"
		title="<?php echo context::global_filters($_ctx->posts->post_title,array (
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
),'EntryTitle'); ?>" class="prev">&#171; <?php echo context::global_filters($_ctx->posts->post_title,array (
  0 => NULL,
  'encode_xml' => 0,
  'encode_html' => '1',
  'cut_string' => '50',
  'lower_case' => 0,
  'upper_case' => 0,
  'encode_url' => 0,
  'remove_html' => 0,
  'capitalize' => 0,
  'strip_tags' => 0,
),'EntryTitle'); ?></a><?php endwhile; $_ctx->posts = null; ?><?php endif; ?>

		<?php $next_post = $core->blog->getNextPost($_ctx->posts,1,0,0); ?>
<?php if ($next_post !== null) : ?><?php $_ctx->posts = $next_post; unset($next_post);
while ($_ctx->posts->fetch()) : ?> <span>-</span> <a href="<?php echo context::global_filters($_ctx->posts->getURL(),array (
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
),'EntryURL'); ?>"
		title="<?php echo context::global_filters($_ctx->posts->post_title,array (
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
),'EntryTitle'); ?>" class="next"><?php echo context::global_filters($_ctx->posts->post_title,array (
  0 => NULL,
  'encode_xml' => 0,
  'encode_html' => '1',
  'cut_string' => '50',
  'lower_case' => 0,
  'upper_case' => 0,
  'encode_url' => 0,
  'remove_html' => 0,
  'capitalize' => 0,
  'strip_tags' => 0,
),'EntryTitle'); ?> &#187;</a><?php endwhile; $_ctx->posts = null; ?><?php endif; ?>

	</p>

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
		<h2 class="post-title cat-<?php if($_ctx->posts->cat_id != null) :
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
endif?> li-<?php if($_ctx->posts->cat_id != null) :
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
endif?>">
			 <?php echo context::global_filters($_ctx->posts->post_title,array (
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
),'EntryTitle'); ?>
		</h2>

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
		<?php echo __('on'); ?> <?php echo context::global_filters($_ctx->posts->getDate('',''),array (
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
),'EntryDate'); ?>, <?php echo context::global_filters($_ctx->posts->getTime('',''),array (
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
),'EntryTime'); ?>
		<?php if($_ctx->posts->cat_id) : ?>
		- <a href="<?php echo context::global_filters($_ctx->posts->getCategoryURL(),array (
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
),'EntryCategoryURL'); ?>"><?php echo context::global_filters($_ctx->posts->cat_title,array (
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
),'EntryCategory'); ?></a>
		<?php endif; ?>
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
),'EntryURL'); ?>"><?php echo __('Permalink'); ?></a>
		</p>

		<?php
$_ctx->meta = $core->meta->getMetaRecordset($_ctx->posts->post_meta,'tag'); $_ctx->meta->sort('meta_id_lower','asc'); ?><?php while ($_ctx->meta->fetch()) : ?>
		<?php if ($_ctx->meta->isStart()) : ?><ul class="post-tags"><?php endif; ?>
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
),'TagURL'); ?>"  title="<?php echo context::global_filters($_ctx->meta->meta_id,array (
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
),'TagID'); ?>" rel="tag"><?php echo context::global_filters($_ctx->meta->meta_id,array (
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
		<?php if ($_ctx->meta->isEnd()) : ?></ul><?php endif; ?>
		<?php endwhile; $_ctx->meta = null; ?>

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


	<?php if(($_ctx->posts->hasComments() || $_ctx->posts->commentsActive())) : ?>
		<?php if ($_ctx->exists("meta") && ($_ctx->meta->meta_type == "tag")) { if (!isset($params)) { $params = array(); }
if (!isset($params['from'])) { $params['from'] = ''; }
if (!isset($params['sql'])) { $params['sql'] = ''; }
$params['from'] .= ', '.$core->prefix.'meta META ';
$params['sql'] .= 'AND META.post_id = P.post_id ';
$params['sql'] .= "AND META.meta_type = 'tag' ";
$params['sql'] .= "AND META.meta_id = '".$core->con->escape($_ctx->meta->meta_id)."' ";
} ?>
<?php
$params['comment_trackback'] = false;
if ($_ctx->nb_comment_per_page !== null) { $params['limit'] = $_ctx->nb_comment_per_page; }
if ($_ctx->posts !== null) { $params['post_id'] = $_ctx->posts->post_id; $core->blog->withoutPassword(false);
}
if ($_ctx->exists("categories")) { $params['cat_id'] = $_ctx->categories->cat_id; }
if ($_ctx->exists("langs")) { $params['sql'] = "AND P.post_lang = '".$core->blog->con->escape($_ctx->langs->post_lang)."' "; }
$params['order'] = 'comment_dt asc';
$_ctx->comments = $core->blog->getComments($params); unset($params);
if ($_ctx->posts !== null) { $core->blog->withoutPassword(true);}
?>
<?php while ($_ctx->comments->fetch()) : ?>
		<?php if ($_ctx->comments->isStart()) : ?>
			<div id="comments">
				<h3><?php echo __('Comments'); ?></h3>
			<dl>
		<?php endif; ?>
			<dt id="c<?php echo $_ctx->comments->comment_id; ?>" class="<?php if ($_ctx->comments->isMe()) { echo 'me'; } ?> <?php if (($_ctx->comments->index()+1)%2) { echo 'odd'; } ?> <?php if ($_ctx->comments->index() == 0) { echo 'first'; } ?>"><a
			href="#c<?php echo $_ctx->comments->comment_id; ?>" class="comment-number"><?php echo $_ctx->comments->index()+1; ?>.</a>
			<?php echo __('On'); ?> <?php echo context::global_filters($_ctx->comments->getDate('',''),array (
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
),'CommentDate'); ?>, <?php echo context::global_filters($_ctx->comments->getTime('',''),array (
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
),'CommentTime'); ?>
			<?php echo __('by'); ?> <?php echo context::global_filters($_ctx->comments->getAuthorLink(),array (
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
),'CommentAuthorLink'); ?></dt>

			<dd class="<?php if ($_ctx->comments->isMe()) { echo 'me'; } ?> <?php if (($_ctx->comments->index()+1)%2) { echo 'odd'; } ?> <?php if ($_ctx->comments->index() == 0) { echo 'first'; } ?>">

			<?php if ($core->hasBehavior('publicCommentBeforeContent')) { $core->callBehavior('publicCommentBeforeContent',$core,$_ctx);} ?>

			<?php echo context::global_filters($_ctx->comments->getContent(0),array (
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
),'CommentContent'); ?>

			<?php if ($core->hasBehavior('publicCommentAfterContent')) { $core->callBehavior('publicCommentAfterContent',$core,$_ctx);} ?>
			</dd>
		<?php if ($_ctx->comments->isEnd()) : ?>
			</dl>
			</div>
		<?php endif; ?>
		<?php endwhile; $_ctx->comments = null; ?>
	<?php endif; ?>

	<?php if($_ctx->posts->commentsActive()) : ?>
		<?php if ($_ctx->form_error !== null) : ?>
			<p class="error" id="pr"><?php if ($_ctx->form_error !== null) { echo $_ctx->form_error; } ?></p>
		<?php endif; ?>

		<?php if (!empty($_GET['pub'])) : ?>
			<p class="message" id="pr"><?php echo __('Your comment has been published.'); ?></p>
		<?php endif; ?>

		<?php if (isset($_GET['pub']) && $_GET['pub'] == 0) : ?>
			<p class="message" id="pr"><?php echo __('Your comment has been submitted and will be reviewed for publication.'); ?></p>
		<?php endif; ?>

		<form action="<?php echo context::global_filters($_ctx->posts->getURL(),array (
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
),'EntryURL'); ?>#pr" method="post" id="comment-form" role="form">
			<?php if ($_ctx->comment_preview !== null && $_ctx->comment_preview["preview"]) : ?>
				<div id="pr">
					<h3><?php echo __('Your comment'); ?></h3>
					<dl>
						<dd class="comment-preview"><?php echo context::global_filters($_ctx->comment_preview["content"],array (
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
),'CommentPreviewContent'); ?></dd>
					</dl>
					<p class="buttons"><input type="submit" class="submit" value="<?php echo __('send'); ?>" /></p>
				</div>
			<?php endif; ?>

			<h3><?php echo __('Add a comment'); ?></h3>
			<fieldset>

				<?php if ($core->hasBehavior('publicCommentFormBeforeContent')) { $core->callBehavior('publicCommentFormBeforeContent',$core,$_ctx);} ?>

				<p class="field"><label for="c_name"><?php echo __('Name or nickname'); ?>&nbsp;:</label>
				<input name="c_name" id="c_name" type="text" size="30" maxlength="255"
				value="<?php echo context::global_filters($_ctx->comment_preview["name"],array (
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
),'CommentPreviewName'); ?>" />
				</p>

				<p class="field"><label for="c_mail"><?php echo __('Email address'); ?>&nbsp;:</label>
				<input name="c_mail" id="c_mail" type="text" size="30" maxlength="255"
				value="<?php echo context::global_filters($_ctx->comment_preview["mail"],array (
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
),'CommentPreviewEmail'); ?>" />
				</p>

				<p class="field"><label for="c_site"><?php echo __('Website'); ?> (<?php echo __('optional'); ?>)&nbsp;:</label>
				<input name="c_site" id="c_site" type="text" size="30" maxlength="255"
				value="<?php echo context::global_filters($_ctx->comment_preview["site"],array (
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
),'CommentPreviewSite'); ?>" />
				</p>

				<p style="display:none"><input name="f_mail" type="text" size="30"
				maxlength="255" value="" /></p>

				<p class="field"><label for="c_content"><?php echo __('Comment'); ?>&nbsp;:</label>
				<textarea name="c_content" id="c_content" cols="35"
				rows="7"><?php echo context::global_filters($_ctx->comment_preview["rawcontent"],array (
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
  'raw' => '1',
),'CommentPreviewContent'); ?></textarea>
				</p>
				<p class="form-help"><?php if ($core->blog->settings->system->wiki_comments) {
  echo __('Comments can be formatted using a simple wiki syntax.');
} else {
  echo __('HTML code is displayed as text and web addresses are automatically converted.');
} ?></p>

				<?php if ($core->hasBehavior('publicCommentFormAfterContent')) { $core->callBehavior('publicCommentFormAfterContent',$core,$_ctx);} ?>
			</fieldset>

			<fieldset>
				<p class="buttons"><input type="submit" class="preview" name="preview" value="<?php echo __('preview'); ?>" />
				<?php if ($core->blog->settings->system->comment_preview_optional || ($_ctx->comment_preview !== null && $_ctx->comment_preview["preview"])) : ?><input type="submit" class="submit" value="<?php echo __('send'); ?>" /><?php endif; ?></p>
			</fieldset>
		</form>
	<?php endif; ?>

	<?php if(($_ctx->posts->hasTrackbacks() || $_ctx->posts->trackbacksActive())) : ?>
		<div id="pings">
			<h3><?php echo __('They posted on the same topic'); ?></h3>
			<?php
if ($_ctx->posts !== null) { $params['post_id'] = $_ctx->posts->post_id; $core->blog->withoutPassword(false);
}
$params['comment_trackback'] = true;
if ($_ctx->nb_comment_per_page !== null) { $params['limit'] = $_ctx->nb_comment_per_page; }
if ($_ctx->exists("categories")) { $params['cat_id'] = $_ctx->categories->cat_id; }
if ($_ctx->exists("langs")) { $params['sql'] = "AND P.post_lang = '".$core->blog->con->escape($_ctx->langs->post_lang)."' "; }
$params['order'] = 'comment_dt asc';
$_ctx->pings = $core->blog->getComments($params); unset($params);
if ($_ctx->posts !== null) { $core->blog->withoutPassword(true);}
?>
<?php while ($_ctx->pings->fetch()) : ?>
				<?php if ($_ctx->pings->isStart()) : ?>
				<dl>
				<?php endif; ?>
					<dt id="c<?php echo $_ctx->pings->comment_id; ?>" class="<?php if (($_ctx->pings->index()+1)%2) { echo 'odd'; } ?> <?php if ($_ctx->pings->index() == 0) { echo 'first'; } ?>"><a href="#c<?php echo $_ctx->pings->comment_id; ?>"
					class="ping-number"><?php echo $_ctx->pings->index()+1; ?>.</a>
					<?php echo __('On'); ?> <?php echo context::global_filters($_ctx->pings->getDate('',''),array (
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
),'PingDate'); ?>, <?php echo context::global_filters($_ctx->pings->getTime('',''),array (
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
),'PingTime'); ?>
					<?php echo __('by'); ?> <?php echo context::global_filters($_ctx->pings->comment_author,array (
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
),'PingBlogName'); ?></dt>

					<dd class="<?php if (($_ctx->pings->index()+1)%2) { echo 'odd'; } ?> <?php if ($_ctx->pings->index() == 0) { echo 'first'; } ?>">

					<?php if ($core->hasBehavior('publicPingBeforeContent')) { $core->callBehavior('publicPingBeforeContent',$core,$_ctx);} ?>

					<p><a href="<?php echo context::global_filters($_ctx->pings->getAuthorURL(),array (
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
),'PingAuthorURL'); ?>"
					<?php if($core->blog->settings->system->comments_nofollow) { echo ' rel="nofollow"';} ?>><?php echo context::global_filters($_ctx->pings->getTrackbackTitle(),array (
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
),'PingTitle'); ?></a></p>
					<?php echo context::global_filters($_ctx->pings->getTrackbackContent(),array (
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
),'PingContent'); ?>

					<?php if ($core->hasBehavior('publicPingAfterContent')) { $core->callBehavior('publicPingAfterContent',$core,$_ctx);} ?>
					</dd>
				<?php if ($_ctx->pings->isEnd()) : ?>
				</dl>
				<?php endif; ?>
			<?php endwhile; $_ctx->pings = null; ?>
		</div>
	<?php endif; ?>

	<?php if($_ctx->posts->trackbacksActive()) : ?>
		<p id="ping-url"><?php echo __('Trackback URL'); ?>&nbsp;: <?php if ($_ctx->posts->trackbacksActive()) { echo $_ctx->posts->getTrackbackLink(); } ?>
</p>
	<?php endif; ?>

	<?php if($_ctx->posts->commentsActive() || $_ctx->posts->trackbacksActive()) : ?>
	<p id="comments-feed"><a class="feed" href="<?php echo context::global_filters($core->blog->url.$core->url->getURLFor("feed","atom"),array (
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
),'BlogFeedURL'); ?>/comments/<?php echo context::global_filters($_ctx->posts->post_id,array (
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
),'EntryID'); ?>"
	title="<?php echo __('This post\'s comments Atom feed'); ?>"><?php echo __('This post\'s comments feed'); ?></a></p>
	<?php endif; ?>
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