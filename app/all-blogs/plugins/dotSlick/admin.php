<?php
/* 
  *  This file is part of dotSlick, a plugin for Dotclear 2.
  *  
  *  Copyright (c) 2019 Bruno Avet
  *  Licensed under the GPL version 2.0 license.
  *  A copy of this license is available in LICENSE file or at
  * http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
 */
if (!defined('DC_CONTEXT_ADMIN')) { return; }

$p_url = 'plugin.php?p='.basename(dirname(__FILE__));
$settings = &$core->blog->settings->dotslick;
$debug="";

//Getting notifications after save refresh
if(isset($_GET['success'])){
    dcPage::addSuccessNotice($_GET['success']);
}
if(isset($_GET['warning'])){
    dcPage::addSuccessNotice($_GET['warning']);
}
if(isset($_GET['error'])){
    dcPage::addSuccessNotice($_GET['error']);
}


//Saving galleries preferences
if (isset($_POST['save']))
{
    $req = $_REQUEST;
    $savedoptions = dotSlickAdmin::saveOptions($req);
    
    header('Location: '.$p_url.'&success='.__('Galleries options successfully saved'));
}

//Saving plugin preferences
if(isset($_POST['saveps']))
{
    $settings->put('dotslick_enabled',$_POST['enable'],'boolean');
    $delims = [' ',',',';','/','|'];
    $post_types=[];
    preg_match_all("/([^,;\s\/\|]*)(?:[,;\s\/\|])?/",$_POST['post_types'],$post_types);
    array_shift($post_types);
    
    foreach($post_types[0] as $k=>$v){
        if($v === ""){ //regexp adds an empty match in the end
            unset($post_types[0][$k]); 
        }
    }
    
    if(!in_array('post', $post_types[0])){
        array_unshift($post_types[0],'post');
    }
    
    $settings->put('post_types',join(' ',$post_types[0]),'string');
    
    header('Location: '.$p_url.'&tab=tab-3'.'&success='.__('Plugin settings successfully saved'));
}

if(isset($_POST['regenerate']) || isset($_POST["regenerateall"])){
    if(isset($_POST['regenerate'])){
        $galleries = $_POST['galleries'];
    }else{
        $dsg = dsGalleries::get();
        $galleries = array_map(function($g){
            return $g["id"];
        },$dsg);
    }
    $ret=true;
    $counter=0;
    $failcount=0;
    foreach($galleries as $g){
        $nbgals=0;
        $ret&=dsGalleries::regenerate((integer)$g,$nbgals);
        if($ret)$counter+=$nbgals;
        else $failcount++;
    }
    if($counter>0){
        $p_url.='&success='.sprintf(__("%d gallery has been regenerated","%d galleries have been regenerated",$counter),$counter);
    }
    if($failcount>0){
        $p_url.='&warning='.sprintf(__("%d gallery was not regenerated","%d galleries were not regenerated",$failcount),$failcount);
    }
    header('Location: '.$p_url.'&tab=tab-2');
}

$options = dotSlickAdmin::extractOptions($settings);




$psoptions =[];
$psoptions["enable"]=$settings->dotslick_enabled;
$psoptions["post_types"]=$settings->post_types;


if($psoptions["enable"]===false){
    $default_tab = 'tab-3';
}else{
    $default_tab = 'tab-2';
}
 
if (isset($_REQUEST['tab'])) {
	$default_tab = $_REQUEST['tab'];
}

$galleries = dsGalleries::get();

////retrieve all posts & pages holding galeries
//$req = " AND (post_excerpt LIKE '%::dotslick%' or post_content LIKE '%::dotslick%')";
//$post_types= explode(' ',$psoptions['post_types']);
//$rs = $core->blog->getPosts(['where'=>$req,'post_type'=>$post_types]);
//$galleries = [];
//while($rs->fetch()){
//    $gallery=[];
//    $gallery["id"]=$rs->post_id;
//    $gallery["title"]=$rs->post_title;
//    $gallery["url"]= $core->blog->url . $core->getPostPublicURL($rs->post_type,$rs->post_url);
//   
//    $gallery["adminurl"] = $core->getPostAdminURL($rs->post_type,$rs->post_id);
//
//    $html=$rs->post_content . $rs->post_excerpt;
//    $gallery["count"]=preg_match_all("/::dotslick/m",$html);
//    $galleries[]=$gallery;
//}

include dirname(__FILE__).'/tpl/admin.tpl';




