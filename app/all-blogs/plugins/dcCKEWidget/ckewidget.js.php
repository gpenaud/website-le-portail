<?php

// -- BEGIN LICENSE BLOCK ----------------------------------
//
// This file is part of dcCKEWidget, a plugin for Dotclear 2.
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
    var admin_url = "<?php echo DC_ADMIN_URL;?>";

    CKEDITOR.config.addWidgetCss = function (pluginpath, csspath="/cke-addon/content.css"){
        var cfg = this, curContentsCss = cfg.contentsCss;
        if (!CKEDITOR.tools.isArray(curContentsCss))
                cfg.contentsCss = curContentsCss ? [curContentsCss] : [];
        cfg.contentsCss.push("index.php?pf="+pluginpath+csspath);
    }
//</script>