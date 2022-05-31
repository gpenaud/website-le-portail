<?php

// -- BEGIN LICENSE BLOCK ----------------------------------
//
// This file is part of dotSlick, a plugin for Dotclear 2.
// 
// Copyright (c) 2019 Bruno Avet
// Licensed under the GPL version 2.0 license.
// A copy of this license is available in LICENSE file or at
// http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
//
// -- END LICENSE BLOCK ------------------------------------

if (!defined('DC_CONTEXT_ADMIN')) {return;}

header('Content-type: text/javascript');

?>
//<script>

    $(function(){
        if(CKEDITOR.config.addWidgetCss){
            CKEDITOR.config.addWidgetCss("dotSlick");
        }
    });
    
    var dotSlickUrl = "<?php echo $core->blog->getQmarkURL();?>pf=dotSlick/";

    var tr = {
    	linkto:["<?php echo __("unclickable images");?>","<?php echo __("link images");?>"],
    	autoplaySpeed:"<?php echo __("slideshow speed");?>",
    	autoplay:["<?php echo __("manual play");?>","<?php echo __("autoplay");?>"],
    	pauseOnHover:["<?php echo __("don't pause slideshow");?>","<?php echo __("pause slideshow when over");?>"],
    	infinite:["<?php echo __("loop once");?>","<?php echo __("infinite loop");?>"],
    	dots:["<?php echo __("hide dots");?>","<?php echo __("show dots");?>"],
    	arrows:["<?php echo __("hide arrows");?>","<?php echo __("show arrows");?>"],
    	height:"<?php echo __("images height");?>",
    	mousewheel:["<?php echo __("disable mousewheel animation");?>","<?php echo __("enable mousewheel animation");?>"],
    	"no pictures. Bug!":"<?php echo __("no pictures. Bug!");?>",
    	dotSlickButtonLabel:"<?php echo __("Insert a gallery");?>",
    	"Options :":"<?php echo __("Options :") ?>",
        "Doubleclick to change the gallery images and settings":"<?php echo __('Doubleclick to change the gallery images and settings') ?>",
    }

    function __(str,sw=null){
    	if(tr[str] !==undefined){
    		if(Array.isArray(tr[str])){
    			sw += "";
    			sw.match(/true|yes|1/i)?sw=1:sw=0;
    			return tr[str][sw]
    		}
    		return tr[str];
    	}
    	return str;
    }

//</script>