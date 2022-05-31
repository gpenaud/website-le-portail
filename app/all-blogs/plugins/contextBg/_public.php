<?php
# -- BEGIN LICENSE BLOCK ----------------------------------
#
# This file is part of contextBG a plugin for Dotclear 2.
# 
# Copyright (c) 2015 Onurbruno
# Licensed under the GPL version 2.0 license.
# A copy of this license is available in LICENSE file or at
# http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
#
# -- END LICENSE BLOCK ------------------------------------

if (!defined('DC_RC_PATH')) { return; }

$core->addBehavior('publicHeadContent',array('publicContextBGBehavior','headContent'));

define("CBG_DEBUG", true);
define("CBG_MARK", true);

function mark($text)
{
	if(!CBG_MARK)return;
	echo "<!-- contextBg ".$text."-->\n";
}

function debug($v,$text="")
{
	if(!CBG_DEBUG)return;
	echo "<!-- contextBg debug -->";
	echo "<pre>\n$text";
	print_r($v);
	echo "</pre>";
	echo "<!-- /contextBg debug -->";
}

class publicContextBGBehavior{
	
	public static function headContent($core,$_ctx)
	{	
		global $core;	
		$settings=$core->blog->settings->contextBg;
		if($settings->excludehome && ($_ctx->current_tpl == "home.html"))
		{
			mark("in home.html template, exclusion requested. We skip");
			return;		
		}

		$selector=base64_decode($settings->selector);
		$pattern=base64_decode($settings->pattern);
		$multipattern=base64_decode($settings->multipattern);
		$default=base64_decode($settings->default);
		$field=base64_decode($settings->field);
		$extracss=base64_decode($settings->css);
		$csslines=preg_split("/[\r\n\v;$^]+/m",$extracss,NULL,PREG_SPLIT_NO_EMPTY);
		$css="";
		foreach($csslines as $line)
		{
			$l=explode(': ',$line);
//			$css.="$({$selector}).css('{$l[0]}','{$l[1]}');\n\t\t";
			$css.="{$l[0]}:{$l[1]};\n\t\t";
		}
		$val="";
		
		if($_ctx->exists('categories') && $_ctx->categories->exists($field))
			$val=$_ctx->categories->{$field};
		else if($_ctx->exists('posts') && $_ctx->posts->exists($field))
			$val=$_ctx->posts->{$field};
		else 
			echo "<!-- contextBG Error : can't find field $field. -->\n";

		$bg_filenames=array();
		for($i=0;;$i++){
			if($i==0){
//loading unique image as first element in the array
				$name=$core->blog->settings->system->public_url.'/'.sprintf($pattern,$val);
				$fsname=DC_ROOT.'/'.$core->blog->settings->system->public_path.'/'.sprintf($pattern,$val);
			}else{
				$name=$core->blog->settings->system->public_url.'/'.sprintf($multipattern,$val,$i);
				$fsname=DC_ROOT.'/'.$core->blog->settings->system->public_path.'/'.sprintf($multipattern,$val,$i);
			}
			if(file_exists($fsname)){
				$bg_filenames[] = "'".$name."'";			
			}else if($i>0){
				break;
			}
		}

		
		if(count($bg_filenames)==0)
		{
			echo "<!-- contextBG : \$bg_filenames undefined, reverting to default -->\n";
			$default_filename=$core->blog->settings->system->public_url.'/'.$default;
			$fs_default_filename=DC_ROOT.'/'.$core->blog->settings->system->public_path.'/'.$default;
			if(file_exists($fs_default_filename))
				$bg_filenames[] = "'".$default_filename."'";
			else {
				echo "<!-- contextBG Error : {$fs_default_filename} undefined -->";
				return;
			}
		}
		$js_filenames="var bg_filenames=[".join(",\n\t\t\t",$bg_filenames)."]";
		echo <<<EOF
<!-- contextBG -->
<script>
	{$js_filenames};
	var selector="{$selector}";
</script>
<script src="{$core->blog->getQmarkURL()}pf=contextBg/contextBg.js"></script>
<style>
	/*<![CDATA[*/
	{$selector}{
		{$css}
	}
	/*]]>*/
</style>		
<!-- /contextBG -->

EOF;
	}
}