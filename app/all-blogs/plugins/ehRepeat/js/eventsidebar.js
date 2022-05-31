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

$(document).ready(function(){
	/*Ouverture de la fenêtre modale*/
	$("#ehrepeat fieldset,#ehrepeat fieldset *").click(function(){
		prepareEhRepeatModal();
		$("#ehrepeatmodal").show();
	});

	/*Validation de la fenêtre modale, mise à jour de la 
	 chaine de fréquence et fermeture de la fenêtre modale*/

	$("#ehr_validate").click(function(){
		var freq="";
		var wday=new Array();
		$("#ehr_wday option:selected").each(function(i,e){
			wday.push($(this).val());
		});
		var wom=new Array();
		$("#ehr_wom option:selected").each(function(i,e){
			wom.push($(this).val());
		});
		
		freq += wday.toString() + ":" + wom.toString();

		$("#rpt_freq").val(freq);
		updateFreqDesc(freq);
		$("#ehrepeatmodal").hide();
		$("#rpt_dates_num").change();	
	});

	/*Gestion du click sur reset*/

	$("#ehr_reset").click(function(){
		prepareEhRepeatModal();
	});

	/*Gestion de la pression de la touche échap pour 
	  fermer la fenêtre modale*/

	$(document).keydown(function(event){
		if(event.which == 27){
			$("#ehrepeatmodal").hide();
		}
	});

	$("#ehrepeatmodal>div").click(function(){
		$("#ehrepeatmodal").hide();
	});

	$("#ehrepeatmodal>div>*").click(function(event){
		event.stopPropagation();
	});

	/*Fonctions gérant l'utilisation de l'* dans les listes : lorsque * est sélectionné, 
	  toutes les autres entrées sont déselectionnées et lorsque une entrée est sélectionnée,
	  * ne l'est plus.
	*/

	$("#ehr_wom > option:first").click(function(){
		if($(this).prop('selected')==true){
			$("#ehr_wom > option:not(:first)").prop('selected',false);
		}
	});

	$("#ehr_wom > option:not(:first)").click(function(){
		if($(this).prop('selected')==true){
			$("#ehr_wom > option:first").prop('selected',false);
		}		
	});

	$("#ehr_wday > option:first").click(function(){
		if($(this).prop('selected')==true){
			$("#ehr_wday > option:not(:first)").prop('selected',false);
		}
	});

	$("#ehr_wday > option:not(:first)").click(function(){
		if($(this).prop('selected')==true){
			$("#ehr_wday > option:first").prop('selected',false);
		}		
	});

	$("#rpt_dates_num").change(function(e){
		if(isNaN($(this).val()))
			return false;

		$.get('services.php', {
			f: 'computeDates',
			freq: $("#rpt_freq").val(),
			date: $("#event_startdt").val(),
			num: $("#rpt_dates_num").val(),
			format: "LSTRING",
                        sformat: "%a %d %B %Y"
		},function (rsp){
			if ($(rsp).attr('status') == 'failed') {
				window.console.log("Erreur dans computeDates");
			}
			$("#rpt_dates>li").remove();
			var dates=$(rsp).find("dates > date").each(function(i,e){
						$("#rpt_dates").append("<li>" + $(e).text() + "</li>");
			});
			var request=$(rsp).find("params > request").text();
		});

	}).change();

});


/*Mise à jour de la chaîne de description de la fréquence
  via ajax*/
function updateFreqDesc(freq){
	$.get('services.php', {
		f: 'freqToString',
		freq: freq
	},function (rsp){
		if ($(rsp).attr('status') == 'failed') {
			window.console.log("Erreur dans updateFreqDesc");
		}
		console.log(rsp);
		var res=JSON.parse($(rsp).find('value').text());
		$("#rpt_freq_desc").text(res);
	});
}

/*Charge la fréquence dans la fenêtre modale*/
function prepareEhRepeatModal(){
	var freq = $("#rpt_freq").val();
	if(freq=="")freq="-:-";
	var afreq = freq.split(":");
	// if(afreq[0]=="*")afreq[0]="1,2,3,4,5,6,7";
	// if(afreq[1]=="*")afreq[1]="1,2,3,4,5";

	var weekdays = afreq[0].split(",");
	var weekofmonth = afreq[1].split(",");

	$("#ehrepeatmodal option").prop('selected',false);

	weekdays.forEach(function(v,i,a){
		if(v=="-") return;
		if(v=="*")
			$("#ehr_wday option:first").prop('selected',true);
		else
			$("#ehr_wday option:eq("+Number(v)+")").prop('selected',true);
	});

	weekofmonth.forEach(function(v,i,a){
		if(v=="-") return;
		if(v=="*")
			$("#ehr_wom option:first").prop('selected',true);
		else
			$("#ehr_wom option:eq("+Number(v)+")").prop('selected',true);
	});
}