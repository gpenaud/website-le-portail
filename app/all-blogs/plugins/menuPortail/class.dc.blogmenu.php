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

class dcBlogMenu
{
	private $blog;
	private $con;
	private $table;
	
	public function __construct(&$blog)
	{
		$this->blog =& $blog;
		$this->con =& $blog->con;
		$this->table = $this->blog->prefix.'menu';
	}

	public function updateAllLinks(){
                $this->delChildren();
		$rs=$this->getLinks(array(),false);
		while($rs->fetch()){
			$this->updateLink($rs->link_id,$rs->link_title,$rs->link_href,$rs->link_desc,$rs->link_lang, $rs->link_xfn, -1);		
		}
	}	
	
	public function getLinks($params=array(),$parents=false)
	{
		$strReq = 'SELECT link_id, link_title, link_desc, link_href, '.
				'link_lang, link_xfn, link_position, link_parent '.
				'FROM '.$this->table.' '.
				"WHERE blog_id = '".$this->con->escape($this->blog->id)."'".
				( ($parents===false) ?" AND link_parent = -1 ":"");
		
		if (isset($params['link_id'])) {
			$strReq .= ' AND link_id = '.(integer) $params['link_id'];
		}
		
		if (isset($params['link_parent'])) {
			$strReq .= ' AND link_parent = '.(integer) $params['link_parent'];
		}
				
		$strReq .= ' ORDER BY link_position ';
		
		$rs = $this->con->select($strReq);
		$rs = $rs->toStatic();
		
		$this->setLinksData($rs);
				
		return $rs;
	}
		
	public function getLink($id)
	{
		$params['link_id'] = $id;
		
		$rs = $this->getLinks($params);
		
		return $rs;
	}
	
	//If $id is a link to a category, finds all the
	//relevant children
	private function findLinkChildren($id){
		$strReq = 'SELECT link_id, link_title, link_desc, link_href, '.
				'link_lang, link_xfn, link_position, link_parent '.
				'FROM '.$this->table.' '.
				"WHERE blog_id = '".$this->con->escape($this->blog->id)."'".
				" AND 'link_parent' = " . (integer)$id .
				" ORDER BY link_position ";

		$rs = $this->con->select($strReq);
		$rs = $rs->toStatic();
		
		$this->setLinksData($rs);
		
		return $rs;		
	}	
	
	public static function parseLinkURL($href){
		global $core;
		$output_array=array();
		$pcre='/^(?<prefix>(?:'.addcslashes($core->blog->url,"/").')?(?:\/index.php)?)\/(?<type>[^\/]*)(?:\/(?:\/[^\/]*))?(?:\/(?<name>[^\/]*))?$/';
		preg_match($pcre, $href, $output_array);
		return $output_array;
	}

	private function solveLink($href,$id){
		global $core;
		$aUrl=$this->parseLinkURL($href);
		if(count($aUrl)==0){
			return null;		
		}
		$numlink=0;	
		switch($aUrl["type"]){
			case "category":
				$this->insertLinkCategoryChildren($id,$aUrl["name"],$aUrl["prefix"]."/");
			break;
			case "pages":
				if($aUrl["name"]=="news")
					$this->insertLinkNewsChildren($id,$aUrl["prefix"]."/");
			break;
			default:
			
		}
	}

	private function insertLinkCategoryChildren($id,$cat,$prefix){
		global $core;
		$id = (integer)$id;
		$position = 0;
		$strReq = "SELECT MAX(link_position) FROM ".$this->table." WHERE 'link_parent' = ".$id." ";
		$rs = $this->con->select($strReq);
		$position = ($rs->f(0)?$rs->f(0)+1:0);
		
		$strReq = "SELECT `post_title`,`post_url`,`post_lang`,`post_type` FROM `" . $core->prefix ."post` WHERE `cat_id` = ".
		          "(SELECT `cat_id` FROM `" . DC_DBPREFIX . "category` WHERE `cat_url` = '".
		          $this->con->escape($cat)."') and blog_id = '".$this->con->escape($this->blog->id).
		          "' and post_status = 1 and post_type " . $this->con->in(array_merge(['post','page'],explode (",",$core->blog->settings->menuportail->extra_post_types)));
                
		$rs = $this->con->select($strReq);
	
		//dcPage::warning("insertLinkCategoryChildren(".$id.",".$cat.",".$prefix.");");		
		
		while($rs->fetch()){
			$this->addLink($rs->post_title,$prefix.$rs->post_type."/".$rs->post_url,$rs->post_title,$rs->post_lang,'',$id);
		}

		$strReq = "SELECT `cat_id` FROM `" . DC_DBPREFIX . "category` WHERE `cat_url` = '".$this->con->escape($cat)."' and blog_id = '".$this->con->escape($this->blog->id)."' ";
		$rs = $this->con->select($strReq);
		$cat_id=$rs->cat_id;
		
		$rs=$core->blog->getCategories(array('start' => $cat_id,'level' => $cat_id == 0 ? 1 : 2,'without_empty' => true));
		while($rs->fetch()){
			$this->addLink($rs->cat_title,$prefix."category/".$rs->cat_url,$rs->cat_title,'','',$id);		
		}            		
	}
		
	private function insertLinkNewsChildren($id,$prefix){
		global $core;
		$id = (integer)$id;
		$position = 0;
		$strReq = "SELECT MAX(link_position) FROM ".$this->table." WHERE 'link_parent' = ".$id." ";
		$rs = $this->con->select($strReq);
		$position = ($rs->f(0)?$rs->f(0)+1:0);
		
		$strReq = "SELECT `post_title`,`post_url`,`post_lang`,`post_type`,`post_upddt` FROM `" . $core->prefix."post` WHERE `post_type` = 'post'".
		          " and blog_id = '".$this->con->escape($this->blog->id)."'".
		          " and post_selected = 1".
		          " and post_status = 1 order by post_creadt DESC" ;
		$rs = $this->con->select($strReq);

		//dcPage::warning("insertLinkNewsChildren(".$id.",".$prefix."); requête: ".$strReq);		
	
		while($rs->fetch()){
			$this->addLink($rs->post_title,$prefix."post/".$rs->post_url,$rs->post_title,$rs->post_lang,'',$id);
		}
	}

	private function clipText($text,$limit){
		if($limit==0 || strlen($text)<=$limit)
			return $text;
		else
			return substr($text,0,$limit - 3)."...";
	}
	
	public function addLink($title,$href,$desc='',$lang='', $xfn='', $parent=-1,$limit=0)
	{
		global $core;
		$cur = $this->con->openCursor($this->table);
		
		$cur->blog_id = (string) $this->blog->id;
		$cur->link_title = $this->clipText((string) $title,$limit);
		$cur->link_href = (string) $href;
		$cur->link_desc = (string) $desc;
		$cur->link_lang = (string) $lang;
		$cur->link_xfn = (string) $xfn;
		$cur->link_parent = (integer) $parent;
		
		if ($cur->link_title == '') {
			throw new Exception(__('You must provide a link title'));
		}
		
		if ($cur->link_href == '') {
			throw new Exception(__('You must provide a link URL'));
		}
		
		$strReq = 'SELECT MAX(link_id) FROM '.$this->table;
		$rs = $this->con->select($strReq);
		$cur->link_id = (integer) $rs->f(0) + 1;
		
		$cur->insert();
		$this->blog->triggerBlog();
		
		$this->solveLink($href,$cur->link_id);
	}
	
	
	public function updateLink($id,$title,$href,$desc='',$lang='', $xfn='', $parent=-1, $limit=0)
	{
		global $core;
		$this->delChildren($id);		
		
		//dcPage::warning("updateLink($id,\"$title\",\"$href\",\"$desc\",\"$lang\",\"$xfn\",$parent);");		
		
		$cur = $this->con->openCursor($this->table);
		
		$cur->link_title = $this->clipText((string) $title,$limit);
		$cur->link_href = (string) $href;
		$cur->link_desc = (string) $desc;
		$cur->link_lang = (string) $lang;
		$cur->link_xfn = (string) $xfn;
		$cur->link_parent = (integer) $parent;		
		
		if ($cur->link_title == '') {
			throw new Exception(__('You must provide a link title'));
		}
		
		if ($cur->link_href == '') {
			throw new Exception(__('You must provide a link URL'));
		}
		
		$cur->update('WHERE link_id = '.(integer) $id.
			" AND blog_id = '".$this->con->escape($this->blog->id)."'");
		$this->blog->triggerBlog();

		$this->solveLink($href,$id);
	}

	public function updateCategory($id,$desc)
	{
		$cur = $this->con->openCursor($this->table);
		
		$cur->link_desc = (string) $desc;
		
		if ($cur->link_desc == '') {
			throw new Exception(__('You must provide a category title'));
		}
		
		$cur->update('WHERE link_id = '.(integer) $id.
		" AND blog_id = '".$this->con->escape($this->blog->id)."'");
		$this->blog->triggerBlog();
	}
	
	public function addCategory($title)
	{
		$cur = $this->con->openCursor($this->table);
		
		$cur->blog_id = (string) $this->blog->id;
		$cur->link_desc = (string) $title;
		$cur->link_href = '';
		$cur->link_title = '';
		
		if ($cur->link_desc == '') {
			throw new Exception(__('You must provide a category title'));
		}
		
		$strReq = 'SELECT MAX(link_id) FROM '.$this->table;
		$rs = $this->con->select($strReq);
		$cur->link_id = (integer) $rs->f(0) + 1;
		
		$cur->insert();
		$this->blog->triggerBlog();
		
		return $cur->link_id;
	}
	
	//Deletes all items referencing $id as link_parent
        // if $id = -1 or not present,
        // deletes all the children 
	 private function delChildren($id=null){
            if($id === null){
                $strReq = 'DELETE FROM '.$this->table.' '.
			"WHERE blog_id = '".$this->con->escape($this->blog->id)."' ".
			'AND link_parent <> -1';
            }else{
                $id = (integer) $id;
                $strReq = 'DELETE FROM '.$this->table.' '.
			"WHERE blog_id = '".$this->con->escape($this->blog->id)."' ".
			'AND link_parent = '.$id.' ';
            }
            $this->con->execute($strReq);
            $this->blog->triggerBlog();	 
	 }
	
	//Deletes item and all items which reference $id as link_parent
	 public function delItem($id)
	{
		$id = (integer) $id;
		
		$strReq = 'DELETE FROM '.$this->table.' '.
				"WHERE blog_id = '".$this->con->escape($this->blog->id)."' ".
				'AND link_id = '.$id.' OR link_parent = '.$id.' ';
		
		$this->con->execute($strReq);
		$this->blog->triggerBlog();
	}
	
	public function updateOrder($id,$position)
	{
		$cur = $this->con->openCursor($this->table);
		$cur->link_position = (integer) $position;
		
		$cur->update('WHERE link_id = '.(integer) $id.
			" AND blog_id = '".$this->con->escape($this->blog->id)."'");
		$this->blog->triggerBlog();
	}
	
	private function setLinksData(&$rs)
	{
		$cat_title = null;
		while ($rs->fetch()) {
			$rs->set('is_cat',!$rs->link_title && !$rs->link_href);
			
			if ($rs->is_cat) {
				$cat_title = $rs->link_desc;
				$rs->set('cat_title',null);
			} else {
				$rs->set('cat_title',$cat_title);
			}
		}
		$rs->moveStart();
	}
	
	public function getLinksHierarchy($rs)
	{
		$res = array();
		
		foreach ($rs->rows() as $k => $v)
		{
			if (!$v['is_cat']) {
				$res[$v['cat_title']][] = $v;
			}
		}
		return $res;
	}
}
?>