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

# Menu template functions

/* require dirname(__FILE__).'/_widgets.php'; */

$core->tpl->addValue('MenuPortail',array('tplMenuPortail','menu'));
$core->tpl->addValue('IfCurrentLinkMenu',array('tplMenuPortail','IfCurrentLinkMenu'));
$core->addBehavior("publicHeadContent", array("tplMenuPortail", "publicHeadContent"));

class tplMenuPortail
{
	public static function publicHeadContent($core, $ctx) {
		global $core;
		if(file_exists(dirname(__FILE__)."menuportail.css")){
			echo "<link rel='stylesheet' type='text/css' href='" . $core->blog->getQmarkURL()."pf=menuPortail/menuportail.css' media='screen' />\n";
		}
		if(file_exists($core->blog->themes_path."/gam/menuportail.css")){
			echo "<link rel='stylesheet' type='text/css' href='/gam/themes/gam/menuportail.css' media='screen' />\n";	
		}
	}


	public static function IfCurrentLinkMenu($attr)
	{
		if ($_SERVER['REQUEST_URI']){}
	}
        
        
	public static function menu($attr)
	{       
            global $core;
                if (!$core->blog->settings->menuportail->active){
                    return "<!-- menuPortail disabled. Activate in admin -->\n";
                }
		$category = '<h3>%s</h3>';
		$block = '<ul class="menu">%s</ul>';
		$item = '<li>%s</li>';
		$open_ul = "";
		$close_ul = "";

		if (isset($attr['block'])) {
			$block = addslashes($attr['block']);
		}
		
		if (isset($attr['item'])) {
			$item = addslashes($attr['item']);
		}
		
		return
		$open_ul."\n".
		'<?php '.
		"echo tplMenuPortail::getList('".$category."','".$block."','".$item."'); ".
		'?>'.
		$close_ul."\n";
	}

	
	public static function getList($category='<h3>%s</h3>',$block='<ul class="menu">%s</ul>',$item='<li>%s</li>')
	{
		require_once dirname(__FILE__).'/class.dc.blogmenu.php';
		$menu = new dcBlogMenu($GLOBALS['core']->blog);
		
		try {
			$links = $menu->getLinks();
		} catch (Exception $e) {
                        echo $e->getMessage();
			return false;
		}
		
		$res = "";
		
		foreach ($menu->getLinksHierarchy($links) as $k => $v)
		{
			if ($k != '') {
				$res .= sprintf($category,html::escapeHTML($k))."\n";
			}
			
			$res .= self::getLinksList($v,$block,$item,$menu);
		}
		
		return $res;
	}
	
	//Cherche à déterminer si l'URL $childref fait référence à un enfant
	//de l'URL $parentref (post appartenant à une catégorie, post appartenant à une news)
	
	private static function isChildOf($parenthref,$childhref){
		global $core;
		require_once dirname(__FILE__).'/class.dc.blogmenu.php';		
		$childhref=urldecode($childhref);		
		$aChildURL=dcBlogMenu::parseLinkURL($childhref);
		
		// echo "<h3>isChildOf(\"".$parenthref."\",\"".$childhref."\")</h3>";
		// echo "<pre>".print_r($aChildURL,true)."</pre>";

		if(count($aChildURL)==0 || !array_key_exists('name',$aChildURL)) {
			return false;
		}
		$res=$core->blog->getPosts(array('post_url'=>$aChildURL['name'],'post_type'=>$aChildURL['type']));
	
		if($res->count()>0){
                    if($res->post_type == 'post' && $res->post_selected == 1){
                        $aParentURL=dcBlogMenu::parseLinkURL($parenthref);
                        if($aParentURL["name"]=="news"){
                                return true;					
                        }
                    }else{
                        $aParentURL=dcBlogMenu::parseLinkURL($parenthref);
                        //echo "<pre>".$aParentURL["name"]."</pre>";
                        if($aParentURL["type"]=="category"){
                            // echo "<pre>".$aParentURL["name"]."\n".$res->cat_url."</pre>";
                            return (strstr($res->cat_url,$aParentURL["name"])!==false);
                        }
                    }
		}
		return false;
	
	}	
	
	private static function getLinksList($links,$block='<ul class="menu">%s</ul>',$item='<li>%s</li>',$menu)
	{
		global $core;  // Pour avoir accès a l'url du blog et aussi connaître le theme
		$list = '';
		$url = $_SERVER['REQUEST_URI'];
		
		$first = true;
		
		foreach ($links as $v)
		{
			$title = $v['link_title'];
			$href  = $v['link_href'];
			$desc = $v['link_desc'];
			$lang  = $v['link_lang'];
			$xfn = $v['link_xfn'];
			

			// Si c'est le premier on lui met une classlien
			if ($first==true){
				$classlien=" class=\"first_menu\" ";
				$first=false;
			} else {
				$classlien="";
			}	
			
			// Si ce doit être le dernier
			if ($xfn=="me"){
				$classlast=" last_menu";
				$classlienlast=" class=\"last_menu\""; 
				$classitem=$classlast;
			} else {
				$classlast="";
				$classlienlast="";
				$classitem="page_item";
			}	

			$classsup="";
			
			$link =
			'<a href="'.html::escapeHTML($href).'"'.
			((!$lang) ? '' : ' hreflang="'.html::escapeHTML($lang).'"').
			((!$desc) ? '' : ' title="'.html::escapeHTML($desc).'"').
			((!$xfn) ? '' : ' rel="'.html::escapeHTML($xfn).'"').
			$classlien.$classlienlast.
			'><span>'.
			html::escapeHTML($title).
			'</span></a>';
			
			// Si il faut tester aussi si page accueil
			if ($xfn=="accueil"){
				// Si nous sommes en accueil
				if ($core->url->type == 'default') {
					$item = '<li class="current_page_item '.$classitem.' %s">%s</li>';
				} else {
					$item = '<li class="'.$classitem.' %s">%s</li>';
				}
			} else {	
				$ehref=html::escapeHTML($href);
				if ($url == $ehref || substr($url,0,strlen($ehref)) == $ehref || self::isChildOf($ehref,$url)) {
					$item = '<li class="current_page_item '.$classitem.' %s">%s</li>';
				} else {
					$item = '<li class="'.$classitem.' %s">%s</li>';			
				}
			}	
			
			$sublinks = $menu->getLinks(array("link_parent"=>$v['link_id']),true);
			$sublink='<ul class="subitem">';
			$has_sub_links=false;
			foreach($menu->getLinksHierarchy($sublinks) as $k => $v){
				foreach($v as $sl){
					$subtitle=$sl["link_title"];
					$subhref=$sl["link_href"];
					$sublang=$sl["link_lang"];
					$has_sub_links=true;
					$sublink .= '<li class="page_subitem"><a href="'.$subhref.'" title="'.$subtitle.'" hreflang="'.$sublang.'"><span>'.$subtitle.'</span></a></li>';			
				}
			}
			$sublink .= '</ul>';	
			if($has_sub_links){
				$link .= $sublink;
			}else{
				$classsup="nosub";
			}
			$list .= sprintf($item,$classsup,$link)."\n";
		}

		return sprintf($block,$list)."\n";
	}
	

}

?>