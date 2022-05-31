<?php

/* 
  *  This file is part of dcDebug, a plugin for Dotclear 2.
  *  
  *  Copyright (c) 2019 Bruno Avet
  *  Licensed under the GPL version 2.0 license.
  *  A copy of this license is available in LICENSE file or at
  * http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
 */

class dotSlickPublicRestMethods {

   public static function getGalleryImage($core, $get) {
        $rsp = new xmlTag();
        try {
            $desc = $get['desc'];
            if ($desc == null) {
                throw new Exception(__("Description string missing"), 1);
            }
            $height = array_key_exists('height', $get) ? $get['height'] : null;

            if ($height == null || !is_numeric($height)) {
                $height = 80;
            }

            $dsAdmin = new dotSlickAdmin($_REQUEST["desc"], true);
            $Medias = new dsMedia($core, "", "image");

            $count=0;
            $value=new xmlTag("value");
            $images = $dsAdmin->parseOptions();
            $options = $dsAdmin->getOptions(dotSlickAdmin::FILTER_JSLICK_DEFAULTS);
            $value->CDATA($Medias->listToImage($images, $height,$count,false,$options["id"]));
            $value->count=sprintf(__('%d picture','%d pictures',$count),$count);
            foreach($options as $optname => $val){
                $option = new xmlTag("option");
                $option->name=$optname;
                $option->value=$val;
                $value->insertNode($option);
            }
            $rsp->insertNode($value);
        } catch (Exception $ex) {            
            $rsp->error("getMediaTree error : " . $ex->getMessage());
        }
        return $rsp;
    }
}
