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


String.prototype.cleanQuotes = function () {
    if (/^\s*(\\?["']).*\1\s*$/.test(this)) {
        return this.replace(/^\s*\\?["']/, "").replace(/\\?["']\s*$/, '');
    }
    return this + '';
};

String.prototype.encodeHtmlEntities = function () {
    var entities = [
        'nbsp', 'iexcl', 'cent', 'pound', 'curren', 'yen', 'brvbar',
        'sect', 'uml', 'copy', 'ordf', 'laquo', 'not', 'shy', 'reg', 'macr',
        'deg', 'plusmn', 'sup2', 'sup3', 'acute', 'micro', 'para', '#183', 'cedil', 'sup1',
        'ordm', 'raquo', 'frac14', 'frac12', 'frac34', 'iquest',
        'Agrave', 'Aacute', 'Acirc', 'Atilde', 'Auml', 'Aring', 'AElig',
        'Ccedil', 'Egrave', 'Eacute', 'Ecirc', 'Euml', 'Igrave', 'Iacute', 'Icirc',
        'Iuml', 'ETH', 'Ntilde', 'Ograve', 'Oacute', 'Ocirc', 'Otilde', 'Ouml', 'times',
        'Oslash', 'Ugrave', 'Uacute', 'Ucirc', 'Uuml', 'Yacute', 'THORN', 'szlig',
        'agrave', 'aacute', 'acirc', 'atilde', 'auml', 'aring', 'aelig',
        'ccedil', 'egrave', 'eacute', 'ecirc', 'euml', 'igrave', 'iacute', 'icirc',
        'iuml', 'eth', 'ntilde', 'ograve', 'oacute', 'ocirc', 'otilde', 'ouml', 'divide', 'oslash',
        'ugrave', 'uacute', 'ucirc', 'uuml', 'yacute', 'thorn', 'yuml'];
    return this.replace(/[\u00A0-\u9999<>\&]/gim, function (i) {
        var c = i.charCodeAt(0);
        if (c >= 160 && c <= 255) {
            return '&' + entities[c - 160] + ';';
        } else if (c == 39) {
            return '&apos;';
        }
        return '&#' + i.charCodeAt(0) + ';';
    });
};


/**
 * 
 * placeholder for all desc objects (in case of several widgets)
 * each element is a {editor:editorname,widget:widgetid,desc:descObj}
 */
window.descs = [];

/**
 * Populates window.descs with correct info
 * @param {string} editor
 * @param {integer} widgetid
 * @param {dotSlickDesc} desc
 * @returns {integer} id of the desc
 */
window.registerDesc = function (editor, widgetid, desc) {
    return window.descs.push({editor: editor, widget: widgetid, desc: desc}) - 1;
};

window.destroyDesc = function (id) {
    if (window.descs[id] != undefined) {
        delete window.descs[id];
    }
}

window.getDesc = function (qs) {
    if (typeof qs === "number") {
        if (qs < window.descs.length) {
            return window.descs[qs].desc;
        } else {
            console.warn("window.getDesc : descId " + qs + " was not found.");
            return undefined;
        }
    } else if (typeof qs === "object") {
        if (qs.editor !== "undefined" && qs.widget !== "undefined") {
            for (i = 0; i < window.descs.length; i++) {
                if (window.descs[i].editor === qs.editor && window.descs[i].widget === qs.widget)
                    return window.descs[i].desc;
            }
            console.warn("window.getDesc : no desc found for editor " + qs.editor + "and widget id " + qs.widget + ".");
        }
    }
    console.warn("window.getDesc : could not understand your query. Please provide either a desc_id or a {editor=editorname,widget=widgetid} object.");

};


/**
 * This object holds a gallery description (desc) and manipulates it.
 * 
 * @param {string} desc
 * @returns {dotSlickDesc}
 *
 * 
 */

const DS_DESC_OPTION_CHANGED = 1;
const DS_DESC_IMAGES_CHANGED = 1 << 1;

function dsOptions() {
    this.length = function () {
        var count = 0;
        this.each(function () {
            count++;
        });
        return count;
    };

    this.clear = function () {
        this.each(function (k, v, obj) {
            delete obj[k];
        }, this);
    };

    this.each = function (cb, thisObj = this) {
        for (var i in thisObj) {
            if (typeof (this[i]) !== 'function') {
                cb(i, thisObj[i], thisObj);
            }
    }
    };
}

function genId(){
    ret = '';
    l=Math.floor(Math.random()*5);
    for(i=0;i<7+l;i++){
        c=Math.floor(Math.random()*26);
        b=Math.floor(Math.random()*2);
        c+=(b%2===0?65:97);
        ret+=String.fromCharCode(c);
    }
    return ret;
    
}


function dotSlickDesc(desc, widget) {
    /**
     * @property {integer} this.flags : bitfield for images and options changes
     * @property {string} desc  : gallery descriptive string
     * @property {array} images : array of images description ['dir'|'imgurl','path']
     * @property {dsOptions} options : holds all the gallery's options
     * 
     * @property {regexp} re : gets all desc option='value' components and checks desc validity
     * @property {regexp} reimages : matches against this.desc to retrieve the images definitions
     * @property {regexp} reoptions : matches against this.desc to retrieve the options
     */

    this.flags = 0;
    this.desc = desc;
    this.images = [];
    this.options = new dsOptions();
    this.widget = widget;


    this.re = /(?:\s+((?:no)?(?:dir|imgurl|linkto|autoplaySpeed|autoplay|pauseOnHover|infinite|dots|arrows|height|mousewheel))(?:=(?:(?:[\'\"]([^\'\"]*)[\'\"])|(yes)|(no)|([^\s:]*)))?)+?/gi;
    this.reimages = /(?:\s+((?:dir|imgurl))(?:=(?:(?:[\'\"]([^\'\"]*)[\'\"])))?)+?/gi;
    this.reoptions = /(?:\s+((?:no)?(?:id|linkto|autoplaySpeed|autoplay|pauseOnHover|infinite|dots|arrows|height|mousewheel))(?:=(?:(?:[\'\"]([^\'\"]*)[\'\"])|(yes)|(no)|([^\s:]*)))?)+?/gi;

    /**
     * fires a descChange event
     */
    this.fire = function () {
        if (this.widget !== null) {
            $(this.widget).trigger("descChange", [this, new Date()]);
        } else {
            console.log("descChange triggered but widget is not ready.");
        }
    };

    /**
     * 
     * adds an eventhandler for descChange event
     * @param {callback} callback
     * @param {object} data
     */

    this.onDescChange = function (callback, data = {}) {
        if (this.widget === null)
            return;
        $(this.widget).on("descChange", data, callback);

    };


    /**
     * initializes a new instance of dotSlickDesc
     * @param {string} desc
     */
    this.init = function (desc) {
        var New = (desc === '');

        if (New){
            id = genId();
            this.options.id=id;
            desc = '::dotslick ::';
        }
        this.desc = desc;

        if (!New)
            this.extract();

    };


    /**
     * checks if the given desc is valid
     * @param {string} desc
     * @returns {Boolean}
     */
    this.check = function (desc) {
        return this.re.test(desc);
    };

    /**
     * resets the changed flags to no
     */
    this.resetFlags = function () {
        this.flags = 0;
    };

    /**
     * checks if the image changed flag is set
     * @returns {boolean}
     */
    this.imagesGetChanged = function () {
        return (this.flags & DS_DESC_IMAGES_CHANGED);
    };

    /**
     * checks if the option changed flag is set
     * @returns {boolean}
     */
    this.optionsGetChanged = function () {
        return (this.flags & DS_DESC_OPTION_CHANGED);
    };

    /**
     * Toggles the image changed flag
     */
    this.imagesSetChanged = function () {
        this.flags |= DS_DESC_IMAGES_CHANGED;
    };

    /**
     * Toggles the option changed flag
     */
    this.optionsSetChanged = function () {
        this.flags |= DS_DESC_OPTION_CHANGED;
    };

    /**
     * checks if some images are set
     * @returns {Boolean}
     */
    this.hasImages = function () {
        return (this.images.length > 0);
    };

    /*
     * checks if some options are set
     * @returns {Boolean}
     */
    this.hasOptions = function () {
        return (this.options.length() > 0);
    };

    /**
     * checks it this is valid.
     * @returns {Boolean}
     */
    this.isComplete = function () {
        return (this.hasImages() && this.hasOptions());
    };
    /**
     * extracts images descriptions and options from this->desc
     * 
     * @param {boolean} r : refresh this->desc when done
     * 
     */
    this.extract = function (r = true) {
        this.images = [];
        this.options.clear();

        var arr_images = this.desc.match(this.reimages);
        if (arr_images !== null) {
            this.set('images', arr_images, false);
        }

        var arr_options = this.desc.match(this.reoptions);
        if (arr_options !== null) {
            this.set('options', arr_options, r);
    }
    };


    /**
     * Compares the current desc object with a given string (the id is ignored during comparison)
     * 
     * @param {string} otherDesc
     * @returns {Boolean}
     */

    this.compare = function (otherDesc) {
        if (this.desc === otherDesc) {
            return true;
        }
        var otherDescObj = new dotSlickDesc(otherDesc, null);
        for (i = 0; i < this.images.length; i++) {
            if (otherDescObj.images.find(function (v, i) {
                return (v[0] === this[0] && v[1] === this[1]);
            }, this.images[i]) === undefined) {
                return false;
            }
        }

        for (var o in this.options) {
            if(o === 'id')
                continue;
            if (otherDescObj.options[o] === undefined || otherDescObj.options[o] !== this.options.o) {
                return false;
            }
        }
        return true;
    };


    /**
     * Sets the desc components value
     * 
     * @param {string} o : option to set 'desc' | 'images' | 'options' | 'imgurl' | 'dir' | 'option name'
     * @param {string | boolean | integer | array } v : option's value, type depends on o value
     * @param {boolean} [r='true'] : if true, refreshes this->desc according to the changed settings
     * 
     * If o == 'desc', sets the whole object
     * If o == ('imgurl'|'dir'), adds it to the this->images array
     * If o == 'images', sets the whole images part, v is a string with dir='dir' imgurl='imgurl' directives or an array of directives
     * If o == 'options', sets the whole options set
     * Else, sets the option o to v (this->options)
     * 
     * @returns {Boolean}
     */
    this.set = function (o, v, r = true) {
        switch (o) {
            case 'desc':
                if (this.check(v)) {
                    r = false; //disabling refreshing this->desc as this->desc was just set
                    if (this.compare(v)) {
                        return false;
                    }
                    this.desc = v;
                    this.extract(false);
                    this.fire();
                } else {
                    return false;
                }
                break;
            case 'images':
                var arr_images;
                if (!Array.isArray(v)) {
                    arr_images = v.match(this.reimages);
                    if (arr_images === null) {
                        return false;
                    }
                } else {
                    arr_images = v;
                }
                arr_images.forEach(function (d, i) {
                    desc = d.split('=');
                    if (desc.length === 2) {
                        this.set(desc[0].trim(), desc[1].cleanQuotes(), false);
                    }
                }, this);
                break;
            case 'options':
                var arr_options;
                if (!Array.isArray(v)) {
                    arr_options = v.match(this.reoptions);
                    if (arr_options === null) {
                        return false;
                    }
                } else {
                    arr_options = v;
                }
                arr_options.forEach(function (d, i, a) {
                    var desc = d.split('=');
                    var o = desc[0].trim(), v = '';
                    if (desc.length === 1) {
                        v = 'yes';
                    } else if (desc[0].startsWith('no')) {
                        v = 'no';
                        o = desc[0].substring(2);
                    } else {
                        v = desc[1].cleanQuotes();
                    }
                    this.set(o, v, false);
                }, this);
                break;
            case 'imgurl':
            case 'dir':
                var imgdesc = [o, v.cleanQuotes()];
                if (this.images.find(
                        function (val) {
                            return Array.isArray(val) && (val[0] === imgdesc[0]) && (val[1] === imgdesc[1]);
                        }) !== undefined) {
                    return false;
                }
                this.images.push(imgdesc);
                this.imagesSetChanged();
                break;
            default:
                if (this.options[o] !== undefined && this.options[o] === v) {
                    return false;
                }
                this.options[o] = v;
                this.optionsSetChanged();
        }
        if (r)
            this.refresh();
        return true;
    };

    /**
     * 
     * property getter
     * 
     * @param {type} property : the property to get
     * @returns {String|Boolean|Array|integer|array|dsOptions}
     */

    this.get = function (property) {
        switch (property) {
            case 'desc':
                return this.desc;
                break;
            case 'images':
                return this.images;
            case 'options':
                return this.options;
            default:
                return this.options[property];
        }
    };

    /**
     * regenerates this->desc description from this->images & this->options components.
     * it is executed by default each time an option is changed or an image is added,
     * but it can be prevented using this->set r parameter = false
     */
    this.refresh = function () {
        if (this.flags === 0) {
            return;
        }
        var newdesc = '::dotslick';
        this.images.forEach(function (v, i) {
            newdesc += ' ' + v[0] + '=\'' + v[1] + '\'';
        });
        this.options.each(function (k, v) {
            newdesc += ' ' + k + (v !== null ? '=\'' + v.replace(/\'/g, '&apos;') + '\'' : '');
        });
        newdesc += '::';
        this.set("desc", newdesc, false);
    };

    /**
     * Loads an image obtained from an ajax service which represents the gallery
     * images in the widget.
     * 
     * @param {type} path : the base path to access the different status pictures
     * @param {type} img : the widget's image element (as a DOM element)
     * @returns {undefined} 
     */
    this.loadImage = function (path, img, caption) {
        var imgsrc = path + 'images/blank.png';
        var oDesc = this;
        var count = "";
        if (this.check(this.desc) && this.images.length > 0) {
            if (!this.imagesGetChanged()) {
                return;
            }
            $(img).attr('src', path + 'images/loading.gif');
            $(caption).hide();
            $.get('services.php?f=getGalleryImage&desc=' + this.desc, function (data, status, xhr) {
                if (status === 'success') {
                    if ($(data).find('error').length === 1) {
                        imgsrc = path + 'images/error.png';
                        console.warn($(data).find('error').text());
                    } else {
                        imgsrc = $(data).find('value').text();
                        count = $(data).find('value').attr('count');
                        options = [];
                        $(data).find('option').each(function(i,v){
                            optname=$(this).attr('name');
                            val=$(this).attr('value');
                            if(optname !== 'id')
                                options.push(__(optname,val) + ((optname === 'height' ||optname === 'autoplaySpeed')?": "+val:""));
                        });

                        oDesc.resetFlags();
                    }
                    $(img).attr('src', imgsrc);
                    captiontext = "";
                    if (count !== "") {
                        captiontext = "<h5>" + count + "</h5>";
                    }else{
                        captiontext = "<h5>" + __('no pictures. Bug!') + "</h5>";
                    }
                    
                    if(options!== undefined){
                        captiontext += "<i>"+__('Options :') +" </i>" + options.join(', ');
                    }

                    captiontext += "<h5>"+__('Doubleclick to change the gallery images and settings');

                    $(caption).html(captiontext).show();


                } else {
                    imgsrc = path + 'images/error.png';
                }                
                $(img).attr('src', imgsrc);
            });
        }
    };


    this.init(desc);
}

