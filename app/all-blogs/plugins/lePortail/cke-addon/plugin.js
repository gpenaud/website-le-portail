CKEDITOR.plugins.add('leportail', {
    requires: 'widget',
    icons: 'leportail',
    init: function (editor) {
        editor.widgets.add('leportail', {
            allowedContent: 'p',
            requiredContent: 'p',
            button: 'Nouvelles',
            template:
                    '<div class="newslist {align}">' +
                    '   <span class="widgettitle">Liste de nouvelles</span>' +
                    '   <div class="label" title="Il faut enregistrer la page ou passer en source et revenir pour voir le changement.">Alignement :' +
                    '       <label>gauche <input type="radio" name="newsalign" value="left" {alignleft}/></label>' +
                    '       <label>centre <input type="radio" name="newsalign" value="center" {aligncenter}/></label>' +
                    '       <label>droite <input type="radio" name="newsalign" value="right" {alignright}/></label>' +
                    '       <label>normal <input type="radio" name="newsalign" value="normal" {alignnormal}/></label>' +
                    '   </div>' +
                    '   <div class="label">Titre :<h4 class="newslisttitle">{title}</span></h4></div>' +
                    '   <div class="label">Nouvelles cliquables<input type="checkbox" name="setlink" {setlink}/></input></div>' +
                    '</div>',
            defaults: {
                title: 'dernières nouvelles',
                setlink: 'checked',
                align: 'normal',
                aligncenter: '',
                alignleft: '',
                alignright: '',
                alignnormal: 'checked'
            },
            editables: {
                title: {
                    selector: '.newslisttitle'
                }
            },

            upcast: function (element, data) {
                if (element.name === 'p') {
                    var content = element.getHtml();
                    var m = content.match(/\[news_list ([^\]]*)\s*\]/);
                    if (m !== null) {
                        var newslistoptions = {};
                        for(var o in this.defaults)
                            newslistoptions[o]=this.defaults[o];
                        var newslist = m[1];
                        var re = /(\w*)="([^"]*)"/gm;
                        while ((m = re.exec(newslist)) !== null) {
                            if (m.index === re.lastIndex) {
                                re.lastIndex++;
                            }
                            newslistoptions[m[1]] = m[2];
                        }
                        if (newslistoptions.set_link === "1" || newslistoptions.set_link === 1) {
                            newslistoptions.setlink = "checked='checked'";
                        } else {
                            newslistoptions.setlink = "";
                        }
                        
                        if(!/(normal|left|center|right)/.test(newslistoptions.align))
                            newslistoptions.align='normal';
                        newslistoptions.alignnormal = '';
                        newslistoptions['align' + newslistoptions.align] = 'checked';


                        delete newslistoptions.set_link;
                        delete newslistoptions.set_title;

                        var html = this.template.output(newslistoptions);
                        wrapper = new window.CKEDITOR.htmlParser.element("div", {class: "wrapper"});
                        wrapper.setHtml(html);
                        theDiv = wrapper.getFirst();
                        element.replaceWith(theDiv);
                        return theDiv;
                    }
                }
            },

            downcast: function () {
                var title = $(this.element.$).find("h4").text();
                var set_link = 1 * $(this.element.$).find("input[name='setlink']").prop("checked");

                if (title === "") {
                    title = 'set_title="0"';
                } else {
                    title = 'set_title="1" title="' + title + '"';
                }
                var alignment = $(this.element.$).find("input[name='newsalign']:checked").val();
                var align = (alignment !== 'normal' ? ('align="' + alignment + '"') : "");
                var ret = '[news_list ' + align + ' set_link="' + set_link + '" ' + title + ']';
                var p = new window.CKEDITOR.htmlParser.element('p');
                p.setHtml(ret);
                return p;
            },
            edit: () => {
                true;
            },
            init: (editor) => {
            },
            data: () => {
            }
        }),
                editor.ui.addButton('leportail', {
                    label: "Insérez la liste des dernières nouvelles",
                    icon: this.path + 'icons/leportail.png',
                    command: 'leportail',
                    toolbar: 'insert'
                });
        ;
    }
});

$(function () {
    CKEDITOR.config.addWidgetCss("lePortail");
    CKEDITOR.config.addWidgetCss("lePortail", "/css/editor.home.css");
});
