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

if (!defined('DC_RC_PATH')) {
    return;
}

global $__autoload, $core;


$__autoload['dcEhRepeat'] = dirname(__FILE__) . '/inc/class.dcehrepeat.php';
$__autoload['ehDate'] = dirname(__FILE__) . '/inc/class.ehdate.php';
$__autoload['ehSimpleFreq'] = dirname(__FILE__) . '/inc/class.ehsimplefreq.php';
$__autoload['ehRepeatRestMethods'] = dirname(__FILE__) . '/_services.php';
$__autoload['rsExtEhRepeatPublic'] = dirname(__FILE__) . '/inc/lib.ehrepeat.rs.extension.php';


# parsefreq rest method  (for ajax service)
$core->rest->addFunction('freqToString', array('ehRepeatRestMethods', 'freqToString'));
$core->rest->addFunction('computeDates', array('ehRepeatRestMethods', 'computeDates'));
$core->rest->addFunction('checkCal', array('ehRepeatRestMethods', 'checkCal'));


$core->addBehavior('adminPageHTMLHead', 'refreshEhRepeat');

function refreshEhRepeat() {
    global $core;

    $tmp = new dcEhRepeat($core);
    unset($tmp);
    return ('<!-- ehRepeat -->');
}

$core->addBehavior('coreEventHandlerBeforeGetEvents', 'coreEventHandlerBeforeGetEvents');

/* coreEventHandlerBeforeGetEvents :
  modifies eventHandler->getEvents results by adding :
  - rpt_freq column, which is NULL if not applicable
  - filtering by rpt_id : returns only events which are referenced by rpt_id in $params['rpt_id']
  - filtering by title : returns only events whose titles matches the $params['by_title'] string(s)
  - filtering is_event : returns only events who are referenced by a rpt_id
 */

function coreEventHandlerBeforeGetEvents($eh, $params) {
    global $core;
    //On ajoute systématiquement rpt_freq pour tous les eventHandler.getEvent
    $p = &$params['params'];
    $p['columns'][] = 'R.rpt_freq';
    $p['columns'][] = 'R.rpt_id';
    $p['columns'][] = 'R.rpt_evt';

    if (!isset($p['sql']))
        $p['sql'] = '';
    if (!isset($p['where']))
        $p['where'] = '';
    
    $from = ' LEFT OUTER JOIN ' . $core->prefix . 'ehrepeat R ON EH.post_id = R.rpt_evt';

    if (isset($p['rpt_id']) && $p['rpt_id']) {
        $p['sql'] .= " AND R.rpt_id " . prepareParams($p, 'rpt_id');
        unset($p['rpt_id']);
    }

    if (isset($p['is_repeat']) && $p['is_repeat']) {
        $p['sql'] .= " AND R.rpt_freq IS NOT NULL";
    }

    if (isset($p['by_title'])) {
        $p['post_type'] = ['eventhandler', 'ehrepeat'];
        if(!is_array($p['by_title'])){
            $by_title = " AND P.post_title LIKE '".$p['by_title']."'";
        }else{
            array_walk($p['by_title'],function(&$v,$i,$p){
                $v = $p."'".$v."'";
            },'P.post_title LIKE ');
            $by_title=' AND ('.join(" OR ",$p['by_title']).')';            
        }
        $p['where'] .= $by_title;
        unset($p['by_title']);
    }

    if (isset($p['master_event_id'])) {
        $p["post_type"] = ['ehrepeat'];
        $from = ' LEFT OUTER JOIN ' . $core->prefix . 'ehrepeat_auto A ON EH.post_id = A.rpt_evt LEFT OUTER JOIN ' . $core->prefix . 'ehrepeat R ON A.rpt_id = R.rpt_id';
        $p['sql'] .= ' AND R.rpt_evt = ' . $p['master_event_id'];
        unset($p['master_event_id']);
    }

    if (isset($p['siblings_from_id'])) {
        $p['post_type'] = ['ehrepeat'];
        $from = ' LEFT OUTER JOIN (SELECT rpt_id,rpt_evt FROM ' . $core->prefix . 'ehrepeat_auto UNION SELECT rpt_id,rpt_evt FROM ' . $core->prefix . 'ehrepeat) A ON P.post_id = A.rpt_evt INNER JOIN (SELECT rpt_id,rpt_evt FROM ' . $core->prefix . 'ehrepeat_auto UNION SELECT rpt_id,rpt_evt FROM ' . $core->prefix . 'ehrepeat) B ON A.rpt_id = B.rpt_id  LEFT OUTER JOIN ' . $core->prefix . 'ehrepeat R ON R.rpt_id = B.rpt_id ';
        $p['sql'] .= ' AND B.rpt_evt ' . prepareParams($p, 'siblings_from_id');
        $p['order'] = 'EH.event_startdt ASC';
        unset($p['siblings_from_id']);
    }

    if (isset($p['all'])) {
        $p['post_type'] = ['ehrepeat','eventhandler'];
        $from = ' LEFT OUTER JOIN (SELECT A.rpt_evt, A.rpt_id, R.rpt_evt as rpt_master, R.rpt_freq from ' . $core->prefix . 'ehrepeat_auto A LEFT OUTER JOIN ' . $core->prefix . 'ehrepeat R on R.rpt_id = A.rpt_id UNION SELECT rpt_evt, rpt_id, rpt_evt as rpt_master, rpt_freq from ' . $core->prefix . 'ehrepeat) as R ON R.rpt_evt = P.post_id ';
        $p['order'] = 'EH.event_startdt ASC';
        unset($p['all']);
    }
    
    if (isset($p['event_interval']) && $p['event_interval']=='of'){
//        $p['post_type'] = ['eventhandler', 'ehrepeat'];
        $sql = explode("AND",$p['sql']);
        unset ($sql[1]);
        $p['sql'] = join("AND",$sql);
        $prefix=DC_DBPREFIX;
        $p['sql'] .= " OR P.post_id in (SELECT distinct R.rpt_evt FROM `${prefix}post` AS P LEFT JOIN ${prefix}ehrepeat_auto AS EA ON EA.rpt_evt = P.post_id INNER JOIN ${prefix}eventhandler EH ON  EH.post_id = P.post_id INNER JOIN ${prefix}ehrepeat R on EA.rpt_id = R.rpt_id WHERE EA.rpt_id IS NOT NULL ". $p['sql']. " AND P.post_status = 1) ";
    }

    $p['from'] .= $from;
}

$core->addBehavior('coreEventHandlerGetEvents', 'coreEventHandlerGetEvents');

function coreEventHandlerGetEvents($rs) {
    if (is_string($rs)) // case of $params['sql_only'] == true
        return;
    $rs->extend('rsExtEhRepeatPublic');
}

$core->addBehavior('coreEventHandlerBeforeEventAdd', 'coreEventHandlerBeforeEventAdd');

function coreEventHandlerBeforeEventAdd($eh, $cur_post, $cur_event) {
    
}

?>