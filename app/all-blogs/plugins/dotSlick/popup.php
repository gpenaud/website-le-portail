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

if (!defined('DC_CONTEXT_ADMIN')) {
    return;
}


global $core;


$dsMedia = new dsMedia($core, "", "image");
$xMedias = $dsMedia->fullXMLTree("text");
$defaultOptions = dotSlickAdmin::getDefaultOptions(); //loaded from $core->blog->settings->dotslick
$options = $defaultOptions;
$submit_title = __("Insert & close");
$dc_admin_url = DC_ADMIN_URL;

$jqSlickDefaultOptions = dotSlickAdmin::getJQuerySlickDefaultOptions();
$gallery_title ="";

function genId(){
    $ret = '';
    $l=rand(7,12);
    for($i=0;$i<$l;$i++){
        $c=rand(0,25);
        $b=rand(0,1);
        $c+=($b%2===0?65:97);
        $ret.=chr($c);
    }
    return $ret;
}

if($_REQUEST["id"]){
    $options["id"]=$_REQUEST["id"];
}else{
    $options["id"]=genId();
}

function loadGallery($desc, $edit = false) {
    global $core;
    try {
        $dsAdmin = new dotSlickAdmin($desc, true);
        $gallerymedias = $dsAdmin->parseOptions();
        
        
        $GalleryMedia = new dsMedia($core, "", "image");
        $xGalleryMedias = $GalleryMedia->listToXML($gallerymedias, "text");
        

        $gallery_desc = [];
        foreach ($gallerymedias as $option) {
            if (isset($option["dir"])) {
                $gallery_desc[] = "dir='" . $option["dir"] . "'";
            } elseif (isset($option["imgurl"])) {
                $gallery_desc[] = "imgurl='" . $option["imgurl"] . "'";
            }
        }

        global $options;
        global $defaultOptions;
        global $submit_title;

        if ($edit) {
            $options = array_merge($defaultOptions, $dsAdmin->images_options);
            $submit_title = __("Update & close");
        }
        $jsgallerymedias = '
        function initialize_gallery(){
            $("#gallery>div.gallery-item").remove();
            var xGallery="' . addslashes($xGalleryMedias) . '";
            var desc="' . join(" ", $gallery_desc) . '";
            loadGallery(xGallery,desc);
        }
        $(document).ready(initialize_gallery);
';

        return $jsgallerymedias;
    } catch (Exception $e) {
        $e->getCode();
        dcPage::addErrorNotice(sprintf(__("Error loading gallery description, reverting "
                                . "to default values<br><code>%s</code>"), $_REQUEST["desc"]));
    }
}

$jsgallerymedias = "";
/* If the popup receives a desc string, process it to populate the fields */
if (isset($_REQUEST["desc"]) && $_REQUEST["desc"] !== "") {
    $jsgallerymedias = loadGallery($_REQUEST["desc"], true);
} elseif (isset($_REQUEST["save"])) {
    $req = &$_REQUEST;
    $options = dotSlickAdmin::saveOptions($req);
    dcPage::addSuccessNotice(__("Default options successfully saved"));

    $desc = "::dotslick " . $_REQUEST["imagelist"] . dotSlickAdmin::formatForDesc($options) . "::";

    $jsgallerymedias = loadGallery($desc);
} else {
    dcPage::addNotice("static", __("Composing a gallery has never been so simple.<br/>"
                    . "First, browse the media library, pick folders and/or single pictures and drag them in the Gallery frame.<br>"
                    . "You can reorder or delete the items in the Gallery view using drag'n'drop.<br>"
                    . "You can customize the gallery with a bunch of options.<br>"
                    . "If the default options don't suit your needs, you can click \"Save defaults\" to memorize your favorite options.<br>"
                    . "Once done, click \"Save and close\" to go back to the post editor."), ["with_ts" => false]);
}

$gallery_title = isset($_REQUEST["title"]) ? $_REQUEST["title"] : "";


function jsimage_options($options, $varname) {
    $js = "var " . $varname . "={\n";
    foreach ($options as $o => $v) {
        
        if ($v === "true" || $v === true) {
            $val = '"yes"';
        } elseif ($v === "false" || $v === false) {
            $val = '"no"';
        } elseif (is_string($v)) {
            $val = '"' . urldecode($v) . '"';
        } else {
            $val = $v;
        }
        $js .= "\t" . $o . ":" . $val . ",\n";
    }
    $js .= "};";
    return $js;
}

$descId = $_REQUEST["descid"];


?>
<html>
    <head>
        <title><?php echo __("Insert dotSlick gallery"); ?></title>
        <script type="text/javascript" src="<?php echo $core->blog->getQmarkURL() . "pf=dotSlick/js/popup.js"; ?>" ></script>
        <script type="text/javascript" src="<?php echo $core->blog->getQmarkURL() . "pf=dotSlick/js/jquery-ui.js"; ?>" ></script>
        <link rel="stylesheet" href="<?php echo $core->blog->getQmarkURL() . "pf=dotSlick/css/popup.css"; ?>" type="text/css" />
    </head>
    <body class="ds-popup">
        <script type="text/javascript">
            //<![CDATA[
<?php echo jsimage_options($defaultOptions, "image_default_options"); ?>
<?php echo $jsgallerymedias; ?>
<?php echo jsimage_options($jqSlickDefaultOptions, "jqSlickDefaultOptions"); ?>
            var descId = <?php echo $descId; ?>;
            var xMedias = "<?php echo addslashes($xMedias); ?>";
            var tcount_tooltip = "<?php echo __("Number of images in folder & subfolders"); ?>";
            var count_tooltip = "<?php echo __("Number of images in folder"); ?>";

            var gallery_count = "<?php echo __("contains %d pictures"); ?>";
            var gallery_count1 = "<?php echo __("contains 1 picture"); ?>";
            //]]>
        </script>
        <h2><?php echo __("Media Library"); ?> <span id="media-breadcrumb"></span></h2>
        <div id="medias">&nbsp;</div>
        <h2><?php echo __("Media Gallery"); ?> <span id="gallery-count"></span></h2>
        <div id="gallery-wrapper">
            <div id="gallery"><span><?php echo __("Drag images and image folders<br/>You may reorder them"); ?></span></div>
            <div id="gallerytrash"></div>
        </div>
        <form name="f2" method="get" action="plugin.php?p=dotSlick&popup=1">
            <?php echo $core->formNonce(); ?>
            <input type="hidden" name="p" value="dotSlick"/>
            <input type="hidden" name="popup" value="1"/>
            <input type="hidden" name="descid" value="<?php echo $_REQUEST["descid"]; ?>"/>
            <input type="hidden" name="id" value="<?php echo $options["id"];?>"/>

            <input id="imagelist" type="hidden" class="maximal" style="width:94vw" name="imagelist" value="<?php echo isset($_REQUEST["imagelist"]) ? $_REQUEST["imagelist"] : ""; ?>"/>
            <div class="two-cols clear">
                <div class="col">
                    <fieldset id="autoplay">
                        <legend><?php echo __("Autoplay settings"); ?></legend>
                        <label><?php echo __("Autoplay"); ?>
                            <p>

                                <input type="radio" name="autoplay" value="no" <?php echo (!$options["autoplay"]) ? 'checked="1"' : '' ?>><?php echo __("No"); ?></input>
                                <input type="radio" name="autoplay" value="yes" <?php echo ($options["autoplay"]) ? 'checked="1"' : '' ?>><?php echo __("Yes"); ?></input>
                            </p>
                        </label>
                        <label><?php echo __("Autoplay speed (in ms)"); ?>
                            <p><input type="number" name="autoplaySpeed" value="<?php echo ($options["autoplaySpeed"]) ?>" min="0" max="60000" step="1000" title="<?php echo __("Set the slideshow speed in milliseconds"); ?>"/></p>
                        </label>
                        <label><?php echo __("Infinite loop"); ?>
                            <p>
                                <input type="radio" name="infinite" value="no" <?php echo (!$options["infinite"]) ? 'checked="1"' : '' ?>><?php echo __("No"); ?></input>
                                <input type="radio" name="infinite" value="yes" <?php echo ($options["infinite"]) ? 'checked="1"' : '' ?>><?php echo __("Yes"); ?></input>
                            </p>
                        </label>
                        <label><?php echo __("Pause when over"); ?>
                            <p>
                                <input type="radio" name="pauseOnHover" value="no" <?php echo (!$options["pauseOnHover"]) ? 'checked="1"' : '' ?>><?php echo __("No"); ?></input>
                                <input type="radio" name="pauseOnHover" value="yes" <?php echo ($options["pauseOnHover"]) ? 'checked="1"' : '' ?>><?php echo __("Yes"); ?></input>
                            </p>
                        </label>
                    </fieldset>
                </div>
                <div class="col">
                    <fieldset id="others">
                        <legend><?php echo __("Other settings"); ?></legend>
                        <label class='required'><abbr title="<?php echo(__('Required field')); ?>">*</abbr> <?php echo __("Gallery title"); ?>                        
                            <p><input type="text" class="maximal" name="title" value="<?php echo $gallery_title; ?>" required='1' /></p>
                        </label>
                        <label><?php echo __("Link image to original"); ?>
                            <p>
                                <input type="radio" name="linkto" value="no"<?php echo (!$options["linkto"]) ? 'checked="1"' : '' ?>><?php echo __("No"); ?></input>
                                <input type="radio" name="linkto" value="yes" <?php echo ($options["linkto"]) ? 'checked="1"' : '' ?>><?php echo __("Yes"); ?></input>
                            </p>
                        </label>
                        <label><?php echo __("Display dots"); ?>
                            <p>
                                <input type="radio" name="dots" value="no" <?php echo (!$options["dots"]) ? 'checked="1"' : '' ?>><?php echo __("No"); ?></input>
                                <input type="radio" name="dots" value="yes" <?php echo ($options["dots"]) ? 'checked="1"' : '' ?>><?php echo __("Yes"); ?></input>
                            </p>
                        </label>
                        <label><?php echo __("Display arrows"); ?>
                            <p>
                                <input type="radio" name="arrows" value="no" <?php echo (!$options["arrows"]) ? 'checked="1"' : '' ?>><?php echo __("No"); ?></input>
                                <input type="radio" name="arrows" value="yes" <?php echo ($options["arrows"]) ? 'checked="1"' : '' ?>><?php echo __("Yes"); ?></input>
                            </p>
                        </label>
                        <label><?php echo __("Enable mousewheel navigation"); ?>
                            <p>
                                <input type="radio" name="mousewheel" value="no" <?php echo (!$options["mousewheel"]) ? 'checked="1"' : '' ?>><?php echo __("No"); ?></input>
                                <input type="radio" name="mousewheel" value="yes" <?php echo ($options["mousewheel"]) ? 'checked="1"' : '' ?>><?php echo __("Yes"); ?></input>
                            </p>
                        </label>
                        <label><?php echo __("Slides height (in px)"); ?>
                            <p><input type="number" name="height" value="<?php echo str_replace("px", "", $options["height"]) ?>" min="0" max="2500" step="50" title="<?php echo __("Set slides height in pixels"); ?>"  /> </p>

                        </label>
                    </fieldset>
                </div>
            </div>
            <fieldset id="buttons">

                <input type="submit" name="ok" value="<?php echo $submit_title; ?>" />
                <input type="reset" name="reset" value="<?php echo __("Reset all values"); ?>" />
                <input type="submit" name="save" value="<?php echo __("Save defaults"); ?>" />
            </fieldset>
        </form>
    </body>
</head>

