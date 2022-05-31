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


if ( ! function_exists( 'array_key_last' ) ) {
	function array_key_last($array){
		$key = NULL;
		if ( is_array( $array ) ) {
			end( $array );
	    	$key = key( $array );
	    }
	    return $key;
	}
}

function printCal($calendrier,$data){

	walkCalendrier($calendrier,
		function($cal,$mon,$year,$wd,$wom,$empty,$data){ //cb_cell
			if($empty){
				return "    ";
			}else{
				$freq=$data["freq"];
				$mday = $cal[$wom][$wd];
				$f=ehDate::get1stWeekDayOfMonth(sprintf("%4s-%02d-%02d 12:00:00",$year,$mon,$mday));

				//TODO : vérifier si la cellule répond à la fréquence et si oui, afficher des * dans la cellule

				//we have a mday here!
				return str_pad($mday,4," ",STR_PAD_BOTH);
			}
		},
		function($data){	//cb_head
			return array("    "," 1  "," 2  "," 3  "," 4  "," 5  ");
		},
		function($wd,$data){ //cb_rowtitles
			static $days=[1=>"L",2=>"M",3=>"m",4=>"J",5=>"V",6=>"S",7=>"D"];
			return str_pad($days[$wd],4," ",STR_PAD_BOTH);
		},
		function($year,$mon,$data){
				},
		function($cal,$ligne,$data){
			$numcol=$data['occupied']?6:5;
			if($ligne==0 || $ligne==1)
								if($ligne==7){
									}
		},$data);
}



/*$calendrier[year][mon][wd][wom]
$cb_cell : function ($cal,$wd,$wom,$empty) => string;
$cb_head : function () => array;
$cb_title: function ($year,$mon) => string;
$cb_rowtitles($wd)

$cb_printline : function ($ligne) =>string;*/
function walkCalendrier($calendrier,$cb_cell,$cb_head,$cb_rowtitles,$cb_title,$cb_printline,$data){

	foreach($calendrier as $year=>$year_cal){
		foreach($year_cal as $mon=>$mon_cal){
			$cal=array();
			$cb_title($year,$mon,$data);
			for($wd=1;$wd<=7;$wd++){
				if(!array_key_exists($wd, $cal))
					$cal[$wd]=array($cb_rowtitles($wd,$data));
				for($wom=1;$wom<=5;$wom++){
					if(!array_key_exists($wom,$cal[$wd])){
						if($wom==1){
							$cal[0]=$cb_head($data);
						}
						$cal[$wd][$wom]=array();
					}
					if(array_key_exists($wom,$mon_cal) && array_key_exists($wd, $mon_cal[$wom])){
						$cal[$wd][$wom] = $cb_cell($mon_cal,$mon,$year,$wd,$wom,false,$data);
					}else{
						$cal[$wd][$wom] = $cb_cell($mon_cal,$mon,$year,$wd,$wom,true,$data);
					}
				}
			}

			/*Suppression de la 5ème colonne si elle est vide*/
			$col5 = array_column($cal,5);
			$occupied=false;
			foreach($col5 as $i=>$cell){
				if($i>1 && $cell!="    "){
					$occupied=true;
					break;
				}
			}
			if(!$occupied){
				foreach($cal as $wd => $ligne){
					array_pop($cal[$wd]);
				}
			}
			/*Fin de suppression*/

			for($ligne=0;$ligne<=7;$ligne++){
				$cb_printline($cal,$ligne,array_merge(["occupied"=>$occupied],$data));
			}
			unset($cal);
		}
	}
}



function checkResults ($num,$basedate,$freq){
	$oFreq=new ehSimpleFreq($freq);
	$oBaseDate=new ehDate($basedate);

	$calendrier=array();

	$mday = $mday_start = $oBaseDate->mday;
	$mon = $mon_start = $oBaseDate->mon;
	$year = $year_start = $oBaseDate->year;
	$wd = $wd_start = $oBaseDate->wd;
	$wom = $wom_start = $oBaseDate->wom;

	$i=$num+5;

	while($num>0){
		if($i--==0)break; // fuse!

		if(!array_key_exists($year,$calendrier))
			$calendrier[$year]=array();
		if(!array_key_exists($mon,$calendrier[$year]))
			$calendrier[$year][$mon]=array();
		for($wom;$wom<=6;$wom++){
			$calendrier[$year][$mon][$wom]=array();
			for($wd;$wd<=7;$wd++){
				if(checkdate($mon, $mday, $year)){
					$calendrier[$year][$mon][$wom][$wd]=$mday++;
				}else{
					$mday=1;
					$mon=($mon<12)?$mon+1:1;
					$year=($mon==1)?$year+1:$year;
					break(2);
				}
			}
			$wd=1;
		}
		$wom=1;
		$num--;
	}
	printCal($calendrier,['num'=>$num,'basedate'=>$basedate,'freq'=>$freq]);
}