<?php
# ***** BEGIN LICENSE BLOCK *****
# This file is part of DotClear.
# Copyright (c) 2007 Olivier Meunier and contributors. All rights
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
if (!defined('DC_CONTEXT_ADMIN')) { exit; }

$version = $core->plugins->moduleInfo('menuPortail','version');

if (version_compare($core->getVersion('menuPortail'),$version,'>=')) {
	return;
}

/* Database schema
-------------------------------------------------------- */
$s = new dbStruct($core->con,$core->prefix);

/*
	Structure de la base de données :
	link_id : n° du lien
	blog_id
	link_href : url du lien
	link_title : texte du lien
	link_desc : description du lien
	link_lang : langue du lien
	link_xfn : aucune idée
	link_position : position du lien à son niveau (racine, catégorie...)
	link_parent : pour les sous-liens, id du lien parent
*/

$s->menu
	->link_id		('bigint',	0,	false)
	->blog_id		('varchar',	32,	false)
	->link_href		('varchar',	255,	false)
	->link_title	('varchar',	255,	false)
	->link_desc		('varchar',	255,	true)
	->link_lang		('varchar',	5,	true)
	->link_xfn		('varchar',	255,	true)
	->link_position	('integer',	0,	false,	0)
	->link_parent   ('integer', 0,true,-1)
	->primary('pk_menu','link_id')
	;

$s->menu->index('idx_menu_blog_id','btree','blog_id');
# verifier cette ligne
$s->menu->reference('fk_menu_blog','blog_id','blog','blog_id','cascade','cascade');

# Schema installation
$si = new dbStruct($core->con,$core->prefix);
$changes = $si->synchronize($s);


# Settings options
$core->blog->settings->addNamespace('menuportail');
$settings = $core->blog->settings->menuportail;
$settings->put('active',false,'boolean',__('Enable MenuPortail add on'),false,true);
$settings->put('extra_post_types','','string',__('Extra post types to include in menus'),false,true);

$core->setVersion('menuPortail',$version);
return true;
?>