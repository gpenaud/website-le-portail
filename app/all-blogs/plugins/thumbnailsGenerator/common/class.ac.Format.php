<?php
# ***** BEGIN LICENSE BLOCK *****
# This file is part of a commun lib for some DotClear plugin.
# Copyright (c) 2010 Anne-Cécile Calvot. All rights reserved.
#
# Licensed under the GPL version 2.0 license.
# See LICENSE file or
# http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
#
# ***** END LICENSE BLOCK *****

class spFormat {
	private $id;
	private $name;
	private $user_expr;
	private $origin_name_place;

	public function __construct($id,$name,$user_expr,$origin_name_place)
	{
		$this->id = $id;
		$this->name = $name;
		$this->setUserExpr($user_expr);
		$this->origin_name_place = $origin_name_place;
	}

	public function getId() {
		return $this->id;
	}

	public function getName() {
		return $this->name;
	}

	public function getUserExpr() {
		return $this->user_expr;
	}
	
	protected function setUserExpr($user_expr) {
		$this->user_expr = $user_expr;
	}

	public function getNumPlaceOfOriginName() {
		return $this->origin_name_place;
	}
}
?>
