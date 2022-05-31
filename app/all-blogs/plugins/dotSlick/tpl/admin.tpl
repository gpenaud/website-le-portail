<!DOCTYPE html>
<!--
  This file is part of dcDebug, a plugin for Dotclear 2.
  
  Copyright (c) 2019 Bruno Avet
  Licensed under the GPL version 2.0 license.
  A copy of this license is available in LICENSE file or at
 http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
-->
<html>
    <head>
        <title><?php echo __("dotSlick admin page"); ?></title>
        <?php echo dcPage::jsPageTabs($default_tab); ?>
    </head>
    <body>
        <div class="multi-part" id="tab-1" title="<?php echo __('Galleries settings'); ?>">
            <form method="post" action="<?php echo($p_url); ?>">
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
                <p><?php echo $core->formNonce(); ?></p>
                <p><input type="submit" name="save" value="<?php echo __('Save'); ?>" /></p>
            </form>
        </div>
        <div class="multi-part" id="tab-2" title="<?php echo __('Galleries'); ?>">
            <h2>Liste des galeries</h2>
            <form method="post" action="<?php echo($p_url); ?>">
            <table class="minimal">
                <?php foreach($galleries as $gallery){ ?>
                <tr>
                    <td><?php echo form::checkbox('galleries[]',$gallery['id'],false).$gallery['id'];?></td>
                    <td><?php echo $gallery["title"];?></td>
                    <td title='<?php echo __("# of galleries in this post"); ?>'><?php echo $gallery["count"]; ?></td>
                    <td><a href="<?php echo $gallery['adminurl']; ?>"><?php echo __("Edit"); ?></a></td>
                    <td><a href="<?php echo $gallery['url']; ?>" target="_new"> <?php echo __("View"); ?></a></td>
                </tr>
                <?php } ?>
            </table>
            <p><?php echo $core->formNonce(); ?></p>
            <p><input type="submit" name="regenerateall" value="<?php echo __('regenerate all'); ?>" /> <input type="submit" name="regenerate" value="<?php echo __('regenerate selected'); ?>" /></p> 
        </div>
        <div class="multi-part" id="tab-3" title="<?php echo __('Plugin settings'); ?>">
            <form method="post" action="<?php echo($p_url); ?>">
                <fieldset>
                    <label><?php echo __("Enable dotSlick galleries on your posts"); ?>
                        <p>
                            <input type="radio" name="enable" value="0" <?php echo (!$psoptions["enable"]) ? 'checked="1"' : '' ?>><?php echo __("No"); ?></input>
                            <input type="radio" name="enable" value="1" <?php echo ($psoptions["enable"]) ? 'checked="1"' : '' ?>><?php echo __("Yes"); ?></input>
                        </p>
                    </label>
                    <label><?php echo __("Post types which use dotslick"); ?>
                        <p>
                            <input type="text" name="post_types" value="<?php echo $psoptions['post_types']; ?>" />
                        </p>
                        
                    </label>
                </fieldset>
                <p><?php echo $core->formNonce(); ?></p>
                <p><input type="submit" name="saveps" value="<?php echo __('Save'); ?>" /></p>    
            </form>
        </div>
<!--        <div class="multi-part" id="tab-4" title="<?php echo __('Debug'); ?>">
            <h2>Debug</h2>
            <pre><?php echo $debug; ?></pre>
        </div>-->
    </body>
</html>
