<?php
# -- BEGIN LICENSE BLOCK ----------------------------------
#
# This file is part of EHRepeat, an extension of eventHandler
# for dotclear 2
#
# (c)2019 Nurbo Teva for Association Du Grain à Moudre
#
# Licensed under the GPL version 2.0 license.
# A copy of this license is available in LICENSE file or at
# http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
#
# -- END LICENSE BLOCK ------------------------------------

if (!defined('DC_RC_PATH')){return;}


class rsExtEhRepeatPublic extends rsExtPost
{
	public static function isRepeat($rs){
		return ($rs->rpt_freq != null);
	}

	public static function getReadableFreq($rs){
		if($rs->isRepeat()){
	 		$freq = new ehSimpleFreq($rs->rpt_freq);
 			return $freq->toString();
 		}
 		return "";
 	}

 	public static function getMasterId($rs){
 		global $core;
 		if(!$rs->isRepeat()){
 			return null;
 		}
 		$mreq="SELECT R.rpt_id, A.rpt_evt, R.rpt_evt as rpt_master from ".$core->prefix."ehrepeat_auto A left outer join ".$core->prefix."ehrepeat R using(rpt_id) where A.rpt_evt = ".$rs->post_id." UNION select rpt_id, rpt_evt, rpt_evt as rpt_master from ".$core->prefix."ehrepeat where rpt_evt = ".$rs->post_id  ;
 		try{
 			$mrs = $core->con->select($mreq);
 			if($mrs->count() == 1 ){
 				return $mrs->rpt_master;
 			}else{
 				throw new Exception('$mrs->rpt_master != 1 for post_id = '.$rs->post_id."\n".'$mreq = '.$mreq );
 			}
 		}catch(Exception $e){
 			throw($e);
 		}

 	}

 	public static function getMasterURL($rs){
 		global $core;
 		if(!$rs->isRepeat()){
 			return "/day/".$rs->post_url;
 		}
 		$master_id = $rs->getMasterId();
 		$prs=$core->blog->getPosts(['post_type'=>['ehrepeat','eventhandler'],'post_id'=>$master_id]);

		return "/day/".$prs->post_url;
 	}

}







?>