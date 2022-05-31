//<![CDATA[
if (typeof console === "undefined" || typeof console.log === "undefined") {
     console = {};
     console.log = function() {};
}

/*Etapes d'animation en mode normal:
 S0 : zoom sur le centre du motif
 S1 : dézoome avec petite rotation arrière
 S2 : rotation élément par élément avec pause
 S3 : pause avec Animation en haut
 */
//Z is a data struct which holds all the settings for the animation.
var Z = {
	S0: {
		css: {
			backgroundSize: "2275px",
			rotate: "0deg",
			cursor: "pointer"},
		csstxt:{
			transform: "scale(1)",
		},
		anim: {}},
	S1: {
		css: {
			a: {rotate: "-360deg"},
			e: {rotate: "-72deg"},
			p: {rotate: "-144deg"},
			v: {rotate: "-216deg"},
			s: {rotate: "-288deg"}},
		anim: {
			delay: 2000,
			data: {backgroundSize: "640px", rotate: "0deg"},
			dur: 1,
			easing: "linear"},
		animtxt: {
			delay: 0,
			data: {scale:"0.28"},
			dur:1000,
			easing:"swing"}},
	S2: {
		css: {backgroundSize: "640px", backgroundPosition: "center center", cursor: "default"},
		anim: {
			params: {
				delay: 650,
				dur: 1500,
				easing: "swing"
			},
			elems: {
				curr: 0,
				0: "a",
				1: "e",
				2: "p",
				3: "v",
				4: "s",
				5: "a1",
				l: 6,
				a: {rotate: "0"},
				e: {rotate: "-72"},
				p: {rotate: "-144"},
				v: {rotate: "-216"},
				s: {rotate: "-288"},
				a1: {rotate: "-360"}}}},
	S3: {
		css: {backgroundSize: "640px", rotate: "0deg", cursor: "default"},
		csstxt: {transform: "scale(0.28)"},
		anim: {
			delay: 0,
			data: {backgroundSize: "640px", rotate: "0deg"},
			dur: 300,
			easing: "swing"}},
	zoom: {
		params: {
			size: "2275px",
			defaultCss: {backgroundPosition: "center center"},
			dur1: 1000,
			dur2: 1000,
			easing: "linear",
			anim2: {backgroundSize: "2275px"}},
		elems: {
			a: {
				anim: {rotate: "0", backgroundPositionX: "-832px", backgroundPositionY: "30px", backgroundSize: "2275"},
				css2: {transform: "rotate(359.99999deg)"},
				css: {backgroundPosition: "-832px 30px"}},
			e: {
				anim: {rotate: "-72", backgroundPositionX: "-1630px", backgroundPositionY: "-569px", backgroundSize: "2275"},
				css2: {transform: "rotate(-72deg)"},
				css: {backgroundPosition: "-1630px -569px"}},
			p: {
				anim: {rotate: "-144", backgroundPositionX: "-1306px", backgroundPositionY: "-1515px", backgroundSize: "2275"},
				css2: {transform: "rotate(-144deg)"},
				css: {backgroundPosition: "-1306px -1515px"}},
			v: {
				anim: {rotate: "-216", backgroundPositionX: "-306px", backgroundPositionY: "-1499px", backgroundSize: "2275"},
				css2: {transform: "rotate(-216deg)"},
				css: {backgroundPosition: "-306px -1499px"}},
			s: {
				anim: {rotate: "-288", backgroundPositionX: "-15px", backgroundPositionY: "-541px", backgroundSize: "2275"},
				css2: {transform: "rotate(-288deg)"},
				css: {backgroundPosition: "-15px -541px"}}}}};


function queue(f)
{
	$("#accueil").
			delay(Z.S2.anim.params.delay).
			animate(
					Z.S2.anim.elems[Z.S2.anim.elems[Z.S2.anim.elems.curr++]],
					Z.S2.anim.params.dur,
					Z.S2.anim.params.easing,
					function () {
						if (Z.S2.anim.elems.curr<Z.S2.anim.elems.l)
							queue(f);
						else {
							$("#accueil").off("click").css(Z.S3.css);
							Z.S2.anim.elems.curr = 0;
							f&&f.call();
						}
					});
}

function animAccueil(f) {
	if (quickquick||quick)
	{
		//Skip , go to the end directly
		$("#txtportail").css(Z.S3.csstxt);
		$("#accueil").css(Z.S3.css);
		f&&f.call();
		return;
	}
	$("#txtportail").
			one("click", function () {
				$("#accueil").click();
			});
	$("#imagew>a").on("click", function () {
			return false;
		});
	$("#accueil").
			one("click", function () {
				//Arrêt prématuré de l'animation
				$("#accueil").
						stop(true, false).
						animate(Z.S3.anim.data,
								Z.S3.anim.dur,
								Z.S3.anim.easing, f).
						css(Z.S3.css);
				$("#txtportail").
						stop(true,false).
						css(Z.S3.csstxt);
			}).
			css(Z.S1.css).
			delay(Z.S1.anim.delay).
			animate(Z.S1.anim.data,
					{speed:Z.S1.anim.dur,
					 easing:Z.S1.anim.easing,
					 queue:true,
					 complete: function () {
						queue(f);
					 }
					 });
}

var accueilAngle=0;

function zoomTo(elem, quickquick,f)
{
	$(".liens").hide();
	$("#liens_"+elem).show();

	if (quickquick)
	{
		$("#txtportail").hide();
		$("#accueil").
				stop(true, false).
				css(Z.zoom.elems[elem].css2).
				css(Z.zoom.elems[elem].css).
				css(Z.zoom.params.anim2);
		$("#zoom").show();
		return;
	}

	$("#txtportail").hide();
	var anim=Z.zoom.elems[elem].anim;
	var retour=Z.S2.anim.elems[elem];
	
	var angle=anim.rotate;
	var delta=angle-accueilAngle;
	console.log("De: "+accueilAngle+" à "+angle+ "(delta : "+delta+") "+((Math.abs(delta)>180)?"LOOPING!":""));
	if(delta==0){
		anim.rotate=0;
		retour.rotate=0;
	}if(delta>180){
		anim.rotate=Number(angle)-360;
		retour.rotate=Number(angle)-360;
	}else if (delta<-180){
		anim.rotate=360+Number(angle);
		retour.rotate=360+Number(angle);
	}
	if(Math.abs(delta)>180)
		console.log("!!! Angle modifié de "+angle+ " à "+anim.rotate);

	$("#retour").one("click",function () {
		$("#zoom").hide();
		$("#accueil").stop(true, false);
		if (!quickquick){
			accueilAngle=retour.rotate;
			console.log("Angle de l'accueil : "+accueilAngle);
			$("#accueil").animate(
					retour,
					Z.S2.anim.params.dur,
					Z.S2.anim.params.easing).css(Z.S2.css);
			$("#txtportail").fadeIn("fast");
		}else{
			$("#accueil").css(Z.S2.anim.elems[elem]).css(Z.S2.css);
			$("#txtportail").show();
		}
		$(".titre").show();
		return false;
	});
	
	
	$("#accueil").
			stop(true, false).
			animate(anim, Z.zoom.params.dur2, Z.zoom.params.easing, function () {
				$("#zoom").show();
				f && f.call();
			});
}

var homepage=true;

function finishStartup() {
	console.log("finishStartup");
	return;
}

function imageFailed(){
	//Le mandala n'est pas chargé, on affiche la page de nouvelles
	console.log("imageFailed");
	document.location.href="/category/Du-Grain-a-Moudre";
}

function imageLoaded(){
	console.log("#preload loaded");
	$("#accueil").css("background-image", "url("+$("#preload").attr('src')+")");

	$(".titre").click(function () {
		zoomTo($(this).attr("id"), quickquick,finishStartup);
		$(this).hide();
		return false;
	});

	animAccueil(finishStartup);
}

$(window).resize(function(){
	var H=$(window).innerHeight();
	var W=$(window).innerWidth()
	var ratio=Math.min(W,H)/700;
	if(ratio==0)ratio=0.0000001;
	var xshift=-(W*(1-ratio)/2);
	var yshift=-(H*(1-ratio)/2);
	$("#page").css("transform","matrix("+ratio+",0,0,"+ratio+","+xshift+","+yshift+")");
});

$(document).ready(function () {
	$(window).resize();
	$("#preload").imagesLoaded().fail(imageFailed).done(imageLoaded);
});



//]]>
