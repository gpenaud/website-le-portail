function getPosition(elem){
	var pos={left:0,top:0};
	var parent=$(elem).parent();
	if($(elem)[0].tagName == "BODY"){
		return pos;
	}

	var parentPos=getPosition(parent);	
	parentPos.left+=$(elem)[0].offsetLeft;
	parentPos.top+=$(elem)[0].offsetTop;

	pos.left=$(elem).position().left+parentPos.left;
	pos.top=$(elem).position().top+parentPos.top;

	return pos;
}


$(document).ready(function(){
	// Effet de transition d'affichage du contenu
	$("#page").css({"opacity":1,"transition":"opacity 2s"});
	// Suppression de l'élément "Accueil" du Breadcrumb
	var bHtml=$("#breadcrumb").html();
	bHtmlAccueil="<!-- retiré dans portail.js : "+bHtml.substring(0,bHtml.indexOf("›"))+"-->";
	bHtmlBreadcrumb=bHtml.substr(bHtml.indexOf("›")+1);
	$("#breadcrumb").html(bHtmlAccueil+bHtmlBreadcrumb);
        
	// Effet de transparence de la page au survol du logo
//	$("#logo").on("mouseover", function(){
//			$("#page").css({"opacity":0});
//			$("#contextBGNav").show().css({"opacity":1});
//			var pos=getPosition($("#logo"));
//			$("#contextBGNav").css({marginLeft:pos.left+"px",marginTop:pos.top+"px"});
//		}).on("mouseout",function(){
//			$("#page").css({"opacity":1});
//			$("#contextBGNav").hide().css({"opacity":0});
//		});
});