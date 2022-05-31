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

define("RE", '/(?:\s+((?:no)?(?:id|dir|imgurl|linkto|autoplaySpeed|autoplay|pauseOnHover|infinite|dots|arrows|height|mousewheel))(?:=(?|(?:[\'\"]([^\'\"]*)[\'\"])|(yes)|(no)|([^\s:]*+)))?)+?/i');

//RE2 :'/(?:\s+(dir|img|imgurl|linkto|autoplaySpeed|autoplay|pauseOnHover|infinite|dots|arrows|height)(?:=(?|(?:[\'\"]([^\s]*)[\'\"])|(yes)|(no)|([^\s:]*+)))?)+?/i';
class dotSlickAdmin {

    public $desc;
    public $code;
    private $images;
    public $images_options;
    private $d;

    public static function getOptionList($option = null, $namesonly = false, $saveonly = false) {
        $options = ['id'=>'string', 'linkto' => 'boolean', 'autoplay' => 'boolean', 'autoplaySpeed' => 'integer', 'pauseOnHover' => 'boolean', 'infinite' => 'boolean', 'dots' => 'boolean', 'arrows' => 'boolean', 'height' => 'integer', 'mousewheel' => 'boolean'];
        $nosave = [];
        if (!$saveonly) {
            $options = array_merge($options, $nosave);
        }

        if ($namesonly) {
            return ($option === null) ? array_keys($options) : $option; // the later is stupid XD
        } else {
            return ($option === null) ? $options : $options[$option];
        }
    }

    private static function get($from, $o, $t = null) {
        if ($t === null)
            $t = self::getOptionList($o);
        if (is_array($from)) {
            if ($t === 'boolean') {
                $v = (isset($from[$o]) ? ($from[$o] == "" ? true : $from[$o]) : false);
            } else {
                $v = (isset($from[$o])) ? $from[$o] : false;
            }
        } else {
            $v = ($from->settingExists($o) ? $from->{$o} : null);
        }
        if (!$v)
            return false;
        switch ($t) {
            case 'boolean':
                $v = ($v === true || $v === 'true' || $v === 'yes' || $v === 1 || $v === "1") ? "true" : "false";
                break;
            case 'integer':
                $v = ($v === null) ? 0 : (integer) $v;
                break;
            case 'string':
                $v = ($v === null) ? '' : (string) $v;
        }
        return $v;
    }

    private static function set(&$to, $o, $v, $t = null) {

        if ($t === null)
            $t = self::getOptionList($o);

        if ($t == 'boolean') {
            $v = ($v === true || $v === 'yes' || $v === 'true' || $v === 1) ? true : false;
        }
        $to->put($o, $v, $t);
    }

    public static function extractOptions($from, $saveonly = false) {

        if (!is_array($from) && !(is_object($from) && is_a($from, "dcNamespace"))) {
            return [];
        }
        if (is_object($from) && is_a($from, "dcNamespace")) {
            $saveonly = true;
        }
        $ret = [];
        $ol = self::getOptionList(null, false, $saveonly);
        foreach ($ol as $o => $t) {
            $val = self::get($from, $o, $t);
            $ret[$o] = $val;
        }

        return $ret;
    }

    public static function saveOptions($from) {
        $s = &$GLOBALS["core"]->blog->settings->dotslick;
        $options = self::extractOptions($from, true);
        foreach ($options as $o => $v) {
            self::set($s, $o, $v);
        }
        return $options;
    }

    public static function getDefaultOptions() {

        $s = $GLOBALS["core"]->blog->settings->dotslick;
        $defaults = self::extractOptions($s, true);
        return $defaults;
    }

    public static function getJQuerySlickDefaultOptions() {
        return ['autoplay' => 'no',
            'autoplaySpeed' => 3000,
            'pauseOnHover' => 'yes',
            'infinite' => 'yes',
            'dots' => 'no',
            'arrows' => 'yes'
        ];
    }

    public static function formatForDesc($options) {
        $ret = "";
        $ol = self::getOptionList();
        $formats = ['boolean' => [" %s='no'", " %s='yes'"], 'integer' => " %s=%d", 'string' => " %s='%s'"];
        foreach ($options as $o => $v) {
            $t = $ol[$o];
            $format = $formats[$t];
            if (is_array($format)) {
                $format = $format[(integer) $v];
            }
            $ret .= sprintf($format, $o, $v);
        }
        return $ret;
    }

    private static function getNoOptions() {
        return ['nolinkto', 'noautoplay', 'nopauseOnHover', 'noinfinite', 'nodots', 'noarrows', 'nomousewheel'];
    }

    public function __get($name){
        if(isset($this->images_options[$name])){
            return $this->images_options[$name];
        }
        return null;
    }
    
    public function __construct($desc, $dontconvert = false) {

        try {
            if ($this->isValidDesc($desc)) {
                $this->desc = $desc;
                $this->images = [];
                $this->images_options = self::getDefaultOptions();

                if (!$dontconvert) {
                    $this->code = $this->convert();
                } else {
                    $this->code = false;
                }
            } else {
                $this->desc = null;
                $this->code = null;
                throw new Exception("Description invalid (" . $desc . ")");
            }
        } catch (Exception $e) {
            throw new Exception("dotSlickAdmin __constructor : " . $e->getMessage());
        }
    }

    public function isValidDesc($desc) {

        $re = RE;
        $matches = [];

        if (!preg_match_all($re, $desc, $matches, PREG_SET_ORDER, 0)) {
            throw new Exception("No match");
        }
        $options = [];
        foreach ($matches as $group) {
            $options[$group[1]] = isset($group[2]) ? $group[2] : "yes";
        }

        if (!array_key_exists("dir", $options) && !array_key_exists("img", $options) && !array_key_exists("imgurl", $options)) {
            throw new Exception("No images");
        }

        return true;
    }

    public function parseOptions() {
        $re = RE;
        $matches = [];
        if (!preg_match_all($re, $this->desc, $matches, PREG_SET_ORDER, 0)) {
            return false;
        }
        $options = [];
        $medias = [];

        foreach ($matches as $group) {
            if (in_array($group[1], $this->getNoOptions())) {
                $options[] = [substr($group[1], 2), ($group[2] == "no") ? "yes" : "no"];
                /* Ha ha, double negation handling (case nodots=no, assumed dots=yes XD */
            } else {
                $options[] = [$group[1], isset($group[2]) ? $group[2] : "yes"];
            }
        }
        foreach ($options as $option) {
            //Case where an option is specified by itself without a value, yes assumed (example: autoplay)
            $optname = strtolower($option[0]);
            $val = $option[1];
            switch ($optname) {
                case "dir":
                    $medias[] = ["dir" => $val];
                    break;
                case "imgurl":
                    $medias[] = ["imgurl" => $val];
                    break;
                case "autoplayspeed":
                    $this->images_options["autoplaySpeed"] = $val;
                    break;
                case "height":
                    $val .= is_numeric($val) ? "px" : "";
                    $this->images_options["height"] = $val;
                    break;
                case "id":
                    $this->images_options["id"] = $val;
                case "pauseonhover":
                    $optname = "pauseOnHover";
                case "linkto":
                case "autoplay":
                case "infinite":
                case "dots":
                case "arrows":
                case "mousewheel":
                    $this->images_options[$optname] = ($val === "yes" || $val === 1 || $val === true) ? "true" : "false";
                    break;
                default:
                    //For unsupported options (allows to specify all jquery-slick options
                    //but without validity control or default values
                    $this->images_options[$optname] = $val;
            }
        }
        return $medias;
    }

    /**
     *
     * gets the options with optionnal filter.
     * if $filter_default is FILTER_NONE, the full list is returned
     * if $filter_default is FILTER_JSLICK_DEFAULTS, the list is expurged from the JSLICK defaults
     * if $filter_default is FILTER_USER_DEFAULTS, the list is expurged from the prefered options.
     *
     */
    
    const FILTER_NONE = -1;
    const FILTER_JSLICK_DEFAULTS = 1;
    const FILTER_USER_DEFAULTS =2;

    public function getOptions($filter_default = self::FILTER_NONE){
        if($filter_default == self::FILTER_NONE){
            return $this->images_options;
        }
        if($filter_default == self::FILTER_USER_DEFAULTS){
            $defaults = self::getDefaultOptions();
        }else{
            $defaults = self::getJQuerySlickDefaultOptions();
        }

        $ret=[];
        foreach($this->images_options as $optname => $val){
            if(isset($defaults[$optname]) && $defaults[$optname] === $val){
                continue;
            }
            $ret[$optname]=$val;
        }
        return $ret;
    }

    //Converts a dotslick description into fully functionnal HTML code
    public function convert() {

        $medias = $this->parseOptions();
        foreach ($medias as $media) {
            if (isset($media["dir"])) {
                $this->insertDir($media["dir"], self::is_true($this->images_options["linkto"]));
            } elseif (isset($media["imgurl"])) {
                $this->insertImg($media["imgurl"], self::is_true($this->images_options["linkto"]));
            }
        }

        $slickgroup = "dotslick" . rand();

        $code = '
<!-- dotSlick start -->
';


        $code .= '<div id="' . $slickgroup . '" class="slick-wrapper">
    <div>  
';
        foreach ($this->images as $img) {
            $code .= '        <div><figure class="slick-slide-inner">' . $img . '<figcaption class="image-carousel-caption"></figcaption></figure></div>' . "\n";
        }
        $code .= '
    </div>
</div>';

        $code .= '
<script type="text/javascript">
$(document).ready(function() {
    $("#' . $slickgroup . '").
        addClass("image-carousel").
        addClass("slick-arrows-inside").
        addClass("slick-dots-outside");

    $("#' . $slickgroup . '>div").slick({
        variableWidth:true,
        centerMode:true,
';


        foreach ($this->images_options as $o => $v) {
            if ($o === "autoplayspeed") {
                $o = "autoplaySpeed";
            }
            if ($o === "pauseonhover") {
                $o = "pauseOnHover";
            }
            if ($o === "arrows" && self::is_false($v)) {
                continue; //We handle this by hiding the arrows to keep navigation
            }
            if ($o === "mousewheel" || $o === "id") { //skip because no jqueryslick options
                continue;
            }
            if ($o != "linkto" && $o != "height") {
                $code .= '        ' . $o . ':' . $v . ",\n";
            }
        }
        $code .= '    
    });

    $(".slick-slide").each(function(){
        var datatitle=$(this).find("img").attr("data-title");
        $(this).find(".image-carousel-caption").text(datatitle);
    });

    $(this).find(".slick-prev, .slick-next, .slick-dots").addClass("fas");

';
        if (array_key_exists("arrows", $this->images_options) && self::is_false($this->images_options["arrows"])) {
            $code .= '
    $("#' . $slickgroup . ' .slick-prev,#' . $slickgroup . ' .slick-next").hide(0);
';
        }
        $code .= '
    $(".slick-slide img").height("' . $this->images_options["height"] . '");
    if(!' . $this->images_options["autoplay"] . '){
        $(".slick-next").focus();
    }
';

        if ($this->images_options["mousewheel"] == 'true') {
            $code .= '    $(".image-carousel").mousewheel(function(e,d){
        e.preventDefault();
        if(d<0){
            $(this).find(".slick-prev").click();
        }else if(d>0){
            $(this).find(".slick-next").click();
        }
    });
';
        }

        $code .= '
    });</script>
    <!-- dotSlick end -->
    ';

        return $code;
    }

    //Inserts a complete media directory, alphabetically sorted
    private function insertDir($dirname, $linkto) {
        $my_media = new dcMedia($GLOBALS['core']);

        if (!is_dir($my_media->root . '/' . $dirname)) {
            return "<!--REPERTOIRE INEXISTANT-->";
        }

        // Liste des images du répertoire media
        $bl = & $GLOBALS['core']->blog;   // Solve bug "LOCK TABLES"
        $bl->con->writeLock($bl->prefix . 'media'); // Solve bug "LOCK TABLES"
        $my_media->chdir($dirname);
        $my_media->getDir();
        $f = $my_media->dir;
        $bl->con->unlock();    // Solve bug "LOCK TABLES"

        $counter = 0;
        foreach ($f['files'] as $v) {
            $counter += $this->insertImage($v, $linkto);
        }
        return $counter;
    }

    //Inserts a set of images, $imgstr is directorypath;file1;file2;file3
    private function insertImg($imgstr, $linkto) {
        $my_media = new dcMedia($GLOBALS['core']);

        $imglist = explode(';', $imgstr);
        $dir_name = array_shift($imglist);

        // Detection du répertoire
        if (!is_dir($my_media->root . '/' . $dir_name)) {
            return "<!--REPERTOIRE INEXISTANT-->";
        }

        // Liste des images du répertoire media
        $bl = & $GLOBALS['core']->blog;   // Solve bug "LOCK TABLES"
        $bl->con->writeLock($bl->prefix . 'media');
        $my_media->chdir($dir_name);
        $my_media->getDir();
        $f = $my_media->dir;
        $bl->con->unlock();
        $counter = 0;
        foreach ($imglist as $v) {
            foreach ($f['files'] as $j) {
                if ($j->basename == $v) {
                    $counter += $this->insertImage($j, $linkto);
                }
            }
        }
        return $counter;
    }

    private function insertImage($f, $linkto) {
        if ($f->media_type == 'image') {
            $image = $f->file_url;
            $title = trim($f->media_title);
            $srcset = [];
            $thumbs=array_reverse($f->media_thumb);
            if(isset($thumbs["sq"])){
                    unset($thumbs["sq"]);
            }
            foreach($thumbs as $s=>$src){
                if($s==="sq")
                    continue;
                $size=$GLOBALS["core"]->blog->settings->system->get("media_img_".$s."_size")."w";
                $srcset[] = $src." ".$size;
            }
            $srcset_txt = join(", ",$srcset);
            if(strlen($srcset_txt)>0){
                $srcset_txt=' srcset="'.$srcset_txt.'"';
            }

            // Si le title fini par .jpg on le supprime
            if (preg_match('/\.jpg$|\.png$|\.gif$/i', $title)) {
                $title = '';
            }
            $txt='<img src="'.$image.'" alt="'. trim($title) .'" data-title="' . $title . '" '.$srcset_txt .'/>';
        } else {
            $image = $f->file_url;
            $title = trim($f->media_title);
            $low_src = "";
            $low_src_dim = "";
            $txt = '<img src="' . $image . '" alt="' . $title . '" data-title="' . $title . '" />';
        }

        if ($linkto) {
            $txt = '<a href="' . $image . '" class="slick-slide-image" title="' . $title . '">' . $txt . '</a>';
        }
        array_push($this->images, $txt);

        return 1;
    }

    /* returns an array containing all relevant informations on the elements from the desc */

    public function getImages() {
        
    }
    
    public static function is_false($v){
        return !self::is_true($v);
    }
    
    public static function is_true($v){
        return $v===1 || $v===true || $v==="true" || $v ==="yes";
    }
    

}
