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

'use strict';
$(function () {
    $('#media-insert-cancel').click(function () {
        window.close();
    });
    $('#media-insert-ok').click(function () {
        var insert_form = $('#media-insert-form').get(0);
        if (insert_form === undefined) {
            return;
        }
        var type = insert_form.elements.type.value;
        var gallery = window.opener.$("#gallery");
        var imagelist = window.opener.$("#imagelist");
        if (type == 'image') {
            var img_description = $('input[name="description"]', insert_form).val();
            
            var imageHTML = '';
            var imageSrc = $('input[name="src"]:eq(1)', insert_form).val();
            var imageOrig = $('input[name="src"]:eq(4)', insert_form).val();
            imageHTML="<img class=\"gallery_item\" src=\""+imageSrc+"\" alt=\""+img_description+"\"  title=\""+img_description+"\"/>";
            $(gallery).append(imageHTML);
            var list=$(imagelist).val();
            list+=(list.length>0?";":"")+imageOrig;
            $(imagelist).val(list);
        }
        window.close();
    });
    $('.two-boxes').hide();
    $('.border-top').remove();
    $('#media-insert-form').before("<div class='imgpreview'><figure><img src='"+$('input[name="src"]:eq(3)').val()+"'/><figcaption>"+$('input[name="description"]').val()+"</figcaption></figure></div>");
    $('head').append("\n\
<style>\n\
.imgpreview img {\n\
    height:200px;\n\
    margin:auto;\n\
\n\
}\n\
\n\
.imgpreview {\n\
    width:100%;\n\
    text-align:center;\n\
    margin-bottom:10px;\n\
}\n\
\n\
.part-tabs {\n\
    display:none !important;\n\
}\n\
</style>\n\
");
    
});