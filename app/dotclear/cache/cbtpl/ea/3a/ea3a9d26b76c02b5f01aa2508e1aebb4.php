
	<!-- eventHandler single -->

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

		
			<link rel="contents" title="<?php echo __('Archives'); ?>" href="<?php echo context::global_filters($core->blog->url.$core->url->getURLFor("archive"),array (
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
),'BlogArchiveURL'); ?>" />
			<?php
if (!isset($params)) $params = array();
$_ctx->categories = $core->blog->getCategories($params);
?>
<?php while ($_ctx->categories->fetch()) : ?>
				<link rel="section" href="<?php echo context::global_filters($core->blog->url.$core->url->getURLFor("category",$_ctx->categories->cat_url),array (
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
),'CategoryURL'); ?>" title="<?php echo context::global_filters($_ctx->categories->cat_title,array (
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
),'CategoryTitle'); ?>" />
			<?php endwhile; $_ctx->categories = null; unset($params); ?>

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
				<?php if ($_ctx->posts->isStart()) : ?>
					<?php
$params = $_ctx->post_params;
$_ctx->pagination = $core->blog->getPosts($params,true); unset($params);
?>
<?php if ($_ctx->pagination->f(0) > $_ctx->posts->count()) : ?>
						<?php if(!context::PaginationEnd()) : ?>
							<link rel="prev" title="<?php echo __('previous entries'); ?>" href="<?php echo context::global_filters(context::PaginationURL(1),array (
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
  'offset' => '1',
),'PaginationURL'); ?>" />
						<?php endif; ?>

						<?php if(!context::PaginationStart()) : ?>
							<link rel="next" title="<?php echo __('next entries'); ?>" href="<?php echo context::global_filters(context::PaginationURL(-1),array (
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
  'offset' => '-1',
),'PaginationURL'); ?>" />
						<?php endif; ?>
					<?php endif; ?>
				<?php endif; ?>

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
			<link rel="EditURI" type="application/rsd+xml" title="RSD" href="<?php echo context::global_filters($core->blog->url.$core->url->getURLFor('rsd'),array (
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
),'BlogRSDURL'); ?>" />
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

	
	<!-- default css for eventHandler -->
    <link rel="stylesheet" type="text/css" href="<?php echo context::global_filters($core->blog->getQmarkURL(),array (
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
),'BlogQmarkURL'); ?>pf=eventHandler/css/default-eventhandler.css" media="screen" />

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
      //<![CDATA[
  var post_remember_str = '<?php echo __('Remember me on this blog'); ?>';
  //]]>
    </script>
 	<!-- html-head -->
</head>
<body class="dc-post dc-category-event">
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
	      <a href="<?php echo context::global_filters($core->blog->url.$core->url->getBase("eventhandler_list"),array (
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
),'EventsURL'); ?>" title="<?php echo __('list of events'); ?>"><?php echo __('All events'); ?></a>
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
		  		- <a href="<?php echo context::global_filters($core->blog->url.$core->url->getBase("eventhandler_list")."/category/".html::sanitizeURL($_ctx->posts->cat_url),array (
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
),'EventsEntryCategoryURL'); ?>"><?php echo context::global_filters($_ctx->posts->cat_title,array (
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
),'TagURL'); ?>" rel="tag"><?php echo context::global_filters($_ctx->meta->meta_id,array (
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

	      <ul class="event-info">
			<?php if($_ctx->posts->isRepeat()) :?>
				<li class="event-date">
		  			<?php echo $_ctx->posts->getReadableFreq(); ?> <?php echo __('of the month'); ?>, <?php echo __('from'); ?> <?php echo context::global_filters($_ctx->posts->getEventTime('','startdt'),array (
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
  'startdt' => '1',
),'EventsEntryTime'); ?> <?php echo __('to (hour)'); ?> <?php echo context::global_filters($_ctx->posts->getEventTime('','enddt'),array (
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
  'enddt' => '1',
),'EventsEntryTime'); ?> (<?php echo __('period of'); ?> <?php echo context::global_filters(eventHandler::getReadableDuration((strtotime($_ctx->posts->event_enddt) - strtotime($_ctx->posts->event_startdt)),''),array (
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
),'EventsEntryDuration'); ?>)</li>
				<?php
if(!isset($ehRepeat)) { $ehRepeat = new dcEhRepeat($core); } 
if ($_ctx->exists("posts") && $_ctx->posts->post_id) { $params['siblings_from_id'] = $_ctx->posts->post_id;
} else {
$params['all'] = true;
}
$_ctx->rpteventdates_params = $params;
$_ctx->rpteventdates = $ehRepeat->getEvents($params); unset($params); 
while ($_ctx->rpteventdates->fetch()) : ?>
					<?php if ($_ctx->rpteventdates->isStart()) : ?>
						<li>Dates à venir :
						<ul>
					<?php endif; ?>
					<li><?php echo context::global_filters($_ctx->rpteventdates->getEventDate('','startdt'),array (
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
  'startdt' => '1',
),'EhRepeatEventDate');
        ?></li>
					<?php if ($_ctx->rpteventdates->isEnd()) : ?>
						</ul></li>
					<?php endif; ?>
				<?php endwhile; 
$_ctx->rpteventdates = null; $_ctx->rpteventdates_params = null; ?>

			<?php else: ?>
				<li class="event-date">
		  			<?php if(!$_ctx->posts->isOnOneDay()) : ?><?php echo __('From'); ?> <?php echo context::global_filters($_ctx->posts->getEventDate('','startdt'),array (
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
  'startdt' => '1',
),'EventsEntryDate'); ?>, <?php echo context::global_filters($_ctx->posts->getEventTime('','startdt'),array (
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
  'startdt' => '1',
),'EventsEntryTime'); ?> <?php echo __('to'); ?> <?php echo context::global_filters($_ctx->posts->getEventDate('','enddt'),array (
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
  'enddt' => '1',
),'EventsEntryDate'); ?>, <?php echo context::global_filters($_ctx->posts->getEventTime('','enddt'),array (
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
  'enddt' => '1',
),'EventsEntryTime'); ?><?php endif; ?>
		  			<?php if($_ctx->posts->isOnOneDay()) : ?><?php echo __('on'); ?> <?php echo context::global_filters($_ctx->posts->getEventDate('','startdt'),array (
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
  'startdt' => '1',
),'EventsEntryDate'); ?>, <?php echo context::global_filters($_ctx->posts->getEventTime('','startdt'),array (
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
  'startdt' => '1',
),'EventsEntryTime'); ?> <?php echo __('till'); ?> <?php echo context::global_filters($_ctx->posts->getEventTime('','enddt'),array (
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
  'enddt' => '1',
),'EventsEntryTime'); ?><?php endif; ?>
				</li>
				<li class="event-duration"><?php echo __('for a period of'); ?> <?php echo context::global_filters(eventHandler::getReadableDuration((strtotime($_ctx->posts->event_enddt) - strtotime($_ctx->posts->event_startdt)),''),array (
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
),'EventsEntryDuration'); ?></li>
				<?php if('' != $_ctx->posts->event_address) : ?>
		  			<li class="event-address"><?php echo context::global_filters($_ctx->posts->event_address,array (
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
),'EventsEntryAddress'); ?></li>
				<?php endif; ?>
				<li class="event-period"><?php echo __('this event is'); ?> <?php $time = time() + dt::getTimeOffset($_ctx->posts->post_tz)*2;
if ($_ctx->posts->getEventTS('startdt') > $time) {
 echo context::global_filters('programmé',array (
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
),'EventsEntryPeriod'); }
elseif ($_ctx->posts->getEventTS('startdt') < $time && $_ctx->posts->getEventTS('enddt') > $time) {
 echo context::global_filters('en cours',array (
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
),'EventsEntryPeriod'); }
elseif ($_ctx->posts->getEventTS('enddt') < $time) {
 echo context::global_filters('terminé',array (
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
),'EventsEntryPeriod'); }
unset($time); ?>
</li>
		  	<?php endif; ?>
	      </ul>

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

	      <?php echo eventHandler::getMapContent("","",$core->blog->settings->eventHandler->public_map_type,($_ctx->posts->event_zoom)?$_ctx->posts->event_zoom:$core->blog->settings->eventHandler->public_map_zoom,0,$_ctx->posts->event_latitude,$_ctx->posts->event_longitude,$_ctx->posts->getMapVEvent()); ?>
	      <?php if(("" != $_ctx->posts->event_latitude && "" != $_ctx->posts->event_longitude)) : ?>
			<ul class="event-info"><li class="event-geo"><?php echo __('Latitude:'); ?> <?php echo context::global_filters($_ctx->posts->event_latitude,array (
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
),'EventsEntryLatitude'); ?>, <?php echo __('Longitude:'); ?> <?php echo context::global_filters($_ctx->posts->event_longitude,array (
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
),'EventsEntryLongitude'); ?></li></ul>
	      <?php endif; ?>

	      <?php if ($core->hasBehavior('publicEntryAfterContent')) { $core->callBehavior('publicEntryAfterContent',$core,$_ctx);} ?>
	    </div>

	    <p id="eventsfeed"><a class="event-ical" type="text/calendar" href="<?php echo context::global_filters(http::getSelfURI(),array (
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
),'SysSelfURI'); ?>/ical.ics" title="<?php echo __('Add this event to your iCalendar'); ?>"><?php echo __('iCalendar'); ?></a><br />
	      <a class="event-hcal" type="text/calendar" href="<?php echo context::global_filters(http::getSelfURI(),array (
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
),'SysSelfURI'); ?>/hcal.html" title="<?php echo __('Display this event on hCalendar format'); ?>"><?php echo __('hCalendar'); ?></a></p>
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