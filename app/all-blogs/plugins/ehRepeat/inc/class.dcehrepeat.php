<?php
/* -- BEGIN LICENSE BLOCK ----------------------------------
 *
 * This file is part of ehRepeat, a plugin for Dotclear 2.
 *
 * Copyright(c) 2019 Nurbo Teva <dev@taktile.fr>
 *
 * Licensed under the GPL version 2.0 license.
 * A copy of this license is available in LICENSE file or at
 * http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
 *
 * -- END LICENSE BLOCK ------------------------------------*/

 class dcEhRepeat {
 	public $core;
	public $con;

	protected $table;
	protected $table_auto;
	protected $blog;
	protected $eh;
	protected $ehr;

	public $outdated;

 	public function __construct($core){
		$this->core = $core;
		$this->con = $core->con;
		$this->table = $core->prefix.'ehrepeat';
		$this->table_auto = $core->prefix.'ehrepeat_auto';		
		$this->blog = $core->con->escape($core->blog->id);

		$this->ehr = new eventHandler($core,'ehrepeat'); //modified eh

		$this->eh = new eventHandler($core); //regular eh

		$this->outdated = $this->checkOutdated();

		if($this->outdated && defined('DC_CONTEXT_ADMIN')) {
			$this->refreshEvents();
		}
 	}

 	//Contrôle si la dernière mise à jour remonte à plus de 24 h
 	private function checkOutdated(){
 		$today = time();
 		$last_update = ehDate::convertDate($this->core->blog->settings->ehRepeat->last_update);
 		return ($last_update<($today-86400));
 	}

 	//Rafraîchit les événements répétitifs : vide puis régénère la liste des eventhandlers avec post_type='ehrepeat'
 	protected function refreshEvents(){
		$res = $this->eh->getEvents(['post_type'=>'ehrepeat']);
		while($res->fetch()){
			$this->eh->delEvent($res->post_id);
		}
		$this->flushEhRepeatAuto();


		$repeat = $this->eh->getEvents(['is_repeat'=>true]);

		while($repeat->fetch()){
			$dateslist = ehSimpleFreq::getDates(null, $repeat->event_startdt, $repeat->rpt_freq, 
												$this->core->blog->settings->ehRepeat->rpt_duration, ehDate::T_TIMESTAMP);
			foreach($dateslist as $k=>$v){
				$startdt=date('Y-m-d H:i:00',$v);
				$enddt=date('Y-m-d H:i:00',$v+(strtotime($repeat->event_enddt) - strtotime($repeat->event_startdt)));
				$this->cloneEvent($repeat->rpt_evt,['event_startdt'=>$startdt,'event_enddt'=>$enddt]);
			}
		}
		$this->core->blog->settings->ehRepeat->put('last_update',date('Y-m-d H:i:00'),'string');
		$this->warning(__("All repetitive events have been generated."));
 	}


 	public function getEvents($params){
 		return $this->eh->getEvents($params);
 	}


 	/* Retourne un record des descripteurs d'événements répétitifs soit
 	   par rpt_id, soit en recherchant les event_id*/ 
 	public function getEhRepeat($params){
 		$strreq="";
 		$where="";
 		if(isset($params['sql'])){
 			$where.=$params['sql'];
 		}

 		/*Recherche des ehRepeat par événement*/


 		if(isset($params['event_id'])){
 			if (is_array($params['event_id'])) {
                array_walk($params['event_id'], function (&$v, $k) {if ($v !== null) {$v = (integer) $v;}});
            }
 			$strreq = "SELECT rpt_id, rpt_evt, rpt_freq FROM ". $this->table. " WHERE rpt_evt " . $this->con->in($params['event_id']).";";
 		}elseif(isset($params['rpt_id'])){
 			if (is_array($params['rpt_id'])) {
                array_walk($params['rpt_id'], function (&$v, $k) {if ($v !== null) {$v = (integer) $v;}});
            } 			
 			/*Recherche l'ehRepeat par rpt_id*/
 			$strreq = "SELECT rpt_id, rpt_evt, rpt_freq FROM ". $this->table ." where rpt_id " . $this->con->in($params['rpt_id']).";";
 		}else{
 			/*Retourne tous les ehRepeat*/
 			$strreq = "SELECT P.post_id, P.post_title, P.post_type, P.post_url, EH.event_startdt, EH.event_enddt, R.rpt_id, R.rpt_freq from ".$this->core->prefix. "post as P INNER JOIN (".$this->table." R, ". $this->core->prefix."eventhandler EH) on R.rpt_evt = EH.post_id AND EH.post_id = P.post_id ".$where." ORDER BY R.rpt_id";
 		}

 		if(isset($params['sql_only'])){
 			return $strreq;
 		}
 		
		try{
			$rs = $this->con->select($strreq);
			return $rs;
		}catch(Exception $e){
			$this->warning($e);
		}
 	}


 	/*Pas propre, refaire avec CURSOR*/
 	public function add_repeat($event_id,$freq){
 		$strreq="";
 		$lastid=-1;
 		$ret=null;
 		try{
 			$rs=$this->eh->getEvents(['post_id'=>$event_id]);
 			$xfreq = new ehSimpleFreq($freq);
 			if($xfreq === null)
 				throw new Exception(sprintf(__("«%s» is not a regular frequency string (for %d)"),$freq,$event_id));
 			if(!$xfreq->dateValid($rs->event_startdt))
 				throw new Exception(sprintf(__("The start date of the event %s doesn't match the frequency string «%s»."),$rs->event_startdt,$xfreq->toString()));
 			$rs=$this->con->select("SELECT MAX(rpt_id) FROM ".$this->table);
 			$lastid=(int)$rs->f(0)+1;
 		}catch(Exception $e){
 			$this->error($e);
 			return false;
 		}
 		$strreq="INSERT INTO " . $this->table . " (rpt_id,rpt_evt,rpt_freq) VALUES ( " . 
 			$lastid ."," . $event_id .",'" . $this->con->escape($freq) . "');";
 		try{
 			$ret = $this->con->execute($strreq);
 		}catch(Exception $e){
 			$this->error($e->getMessage()."<br/> request : $strreq");
 			return false;
 		}
 		$this->refreshEvents();
 		return $ret;
 	}

 	public function update_repeat($rpt_id,$event_id,$freq){
 		$ret=null;
 		$strreq = "UPDATE " . $this->table . " SET rpt_evt = " . $event_id . ", rpt_freq = '" . $this->con->escape($freq) . "' WHERE rpt_id = " . $rpt_id . ";";
 		try{
 			$rs=$this->eh->getEvents(['post_id'=>$event_id]);
 			$xfreq = new ehSimpleFreq($freq);
			if($xfreq === null)
 				throw new Exception(sprintf(__("«%s» is not a regular frequency string (for %d)"),$freq,$event_id));
 			if(!$xfreq->dateValid($rs->event_startdt))
 				throw new Exception(sprintf(__("The start date of the event %s doesn't match the frequency string «%s»."),$rs->event_startdt,$xfreq->toString()));
 			$ret = $this->con->execute($strreq);
 		}catch(Exception $e){
 			$this->error($e->getMessage()."<br/> request :$strreq");
 			return false;
 		}	
 		$this->refreshEvents();
 		return $ret; 		
 	}

 	public function is_repeat($event_id){
 		$res=$this->eh->getEvents(["event_id"=>$event_id]);
 		return !$res->isEmpty();
 	}

 	public function delete($id){
 		$ret=null;
 		$strreq1="DELETE FROM ".$this->table_auto." WHERE rpt_id = ".(int)$id.";";
 		$strreq2="DELETE FROM ".$this->table." WHERE rpt_id = ".(int)$id.";";
 		try{
 			$ret = ( $this->con->execute($strreq1) && $this->con->execute($strreq2));
 		}catch(Exception $e){
 			$this->error($e." dcEhRepeat->delete($id) :\nrequest :\n$strreq1\n$strreq2");
 			return false;
 		}
 		$this->refreshEvents();
 		return $ret; 		
 	}

 	public function delete_by_event($evt_id){
 		$strreq1 = "SELECT rpt_id FROM ".$this->table." WHERE rpt_evt = " . (int)$evtid . ";";
 		$res=$this->con->select($strreq1);
 		$ret = false;
 		if(!$res->isEmpty())
 			$ret = $this->delete($res->rpt_id);
 		$this->refreshEvents();
 		return $ret;
 	}

 	public static function getReadable($s_freq){
 		$freq = new ehSimpleFreq($s_freq);
 		return $freq->toString();
 	}

 	public function error($e){
 		if($e instanceof Exception){
 			$message=$e->getMessage();
 		}else{
 			$message=$e;
 		}
 		dcPage::addErrorNotice("EhRepeat : ".$message);
 	}

 	public function warning($e){
 		if($e instanceof Exception){
 			$message=$e->getMessage();
 		}else{
 			$message=$e;
 		}
 		dcPage::addWarningNotice("EhRepeat : ".$message);
 	}

 	
 	/*
 		this method converts an event in the regular eventhandler table into 
 		 an event into the local eventhandler table (event_type=ehrepeat),
 		 assigning the fields specified in $out_fields, generally ['event_startdt'=>value,'event_enddt'=>value]
 	*/

 	protected function cloneEvent($event_id,$out_fields=[]){
 		$rs=$this->eh->getEvents(['event_id'=>$event_id]);
 		if($rs->isEmpty())
 			return -1;

		$cur_post = $this->core->con->openCursor($this->core->prefix.'post');

		foreach(['cat_id','post_dt','post_format','post_password','post_lang','post_title','post_excerpt',
				 'post_excerpt_xhtml','post_content','post_content_xhtml','post_notes','post_status','post_selected',
				 'user_id','post_open_comment','post_open_tb'] as $f){
			$cur_post->{$f} = (array_key_exists($f, $out_fields)?$out_fields[$f]:$rs->{$f});
		}

		$num=$this->eh->getEvents(['where'=>' AND post_title = "'.$this->con->escape($rs->post_title).'"','post_type'=>'ehrepeat'],true)->f(0);

		$cur_post->post_url = $rs->post_url;
		$cur_post->post_dt = date('Y-m-d H:i:00',time());
		$cur_post->post_type = 'ehrepeat';

		$cur_event = $this->core->con->openCursor($this->core->prefix.'eventhandler');
		foreach(['event_startdt','event_enddt','event_address','event_latitude','event_longitude','event_zoom'] as $f){
			$cur_event->{$f} = (array_key_exists($f, $out_fields)?$out_fields[$f]:$rs->{$f});
		}

		try{
			$post_id = $this->eh->addEvent($cur_post,$cur_event);
			$this->createEhRepeatAuto($rs->rpt_id,$post_id);

		}catch(Exception $e){
			$this->error($e);
			return null;
		}
 	}

 	public function createEhRepeatAuto($rpt_id,$rpt_evt){
		$cur_ehrepeat_auto = $this->core->con->openCursor($this->table_auto);
		$cur_ehrepeat_auto->rpt_id=(integer)$rpt_id;
		$cur_ehrepeat_auto->rpt_evt=(integer)$rpt_evt;
		$this->addEhRepeatAuto($cur_ehrepeat_auto);
 	}

 	protected function addEhRepeatAuto($cur){
        $this->con->writeLock($this->table_auto);
        try
        {
    	    $cur->insert();
            $this->con->unlock();
        }catch(Exception $e){
            $this->con->unlock();
            throw $e;
        }
 	}

 	protected function flushEhRepeatAuto(){
        $this->con->writeLock($this->table_auto);
        try
        {
            $this->con->execute("TRUNCATE ".$this->table_auto);
            $this->con->unlock();
        }catch(Exception $e){
            $this->con->unlock();
            throw $e;
        }
 	}
}

function prepareParams(&$params,$param,$is_num=true){
		global $core;
		if (is_array($params[$param])) {
			if($is_num)array_walk($params[$param],create_function('&$v,$k','if($v!==null){$v=(integer)$v;}'));
		} else {
			$params[$param] = array((integer) $params[$param]);
		}
		return $core->con->in($params[$param]);
	}

