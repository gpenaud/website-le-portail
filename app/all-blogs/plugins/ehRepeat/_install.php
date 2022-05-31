<?php
/* -- BEGIN LICENSE BLOCK ----------------------------------
 *
 * This file is part of ehRepeat, a plugin for Dotclear 2.
 *
 * Copyright(c) 2019 Onurb Teva <dev@taktile.fr>
 *
 * Licensed under the GPL version 2.0 license.
 * A copy of this license is available in LICENSE file or at
 * http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
 *
 * -- END LICENSE BLOCK ------------------------------------*/


if (!defined('DC_CONTEXT_ADMIN')){return;}


# Get new version
$new_version = $core->plugins->moduleInfo('ehRepeat','version');
$old_version = $core->getVersion('ehRepeat');
$eventhandler_version = $core->plugins->moduleInfo('eventHandler','version');

define('EH_REPEAT_MIN_EH_VERSION',"2018.01.25");
# Compare versions
if (version_compare($old_version,$new_version,'>=')) return;
# Install
if(version_compare($eventhandler_version, EH_REPEAT_MIN_EH_VERSION,'<'))
	throw new Exception (sprintf(__("Eh Repeat requires eventHandler V%s minimum, V%s installed. Please update"),
								  EH_REPEAT_MIN_EH_VERSION,$eventhandler_version));

# Database schema
$t = new dbStruct($core->con,$core->prefix);
$t->ehrepeat
	->rpt_id    ('bigint',0,false)
	->rpt_evt   ('bigint',0,true,null)
	->rpt_freq	('text','',true,null)
	->index('idx_ehrepeat_rpt_id','btree','rpt_id')
	->primary('pk_ehrepeat','rpt_id')
	->unique('uk_ehrepeat','rpt_id')
	->reference('fk_ehrepeat_evt','rpt_evt','eventhandler','post_id','cascade','cascade');
$t->ehrepeat_auto
	->rpt_id 	('bigint',0,false)
	->rpt_evt	('bigint',0,false)
	->index 	('idx_ehrepeat_auto','btree','rpt_id','rpt_evt')
	->primary   ('pk_ehrepeat_auto','rpt_id','rpt_evt');

$t->ehrepeat_auto->reference ('fk_ehrepeat_auto_id','rpt_id','ehrepeat','rpt_id','cascade','cascade');
$t->ehrepeat_auto->reference ('fk_ehrepeat_auto_evt','rpt_evt','eventhandler','post_id','cascade','cascade');

# Schema installation
$ti = new dbStruct($core->con,$core->prefix);
$changes = $ti->synchronize($t);

# Settings options
$s = $core->blog->settings->eventHandler;
if(!$s)
	throw new Exception(__("Eh Repeat requires eventHandler"));

$core->blog->settings->addNamespace('ehRepeat');
$s = $core->blog->settings->ehRepeat;
$s->put('rpt_active',true,'boolean','Enabled eventHandler ehrepeat addon',false,true);
$s->put('rpt_duration',6,'integer',__('Months number for automatic events generation'),false,true);
$s->put('rpt_sunday_first',true,'boolean',__('Week starts on sunday'),false,true);
$s->put('rpt_replace_enddt',true,'boolean',__('Replace event end date by event duration'),false,true);
$s->put('rpt_minute_step',5,'integer',__('Minutes accuracy'),false,true);
$s->put('last_update',"1970-01-01 00:00",'string',__('Date for last update'),false,true);
# Set version
$core->setVersion('ehRepeat',$new_version);

return true;
