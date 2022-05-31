<?php

/*
 *  This file is part of dotSlick, a plugin for Dotclear 2.
 *  
 *  Copyright (c) 2019 Bruno Avet
 *  Licensed under the GPL version 2.0 license.
 *  A copy of this license is available in LICENSE file or at
 * http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
 */


if (!defined('DC_RC_PATH')) {
    return;
}

$core->addBehavior('publicHeadContent', array('dotSlickPublicBehaviors', 'publicHeadContent'));
$core->addBehavior('templateBeforeValue', array('dotSlickPublicBehaviors', 'templateBeforeValue'));


#public ajax service dotajax
if (isset($core->pubrest)) {
    $__autoload['dotSlickPublicRestMethods'] = dirname(__FILE__) . '/_publicservices.php';
    $core->pubrest->register('dotSlick', 'dotSlickPublicRestMethods');
}

class dotSlickPublicBehaviors {

    static function publicHeadContent($core, $ctx) {
        $url = $core->blog->getQmarkURL() . 'pf=' . basename(dirname(__FILE__));
        echo '<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.0/css/all.css" integrity="sha384-lZN37f5QGtY3VHgisS14W3ExzMWZxybE1SJSEsQp9S+oqd12jhcu+A56Ebc1zFSJ" crossorigin="anonymous">' . "\n";
        echo '<link rel="stylesheet" type="text/css" media="all" href="' . $url . '/js/slick/slick.css" />' . "\n";
        //echo '<script type="text/javascript" src="/js/slick/jquery-2.0.3.min.js"></script>'."\n";
        echo '<script type="text/javascript" src="' . $url . '/js/slick/slick.js"></script>' . "\n";
        echo '<script type="text/javascript" src="' . $url . '/js/slick/jquery.mousewheel.js"></script>' . "\n";
    }
}
