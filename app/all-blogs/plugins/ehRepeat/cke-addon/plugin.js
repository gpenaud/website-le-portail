CKEDITOR.plugins.add('events', {
    requires: 'widget',
    icons: 'events',
    init: function (editor) {
        editor.widgets.add('events', {
            allowedContent: 'p',
            requiredContent: 'p',
            button: 'Nouvelles',
            template:
                    '<div class="slavelist {align}">' +
                    '   <span class="widgettitle">Liste d\'événements</span>' +
                    '   <div class="label evtalign" title="Il faut enregistrer la page ou passer en source et revenir pour voir le changement." data="{align}">Alignement :' +
                    '       <label>gauche <input type="radio" name="evtalign" value="left"/></label>' +
                    '       <label>centre <input type="radio" name="evtalign" value="center"/></label>' +
                    '       <label>droite <input type="radio" name="evtalign" value="right"/></label>' +
                    '       <label>normal <input type="radio" name="evtalign" value="normal"/></label>' +
                    '   </div>' +
                    '   <div class="group">' +
                    '       <div class="label">Titre :<h4 class="eventstitle">{title}</h4></div>' +
                    '       <div class="label">Texte si aucun événement:<h4 class="eventsnone">{none}</h4></div>' +
                    '   </div>' +
                    '   <div class="group">' +
                    '       <div class="label">Afficher les titres des événements' +
                    '           <input type="checkbox" name="usetitle" {usetitle}/>' +
                    '           Evénements cliquables<input type="checkbox" name="setlink" {setlink}/>' +
                    '       </div>' +
                    '   </div>' +
                    '   <div class="group evtmode" data="{mode}">' +
                    '       <div class="label">Mode: mélangé <input type="radio" value="mix" name="evtmode"/> ' +
                    '            par événement<input type="radio" value="evt" name="evtmode"/>' +
                    '      </div>' +
                    '       <div class="label evtstyle" data="{evtstyle}">Tous les événements' +
                    '           <input type="radio" name="evtstyle" value="all"/>' +
                    '       </div>' +
                    '       <div class="label">Par titre' +
                    '           <input type="radio" name="evtstyle" value="by_title"/>' +
                    '           <div><h4 class="eventsbytitle" title="titre à rechercher">{by_title}</h4>' +
                    '               <p>Les titres de à rechercher peuvent soit être un titre donné qui devra correspondre à la majuscule près, soit un motif de recherche incluant les caractères % et _.' +
                    '               Le caractère <em>%</em> remplace n\'importe quelle chaîne de caractères, alors que <em>_</em> remplace un seul caractère."' +
                    '               Exemples : "Marchés" correspondra aux événements ayant pour titre "Marchés", alors que "Marché%" correspondra à "Marché", "Marchés", "Marché nocture" etc.</p>' +
                    '           </div>' +
                    '       </div>' +
                    '   </div>' +
                    '</div>',
            editables: {
                eventstitle: '.eventstitle',
                eventsnone: '.eventsnone',
                eventsbytitle: '.eventsbytitle'
            },
            defaults: {
                title: 'Au programme',
                none: 'rien de prévu',
                usetitle: 'checked',
                setlink: 'checked',
                by_title: 'Titre des événements à rechercher',
                mode: 'mix',
                evtstyle: 'all',
                align: 'normal'
            },
            upcast: function (element, data) {
                if (element.name === 'p') {
                    var content = element.getHtml();
                    var m = content.match(/\[event_list ([^\]]*)\s*\]/);
                    if (m !== null) {
                        var evtlistoptions = {};
                        for (var o in this.defaults)
                            evtlistoptions[o] = this.defaults[o];
                        var evtlist = m[1];
                        var re = /(\w*)="([^"]*)"/gm;
                        while ((m = re.exec(evtlist)) !== null) {
                            if (m.index === re.lastIndex) {
                                re.lastIndex++;
                            }
                            evtlistoptions[m[1]] = m[2];
                        }
                        if (evtlistoptions.set_link === "1" || evtlistoptions.set_link === 1) {
                            evtlistoptions.setlink = "checked";
                        } else {
                            evtlistoptions.setlink = "";
                        }
                        if (evtlistoptions.use_post_title === "1" || evtlistoptions.use_post_title === 1) {
                            evtlistoptions.usetitle = "checked";
                        } else {
                            evtlistoptions.usetitle = '';
                        }
                        if (evtlistoptions.by_title) {
                            evtlistoptions.evtstyle = "by_title";
                        }
                        if (evtlistoptions.all) {
                            evtlistoptions.evtstyle = "all";
                        }

                        delete evtlistoptions.set_link;
                        delete evtlistoptions.set_title;
                        delete evtlistoptions.use_post_title;

                        var html = this.template.output(evtlistoptions);
                        wrapper = new window.CKEDITOR.htmlParser.element("div", {class: "wrapper"});
                        wrapper.setHtml(html);
                        theDiv = wrapper.getFirst();
                        element.replaceWith(theDiv);
                        return theDiv;
                    }
                }
            },
            downcast: function () {
                var e = $(this.element.$);
                var title = ' title="' + e.find(".eventstitle").text() + '"';
                var all = e.find("input[name='evtstyle_" + this.id + "[]']:checked").val() === "all";
                var by_title = ' by_title="' + e.find(".eventsbytitle").text() + '"';
                var none = ' none="' + e.find(".eventsnone").text() + '"';
                var set_link = ' set_link="' + (1 * e.find("input[name='setlink']").prop("checked")) + '"';
                var use_title = ' use_post_title="' + (1 * e.find("input[name='setlink']").prop("checked")) + '"';
                var mode = ' mode="' + e.find("input[name='evtmode_" + this.id + "[]']:checked").val() + '"';
                var alignment = e.find("input[name='evtalign_" + this.id + "[]']:checked").val();
                var align = (alignment !== 'normal' ? ('align="' + alignment + '"') : "");
                var style = (all ? ' all="1"' : by_title);
                var ret = '[event_list ' + align + title + style + none + set_link + use_title + mode + ']';
                var p = new window.CKEDITOR.htmlParser.element('p');
                p.setHtml(ret);
                return p;
            },
            edit: () => {
                true;
            },
            init: function (editor) {
                var id = this.id;
                var widget = this.element.$;
                $(widget).find("input[type='radio']").each(function (i, e) {
                    var data = $(widget).find("." + $(e).attr("name")).attr("data");
                    $(e).attr("name", $(e).attr("name") + "_" + id + "[]");
                    $(e).prop("checked", $(e).val() === data);
                });
            },
            data: () => {
            }
        }),
                editor.ui.addButton('events', {
                    label: "Insérez une liste d'événements",
                    icon: this.path + 'icons/events.png',
                    command: 'events',
                    toolbar: 'insert'
                });
        ;
    }
});

$(function () {
    CKEDITOR.config.addWidgetCss("ehRepeat");
});

function styleToggle(e) {
    var all = 1 * ($(e.target).val() === "all" && $(e.target).prop('checked'));
    $('div.bytitle').removeClass('style0 style1').addClass('style' + all);
}