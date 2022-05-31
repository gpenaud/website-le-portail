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


jsToolBar.prototype.elements.dotSlick = {
    type: 'button',
    title: 'dotSlick Gallery',
    icon: 'index.php?pf=dotSlick/dotslick20.png',
    fn: {},
    fncall: {},
    open_url: 'plugin.php?p=dotSlick&popup=1',
    data: {},
    popup: function () {
        window.the_toolbar = this;
        this.elements.dotSlick.data = {};
        var p_win = window.open(this.elements.dotSlick.open_url, 'dc_popup',
                'alwaysRaised=yes,dependent=yes,toolbar=yes,height=670,width=650,' +
                'menubar=no,resizable=yes,scrollbars=yes,status=no');
        p_win.focus();
    },
};
// OUVERTURE DU POPUP POUR LES TROIS MODES
jsToolBar.prototype.elements.dotSlick.fn.wiki = function () {
    this.elements.dotSlick.popup.call(this);
};
jsToolBar.prototype.elements.dotSlick.fn.xhtml = function () {
    this.elements.dotSlick.popup.call(this);
};
jsToolBar.prototype.elements.dotSlick.fn.wysiwyg = function () {
    this.elements.dotSlick.popup.call(this);
};
// AJOUT DU TEXTE AU POST POUR LES TROIS MODES
jsToolBar.prototype.elements.dotSlick.fncall.wiki = function () {
    var d = this.elements.dotSlick.data;
    this.encloseSelection('', '', function () {
        return d.texte;
    });
};
jsToolBar.prototype.elements.dotSlick.fncall.xhtml = function () {
    var d = this.elements.dotSlick.data;
    this.encloseSelection('', '', function () {
        return d.texte;
    });
};
jsToolBar.prototype.elements.dotSlick.fncall.wysiwyg = function () {
    var d = this.elements.dotSlick.data;
    temp = document.createTextNode(d.texte);
    this.insertNode(temp);
};
var xmlMedias;
var initial_imagelist = "";
function createImage(attr = {title:"", class:"", type:"image"}, iconsrc = "", count = - 1, tcount = - 1) {
    if (attr.title === "") {
        attr.title = attr.href.split('/').splice(-1, 1)[0];
    }
    if (iconsrc === "") {
        iconsrc = attr.href;
    }

    var imageDiv = document.createElement('div');
    $(imageDiv).hide().attr(attr).append('<figure><img />' +
            '<figcaption></figcaption></figure>');
    $(imageDiv).find("img").attr({src: iconsrc, alt: attr.title, title: attr.title});
    $(imageDiv).find("figcaption").text(attr.title);
    if (count > 0 && attr.type === "folder") {
        $(imageDiv).prepend('<span class="fcount"></span>')
                .find('.fcount')
                .attr("title", count_tooltip)
                .text(count);
    }
    if (tcount > 0 && attr.type === "folder" && tcount !== count) {
        $(imageDiv).prepend('<span class="tcount"></span>')
                .find('.tcount')
                .attr("title", tcount_tooltip)
                .text(tcount);
    }
    return $(imageDiv);
}

function loadGallery(xml, desc) {
    var medias = $(xml).find(">*");
    $(medias).each(function () {
        if ($(this).get(0).nodeName.toLowerCase() === "dir") {
            $("#gallery").append(
                    createImage(
                            {href: $(this).attr("url"),
                                title: $(this).attr("title"),
                                class: $(this).attr("class"),
                                type: "folder"},
                            $(this).attr("icon"),
                            $(this).attr("count"),
                            ).addClass("gallery-item").show());
        } else {
            var icon = $(this).find("thumb[size='sq']").attr("src");
            if (icon === "")
                icon = $(this).attr("icon");
            if (icon === "")
                icon = "/dotclear/admin/images/media/image.png";
            $("#gallery").append(
                    createImage(
                            {href: $(this).attr("dir") + "/" + $(this).attr("fname"),
                                dhref: $(this).attr("dir"),
                                title: $(this).attr("title"),
                                class: ($(this).attr("class") !== undefined) ? $(this).attr("class") : "image",
                                type: "image"},
                            icon).addClass("gallery-item").show());
        }
    });
    $("#imagelist").val(desc);
    initial_imagelist = desc;
}

function loadOptions(options) {
    /*    for (var opt in options) {
     if (options[opt] === "yes" || options[opt] === "no") {
     $('input[name="' + opt + '"][value="' + options[opt] + '"]').attr("checked", "1").change();
     } else {
     if (options[opt].endsWith("px")) {
     options[opt] = options[opt].replace("px", "");
     }
     $('input[name="' + opt + '"]').val(1 * options[opt]);
     }
     }*/
}

function setImages(dir = "") {
    var mediadirs, mediafiles;
    $("#media-breadcrumb").text("/" + dir);
    if (dir === "") {
        mediadirs = $(xmlMedias).find("medias>dir");
    } else {
        mediadirs = $(xmlMedias).find("dir[url='" + dir.replace("'", "\'") + "']>content>dir");
    }
    $("#medias>*").remove();
    $(mediadirs).each(function () {
        $("#medias").append(
                createImage(
                        {href: $(this).attr("url"),
                            title: $(this).attr("title"),
                            class: $(this).attr("class"),
                            type: "folder"},
                        $(this).attr("icon"),
                        $(this).attr("count"),
                        $(this).attr("treecount")).show());
    });
    if (dir === "") {
        mediafiles = $(xmlMedias).find("medias>file");
    } else {
        mediafiles = $(xmlMedias).find("dir[url='" + dir.replace("'", "\'") + "']>content>file");
    }

    $(mediafiles).each(function () {
        var icon = $(this).find("thumb[size='sq']").attr("src");
        if (icon === "")
            icon = $(this).attr("icon");
        if (icon === "")
            icon = "images/media/image.png";
        $("#medias").append(
                createImage(
                        {href: $(this).attr("url"),
                            dhref: $(this).attr("dir"),
                            title: $(this).attr("title"),
                            class: ($(this).attr("class") !== undefined) ? $(this).attr("class") : "image",
                            type: "image"},
                        icon).show());
    });
    $("#medias>div").draggable({
        appendTo: "#content",
        cancel: ".media-folder-up",
        helper: "clone",
        connectToSortable: "#gallery",
        revert: true,
        revertDuration: 200,
        tolerance: "pointer",
        containment: "#content",
        cursorAt: {right: 3, bottom: 3},
        scroll: false,
        start: function (e, ui) {
            $("#gallery").addClass("dragreceive");
        },
        stop: function (e, ui) {
            $("#gallery").removeClass("dragreceive");
        }
    });
}

function setOverlay(href, title, caller) {
    $("#medias").append(
            createImage(
                    {href: href,
                        title: title,
                        class: "overlay",
                        dir: "",
                        type: "overlay"
                    }).show()
            );
    $("#medias>div.overlay").draggable(
            {
                appendTo: "#content",
                cancel: ".media-folder-up",
                helper: function () {
                    $(caller).clone().addClass("overlay-helper").appendTo("#medias");
                    return $("#medias>div.overlay-helper")[0];
                },
                connectToSortable: "#gallery",
                containment: "#content",
                revert: true,
                revertDuration: 200,
                cursorAt: {right: 3, bottom: 3},
                tolerance: "pointer",
                scroll: false,
                start: function (e, ui) {
                    $("#gallery").addClass("dragreceive");
                    $(this).remove();
                },
                stop: function (e, ui) {
                    $("#gallery").removeClass("dragreceive");
                }
            }
    );
}

function checkDuplicates(href, dhref = "", type = "file") {
    if (type === "file") {
        var hrefs = {
            image: "[href='" + href + "']",
            dir: "[href='" + dhref + "']"
        };
    } else if (type === "folder") {
        var hrefs = {
            image: "[dhref='" + href + "']",
            dir: "[href='" + href + "']"
        };
    }

    var selector = ">div.gallery-item.image" + hrefs.image + ",>div.gallery-item.media-folder" + hrefs.dir;
    var duplicate = $("#gallery").find(selector);
    if (duplicate.length === 0) {
        return false;
    }
    return duplicate;
}

function validForGallery(media) {
    if ($(media).attr("type") === "folder") {
        var count = $(media).find(".fcount").text();
        if (count === "") {
            return false;
        }
    }

    if ($("#gallery>div.gallery-item").length === 0) {
        return true;
    }
    var type = ($(media).attr("type") === "folder" ? "folder" : "file");
    var duplicate = checkDuplicates($(media).attr("href"), $(media).attr("dhref"), type);
    if (duplicate !== false) {
        $(duplicate).animate({backgroundColor: "red"}, "fast").delay(100)
                .animate({backgroundColor: "unset"}, "fast").delay(100)
                .animate({backgroundColor: "red"}, "fast").delay(100)
                .animate({backgroundColor: "unset"}, "fast").delay(100)
                .animate({backgroundColor: "red"}, "fast").delay(100)
                .animate({backgroundColor: "unset"}, "fast");
        return false;
    }

    return true;
}

/* takes an array of {dir: dir} and {dir:dir, img:img} objects and builds a 
 * "factorised" list
 * if an image object has the same dir as the previous one, only one declaration 
 * is necessary*/

function formatList(desc) {
    var list = "";
    for (var i = 0; i < desc.length; i++) {
        var item = desc[i];
        var itemtext = "";
        if (item.img === undefined) {
            itemtext = " dir='" + item.dir + "'";
        } else {
            if (i > 0 && desc[i - 1].dir === item.dir) {
                itemtext = ";" + item.img + "'";
            } else {
                itemtext = " imgurl='" + item.dir + ";" + item.img + "'";
            }
        }
        list += itemtext;
    }
    return list.replace(/\';/g, ";").trim();
}

function loadError(error, desc) {

}

//set the media browser
$(document).ready(function () {
    xmlMedias = $.parseXML(xMedias);
    setImages();
    $("#medias").on("click", "div", function (e) {
        var type = $(this).attr("type");
        if (type === "folder") {
            setImages($(this).attr("href"));
        } else if (type === "image") {
            setOverlay($(this).attr("href"), $(this).attr("title"), $(this));
        } else if (type === "overlay") {
            $(this).remove();
        }
        return false;
    });
    $("#gallery").sortable({
        forceHelperSize: true,
        forcePlaceholderSize: true,
        items: "> div.gallery-item",
        placeholder: "galleryplaceholder",
        connectWith: "#gallerytrash",
        containment: "#content",
        dropOnEmpty: true,
        scroll: false,
        cursorAt: {right: 3, bottom: 3},
        activate: function (e, ui) {
            if ($(ui.item).hasClass("gallery-item")) {
                $("#gallertrash").droppable('activate');
                $("#gallerytrash").show("fast");
            }
        },
        start: function (e, ui) {
            if (!$(ui.item).hasClass("gallery-item")) {
                if (!validForGallery($(ui.item))) {
                    ui.item.addClass('drag-cancelled');
                    $("#medias>div[href='" + $(ui.item).attr("href") + "']").draggable("cancel");
                }
            }
        },
        stop: function (e, ui) {
            $("#gallerytrash").hide(1000);
            if (ui.item.hasClass('drag-cancelled')) {
                ui.item.remove();
            }
        },
        update: function () {
            var desc = [];
            var gallery_items = $("#gallery>div:not('.drag-cancelled')");
            var count_images = 0;
            $(gallery_items).each(function () {
                var type = $(this).attr("type");
                $(this).addClass("gallery-item");
                if (type === "folder") {
                    count_images += ($(this).find(".fcount").text() * 1);
                    desc.push({dir: $(this).attr("href")});
                } else if (type === "image") {
                    var img = $(this).attr("href").split('/').splice(-1, 1);
                    count_images++;
                    desc.push({dir: $(this).attr("dhref"), img: img});
                }
            });
            $("#gallery-count").text("");
            if (count_images === 1) {
                $("#gallery-count").text(gallery_count1);
            } else if (count_images > 1) {
                $("#gallery-count").text(gallery_count.replace(/\%d/, count_images));
            }

            $("#imagelist").val(formatList(desc));
        }});
    $("#gallerytrash").droppable({
        tolerance: "pointer",
        activate: function (e, ui) {
            $("#gallerytrash").show("fast");
        },
        drop: function (e, ui) {
            $(ui.draggable).remove();
            $("#gallerytrash").removeClass("trash-open");
        },
        over: function (e, ui) {
            $("#gallerytrash").addClass("trash-open");
        },
        out: function (e, ui) {
            $("#gallerytrash").removeClass("trash-open");
        }
    }).show().hide(3000);
    $("input[name='autoplay']").change(function () {
        if (this.checked) {
            if ($(this).val() === "no") {
                $("#autoplay>label:not(:first)").slideUp();
            } else {
                $("#autoplay>label:not(:first)").slideDown();
            }
        }
    }).change();

    $("form[name='f2']").click(function (event) {
        $(this).data('clicked', $(event.target));
    });

    $("form[name='f2']").submit(function (e) {
        if ($(this).data('clicked').is('[name="save"]')) {
            window.submited = true;
            return;
        }

        e.preventDefault();
        if ($("#imagelist").val() === "") {
            $("html, body").animate({scrollTop: 0}, "slow");
            return;
        }
        var options = [];
        var autoplay = $("input[name='autoplay']:checked").val() === "yes";
        for (var o in image_default_options) {
            if (!autoplay && (o === "autoplaySpeed" || o === "pauseOnHover" || o === "infinite")) {
                continue;
            }
            var input = $("form[name='f2']").find("[name='" + o + "']");
            var val;
            if ($(input).attr("type") === "radio") {
                val = $(input).filter(":checked").val();
            } else {
                val = $(input).val();
            }
//            if ((jqSlickDefaultOptions[o] !== undefined) && (val === jqSlickDefaultOptions[o])) {
//                //skip : option is set to jquerySlick default value
//                continue;
//            } else {
                options.push(o + '="' + val + '"');
//            }
        }
        var ret = "::dotslick " + $("#imagelist").val() + " " + options.join(" ") + "::";

        var descObj = window.opener.getDesc(descId);
        descObj.set("desc", ret);
        $title = $(this).find("input[name='title']").val()
        descObj.widget.element.findOne('.wds-title').setHtml($title);
        descObj.widget.data.popupreceived = true;
        window.submitted = true;
        window.close();
        return false;
    });


    $("form[name='f2']").on("reset", function (e) {
        setTimeout(function () {
            $("input[name='autoplay']").change();
            initialize_gallery();
        }, 1);
    });
});
