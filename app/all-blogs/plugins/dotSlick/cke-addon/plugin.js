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

CKEDITOR.plugins.add('dotslick', {
// This plugin requires the Widgets System defined in the 'widget' plugin.
    requires: 'widget',
    // Register the icon used for the toolbar button. It must be the same
    // as the name of the widget.
    icons: 'dotslick',
    // The plugin initialization logic goes inside this method.
    init: function (editor) {
        editor.widgets.add('dotslick', {
            allowedContent:
                    'div(!wds)' +
                    'h4(!wds-title)' +
                    'figure' +
                    'img(!src,!alt,!title)' +
                    'figcaption' +
                    'p',

            requiredContent: 'div(wds)',
            editables: {
                title: {
                    selector: '.wds-title',
                    allowedContent: ''
                }
            },
            template:
                    '<div class="wds" id="{wds-id}">' +
                    '<span class="dotSlickWidgetLabel">Dotslick gallery</span>' +
                    '<h4 class="wds-title">{wds-title}</h4>' +
                    '<figure class="wds-figure">' +
                    '<img src="' + this.path + 'images/blank.png" alt="" title=""/>' +
                    '<figcaption></figcaption>' +
                    '</figure>' +
                    '<p class="wds-desc">{wds-desc}</p>' +
                    '</div>',
            defaults: {
                'wds-title': __('Titre de la galerie'),
                'wds-desc': '',
                'wds-id': ''
            },
            button: 'Insérez une galerie',

            upcast: function (element) {
                if (element.name === 'div' && element.hasClass('wds')) {
                    var data = {
                        'wds-title': '',
                        'wds-desc': '',
                        'wds-id': ''
                    };

                    element.filterChildren(new window.CKEDITOR.htmlParser.filter({
                        elements: {
                            h4: function (element) {
                                data['wds-title'] = element.getHtml();
                            },
                            p: function (element) {
                                data['wds-desc'] = element.getHtml().trim();
                            }
                        }}
                    ));
            
                    var d = new dotSlickDesc(data['wds-desc']);
                    data['wds-id']=d.get(id);
                    if(data['wds-id']===undefined){
                        data['wds-id']=genId();
                    }
                    
                    var html = this.template.output(data);
                    wrapper = new window.CKEDITOR.htmlParser.element("div",{class:"wds test"}); 
                    wrapper.setHtml(html);
                    theDiv = wrapper.getFirst();
                    element.replaceWith(theDiv);
                    return theDiv;
                }
                return false;
            },
            downcast: function (wE) {
                var title = wE.find('h4');
                var desc = wE.find('p');
                var div = new window.CKEDITOR.htmlParser.element('div', {class: 'wds'});
                title[0].removeClass('wds-title');
                desc[0].removeClass('wds-desc');
                var wrapper = title[0].wrapWith(div);
                wrapper.add(desc[0]);
                var descid=/id=([\"']?)([^\1\s]*)\1/.exec(desc[0].getHtml())[2];
                if($(window.parent.document).find("[value='"+descid+"']").length===0){
                    $(window.parent.document).find("#entry-form").append("<input type='hidden' name='savedotslick[]' value='"+descid+"'/>");
                }
                return wrapper;
            },
            init: function () {
                if (this.data.desc === undefined) {
                    this.setData("desc", window.registerDesc(this.editor.name, this.id, new dotSlickDesc(this.element.findOne('.wds-desc').getText(), this)));
                }

                $(this.element.findOne('.wds-title').$).on('blur', {wdata: this.data}, function (e) {
                    $(this).html($(this).text().encodeHtmlEntities());
                });
                $(this).on("descChange", {widget: this}, function (e, desc, time) {
                    e.data.widget.fire("data");
                });
            },
            destroy: function () {
                if (this.data.desc !== undefined)
                    window.destroyDesc(this.data.desc);
            },
            edit: function () {
                var ds_popup_params = {'width': 650, 'height': 670};
                var ds_open_url = 'plugin.php?p=dotSlick&popup=1';
                var open_url = ds_open_url;
                if (window.getDesc(this.data.desc).isComplete()) {
                    var selection = window.getDesc(this.data.desc).desc;
                    open_url += '&desc=' + encodeURIComponent(selection);
                }else{
                    open_url += '&id=' + window.getDesc(this.data.desc).options.id;
                }
                var title = this.element.findOne('.wds-title').getText();
                open_url += '&title=' + encodeURIComponent(title);
                open_url += '&descid=' + this.data.desc;
                this.setData("popupopened", true);
                var p_win = window.open(open_url, 'dc_popup',
                        'alwaysRaised=yes,dependent=yes,toolbar=yes,' +
                        'height=' + ds_popup_params.height + ',width=' + ds_popup_params.width + ',' +
                        'menubar=no,resizable=yes,scrollbars=yes,status=no');
                var interval = window.setInterval(function (win, widget) {
                    if (win.closed) {
                        widget.fire("data", {popupclosed: true});
                        window.clearInterval(interval);
                    }
                }, 1000, p_win, this);
                p_win.focus();
                return true;
            },
            // Listen on the widget#data event which is fired every time the widget data changes
            // and updates the widget's view.
            // Data may be changed by using the widget.setData() method, which we use in the
            // Simple Box dialog window.
            data: function (e) {
                if (e.data && e.data.popupclosed === true) {
                    if (this.data.popupreceived !== true) {
                        wdsdesc = this.element.findOne('.wds-desc').$.innerText;
                        if (wdsdesc.length == 0 || wdsdesc === this.definition.defaults['wds-desc']) {
                            this.element.getParent().remove();
                            this.repository.destroy(this);
                        }
                        return false;
                    }
                    this.data.popupopened = false;
                    this.data.popupreceived = false;
                }
                if (window.getDesc(this.data.desc).isComplete()) {
                    this.element.findOne('.wds-desc').setText(window.getDesc(this.data.desc).get('desc'));
                    window.getDesc(this.data.desc).loadImage(dotSlickUrl + 'cke-addon/', $(this.element.find('.wds-figure img').$), $(this.element.find('.wds-figure figcaption').$));
                }

            }
        }),
                editor.ui.addButton('dotSlick', {
                    label: "Insérez une galerie dotSlick",
                    icon: this.path + 'icons/dotslick.png',
                    command: 'dotslick',
                    toolbar: 'insert',
                    data: {'wds-title': 'Indiquez un titre', 'wds-desc': '::dotslick ::', 'wds-id':''}
                });
    }
});





