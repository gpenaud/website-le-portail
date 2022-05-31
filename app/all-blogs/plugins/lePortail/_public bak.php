<?php
# -- BEGIN LICENSE BLOCK ----------------------------------
#
# This file is part of lePortail, a plugin for Dotclear 2
#
# (c)2019 Nurbo Teva for Association Du Grain à Moudre
#
# Licensed under the GPL version 2.0 license.
# A copy of this license is available in LICENSE file or at
# http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
#
# -- END LICENSE BLOCK ------------------------------------

if (!defined('DC_RC_PATH')) { return; }

$core->tpl->addValue('CategoryID', array('dcPortail', 'CategoryID'));
$core->tpl->addValue('EntryCategoryParent', array('dcPortail', 'EntryCategoryParent'));
$core->tpl->addValue('CategoryParent', array('dcPortail', 'CategoryParent'));
$core->tpl->addValue('Bug', array('dcPortail', 'Bug'));


/*Manage special tags as [news_list]*/
$core->addBehavior('publicBeforeContentFilter',array('dcPortail','publicBeforeContentFilter'));

class dcPortail
{
	/*	tpl:EntryCategoryParent : retrieves the top level category for the current post
	 *   With no arg, returns the category title
	 *	 @param url retrieves the URL
	 *	 @param shorturl retrieves the short URL
	 *   @param ID retrieves the category ID
	 * 
	 */
	public static function EntryCategoryParent($a) {
		$ret1='if($_ctx->posts->cat_id != null) :'."\n".
                        '$_ctx->entrycategories = $core->blog->getCategoryParents($_ctx->posts->cat_id);'."\n".
			  '//while(!$_ctx->entrycategories->isEnd()){$_ctx->entrycategories->fetch();}'."\n".
			  'if($_ctx->entrycategories->count()==0){$_ctx->entrycategories=$core->blog->getCategory($_ctx->posts->cat_id);}'."\n";
		if (isset($a['url']) && $a['url']) {
			$ret = '$core->blog->url.$core->url->getURLFor("category",' .
				   '$_ctx->entrycategories->cat_url)';
		} elseif (isset($a['shorturl']) && $a['shorturl']) {
			$ret = '$_ctx->entrycategories->cat_url';
		} elseif (isset($a['id']) && $a['id']) {
			$ret = '$_ctx->entrycategories->cat_id';
		} else {
			$ret = '$_ctx->entrycategories->cat_title';
		}
		$ret2 = 'unset($_ctx->entrycategories);' . "\n".
                        'else:'."\n".
                        '  echo "";'."\n".
                        'endif'
                        ;

		return '<?php ' . $ret1 . "echo " . sprintf($GLOBALS['core']->tpl->getFilters($a), $ret) . ';'. $ret2. '?>';
	}

	public static function CategoryParent($a) {
		$ret1='$_ctx->pcategories = $core->blog->getCategoryParent($_ctx->categories->cat_id);'."\n".
			  'if($_ctx->pcategories->count()==0){$_ctx->pcategories=$core->blog->getCategory($_ctx->categories->cat_id);}'."\n";

		if (isset($a['url']) && $a['url']) {
			$ret = '$core->blog->url.$core->url->getURLFor("category",' .
				   '$_ctx->pcategories->cat_url)';
		} elseif (isset($a['shorturl']) && $a['shorturl']) {
			$ret = '$_ctx->pcategories->cat_url';
		} elseif (isset($a['id']) && $a['id']) {
			$ret = '$_ctx->pcategories->cat_id';
		} else {
			$ret = '$_ctx->pcategories->cat_title';
		}
		
		$ret2 = 'unset($_ctx->pcategories);' . "\n";

		return '<?php ' . $ret1 . "echo " . sprintf($GLOBALS['core']->tpl->getFilters($a), $ret) . ';'. $ret2. '?>';
	}
	
	public static function CategoryID($a) {
		return '<?php echo ' . sprintf($GLOBALS['core']->tpl->getFilters($a), "\$_ctx->categories->cat_id") . '; ?>';
	}

	public static function publicBeforeContentFilter ($core, $tag, $arr) {
		if( $tag == 'EntryContent' || $tag == 'EntryExcerpt' ) {
			$txt = $arr[0];
			$out=array();
			$all=false;
			$debug="";
			if(preg_match_all("/\[news_list\b([^\]]*)\]/is",$txt,$out)>0){
				foreach($out[0] as $k=>$v) {
					$values=array("order"=>["post_dt DESC"],"set_title"=>false,"set_link"=>false);
					$tinout=array();
					preg_match_all('`(\w+)\s*=\s*\"([^\"]*)\"`isU',$out[1][$k],$tinout);
					foreach($tinout[1] as $k2=>$v2) {
						if (!isset($values[$v2])) {
							$values[$v2] = array();
						}
						switch($v2){
	                        case "set_link":
	                            $values['set_link']=(boolean)$tinout[2][$k2];
	                            break;
							default:
								$values[$v2]=$tinout[2][$k2];
						}					
					}

					$news_list=[];
					$params=["sql"=>" AND post_selected = 1 "];
					if(isset($values["order"])){
						$params["order"]=$values["order"][0];
					}

					$debug .= "Values : ".print_r($values,true)."\n";

					$debug .= "Params : ".print_r($params,true)."\n";

					$posts = null;

/*					$posts = $core->blog->getPosts(array_merge($params,["sql_only"=>true]));
					if(is_string($posts)){
						$debug.="strreq :\n".$posts."\n";
					}*/

					$posts = $core->blog->getPosts($params);
					$counter=0;
					while($posts->fetch()){
						$debug.="fetch ". ++$counter."\n";
						if($values['set_title']){
							$tooltip=strip_tags(str_replace('"','\"',$posts->post_excerpt));
						}
					    $link1=$link2="";
					    if($values['set_link']){
					        $link1="<a title=\"".$tooltip."\" href='".$posts->getURL()."'>";
					        $link2="</a>";
					    }
						$news_list[] = ['date'=>dt::dt2str(__("%e/%m/%Y"),$posts->post_dt,$posts->post_tz),
												  'link1'=>$link1,
												  'title'=>$posts->post_title,
												  'link2'=>$link2];
					}
					$debug.= '$news_list contient '.count($news_list)." dates.\n";
					$debug .= '$news_list : '.print_r($news_list,true)."\n";
					$nbposts=count($news_list,COUNT_RECURSIVE);
					global $plural;
					$plural = ($nbposts>1);
					

					$title=isset($values["title"])?$values["title"]:__("Fresh news");
					$title=preg_replace_callback('|\{([^\|]*)\|([^\}]*)\}|', function ($matches){global $plural; return $matches[1+$plural];}, $title);

					$news_header="<div class='newslist'><h4>".$title."</h4>\n";
					if ($nbposts == 0){
						$news_header.="<h6>".($values['none']?$values['none']:__("No fresh news"))."</h6></div>";
						$txt = str_replace($out[0][$k], $news_header, $txt);
						continue;
	                } else if($nbposts > 10) {
						$news_header.="<ul class='news10'>\n";
					} else {
						$news_header.="<ul class='news'>\n";
					}				
					$newshtml = $news_header;
					foreach ($news_list as $news) {
						$newshtml.= sprintf("<li>%s%s%s <i>(%s)</i></li>\n",$news['link1'],$news['title'],$news['link2'],$news['date']);
					}
					$newshtml.="</ul></div>\n";
					$txt = str_replace($out[0][$k], $newshtml, $txt);
				}
				// $txt.="\n<br><textarea rows=30 cols=120 class='debug' style='overflow:no'>".$debug."</textarea>";
			}
			$arr[0] = $txt;
		}
	}


        public static function Bug($a){
            return "<?php 12/0;?>";
        }
        
}
 
?>
