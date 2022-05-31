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

class ehRepeatPublicRestMethods {

	public static function getAllRepeatEventsForCalendar($core,$get){
		$ehRepeat = new dcEhRepeat($core);
		
/*		$repeats = $ehRepeat->getEhRepeat(['sql'=>" AND post_status = 1",'sql_only'=>true]);

		echo $repeats;*/
		$repeats = $ehRepeat->getEhRepeat(['sql'=>" AND post_status = 1"]);

		$res = array();

		while($repeats->fetch()){
			$datelist = ehSimpleFreq::getDates(null,$repeats->event_startdt,$repeats->rpt_freq,$core->blog->settings->ehRepeat->rpt_duration);

			$res[] = ['url'=>$repeats->post_url,'title'=>$repeats->post_title,'dates'=>$datelist];
		}

		return $res;
	}
}


?>