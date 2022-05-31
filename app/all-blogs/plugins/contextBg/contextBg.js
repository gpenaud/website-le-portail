	//<![CDATA[
		//Preloading images
		window.onload = function() {
			setTimeout(function() {
				var xhr = new XMLHttpRequest();
				for(var i in bg_filenames)
					new Image().src = bg_filenames[i];
			}, 1000);
		};
		
				
		window.changeBg = function(next, prev) {
			next=next||false;
			prev=prev||false;
			if(bg_filenames.length==0)
				return;
			if(next && prev) {
				raise("Error : changeBg (from dotclear plugin contextBg): next & prev can't go to both directions at once, I stay here.");
				return;		
					
			}
			if(!next && !prev) {
				var new_index=parseInt(Math.random()*bg_filenames.length);
				if(new_index==window.bg_index){
					changeBg({next:true});				
				}
				window.bg_index = new_index;
				 						
			} else if(next)	{
				bg_index = ((bg_index<bg_filenames.length-1) ? bg_index+1 : 0);			
				
			} else {
				bg_index = ((bg_index>0) ? bg_index-1 : bg_filenames.length-1);
			}
			$(selector).css("background-image","url("+bg_filenames[bg_index]+")");
		}
		$(document).ready(function() {
			window.changeBg();
		});
	//]]>
