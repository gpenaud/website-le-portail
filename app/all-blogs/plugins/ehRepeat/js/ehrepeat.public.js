

$(document).ready(reload_calendar);


function reload_calendar() {
    if ($(".calendar-array").length == 0)
        return;
    $(document).ajaxError(function (e, xhr, opt) {
        console.log("Error requesting " + opt.url + ": " + xhr.status + " " + xhr.statusText);
    });

    if (window.ehdates == undefined) {
        window.updateCalDate = {year: 0, month: 0};

        $.getJSON('/rest/ehrepeat', {
            f: 'getAllRepeatEventsForCalendar',
            dummy: 0
        }, function (result, status) {
            window.ehdates = [];
            parseDatesList(result.data);
            var curmonth = $(".calendar-array caption").attr('title');
            window.updateCalDate.year = curmonth.substr(0, 4);
            window.updateCalDate.month = curmonth.substr(4);
            updateCal();
        });
    } else {
        var curmonth = $(".calendar-array caption").attr('title');
        window.updateCalDate.year = curmonth.substr(0, 4);
        window.updateCalDate.month = curmonth.substr(4);
        updateCal();
    }

    $(".calendar-array").find("a.prev,a.next").click(function () {
        console.log("a pressed");
        window.updateCalInterval = window.setInterval(function () { //waiting for the eventhandler ajax request to occur
            var datestr = window.updateCalDate.year + "" + window.updateCalDate.month;
            console.log("interval :", datestr, '<>', $(".calendar-array caption").attr('title'));
            if ($(".calendar-array caption").attr('title') != datestr) {
                // var curmonth = $(".calendar-array caption").attr('title');
                // window.updateCalDate.curyear = curmonth.substr(0,4);
                // window.updateCalDate.curmonth = curmonth.substr(4);
                // updateCal();
                reload_calendar();
                window.clearInterval(window.updateCalInterval);
            }
        }, 200);
    });
}

function parseDatesList(dateslist) {
    for (i in dateslist) {
        for (date in dateslist[i].dates) {
            var adate = dateslist[i].dates[date].split(' ')[0].split('-');//year : adate[0], month : adate[1], day : adate[2]
            var y, m, d;
            y = window.ehdates.findIndex(function (v, i, a) {
                return (v.year == adate[0]);
            });
            if (y == -1) {
                y = window.ehdates.push({year: 1 * adate[0], months: new Array()}) - 1;
            }

            m = window.ehdates[y].months.findIndex(function (v, i, a) {
                return (v.month == adate[1]);
            });
            if (m == -1) {
                m = window.ehdates[y].months.push({month: 1 * adate[1], days: new Array()}) - 1;
            }

            d = window.ehdates[y].months[m].days.findIndex(function (v, i, a) {
                return (v.day == adate[2]);
            });
            if (d == -1) {
                d = window.ehdates[y].months[m].days.push({day: 1 * adate[2], events: new Array()}) - 1;
            }
            window.ehdates[y].months[m].days[d].events.push({url: dateslist[i].url, title: dateslist[i].title});
        }
    }
}

function eventsPopup(e) {
    if ($("#events-popup").length == 0) {
        $("body").append("<div id='events-popup'><ul></ul></div>");
    }
    $("#events-popup>ul>*").remove();

    var ul = "<ul>";
    var evt = e.data.events
    for (ev in evt) {
        ul += "<li><a href='/day/" + evt[ev].url + "' title='" + e.data.date + "'>" + evt[ev].title + "</a></li>";
    }
    ul += "</ul>";

    $("#events-popup>ul").replaceWith(ul);
    var w = $("#events-popup").width();
    console.log(w);
    $("#events-popup").css("top", (e.pageY - 7) + "px").css("left", (e.pageX - w / 1.2) + "px").fadeIn();
    $("#events-popup").mouseleave(function () {
        $(this).fadeOut();
    });
}

function dayClicked(e) {
    e.preventDefault();
    eventsPopup(e);
    return false;
}

function updateCal() {
    var month = 1 * window.updateCalDate.month;
    var year = 1 * window.updateCalDate.year;
    console.log('updateCal(' + month + ',' + year + ');');
    var y, m, d, e;
    console.log(window.ehdates);
    y = window.ehdates.find(function (v, i, a) {
        return (v.year == year);
    });
    m = (y != undefined) ? y.months.find(function (v, i, a) {
        return (v.month == month);
    }) : undefined;

    if (y != undefined && m != undefined) {
        for (d in m.days) {
            d = m.days[d];
            e = d.events;
            $(".calendar-array td").each(function () {
                if ($(this).text() == "" + d.day) {
                    console.log("Trouvé " + d.day + "/" + m.month + "/" + y.year + ". " + e.length + " événement" + ((e.length > 1) ? "s :" : " :"));
                    console.log(e);
                    var link;
                    var A = $(this).find("a");
                    if ($(A).length > 0) { //On a déjà une date
                        var href = new dcURL($(A).attr("href"));
                        var title = $(A).attr("title");
                        if (e.findIndex(function (v, i, a) {
                            console.log("findIndex :" + v.url + "«»" + href.page);
                            return v.url == href.page;
                        }) == -1) {
                            e.push({url: cleanUrl(href.pathname), title: title});
                        }
                    } else {
                        $(this).wrapInner("<a href='/day/" + e[0].url + "' title='" + e[0].title + "'></a>");
                        $(this).addClass('eventsday');
                        A = $(this).find("a");
                    }
                    if (e.length > 1) {
                        $(A).attr('title', e.length + " événements");
                        $(A).click({events: e, date: (d.day < 10 ? "0" : "") + d.day + " " + $(".calendar-array caption a.current").text()}, function (e) {
                            return dayClicked(e);
                        });
                    } else if (e.length == 1) {

                    } else {
                        console.log("Erreur : pas d'événement pour " + d.day + "/" + m.month + "/" + y.year + " pourtant présent dans window.ehdates");
                    }
                    return false; //pour interrompre le each
                }
            });
        }
    }
}

/*Remove everything but the part following "day/" from the event url*/
function cleanUrl(url){
    return url.replace(/^(?:[^\/]*\/?)*day\//,"");
}

/* Extension to class URL*/


dcURL.prototype = Object.create(URL.prototype);
dcURL.prototype.type = "";
dcURL.prototype.page = "";

function dcURL(url) {
    console.log("Constructeur de dcURL pour «" + url + "»");
    URL.call(this, url);

    var path = this.pathname;

    if (path.startsWith('/index.php/'))
        path = path.slice(11);
    var aPath = path.split('/');
    this.type = aPath.shift();
    this.page = aPath.join('/');
}

/*Class URL*/

function URL(url) {
    console.log("Constructeur d'URL pour «" + url + "»");
    if (url == undefined) {
        url = window.location;
    }
    this.href = url;

    if ((qmark = url.indexOf('?') != -1) && (qmark != url.length - 1)) {
        this.search = decodeURI(url.slice(qmark + 1));
        url = url.slice(0, qmark);
    }
    if ((hash = url.indexOf('#') != -1) && (hash != url.length - 1)) {
        this.hash = decodeURI(url.slice(hash + 1));
        url = url.slice(0, hash);
    }
    if ((proto = url.indexOf('://')) != -1) {
        this.protocol = url.slice(0, proto + 3);
        url = url.slice(proto + 3);
    }
    if (this.protocol.length > 0) {
        var host = url.indexOf('/');
        if (host == -1 || host == url.length - 1) {
            this.host = url;
        }
        this.host = url.slice(0, host);
        url = url.slice(host);
    }
    if ((port = this.host.indexOf(':')) != -1) {
        this.port = this.host.slice(port + 1);
        this.hostname = this.host.slice(0, port);
    }
    this.pathname = decodeURI(url);

    Object.defineProperty(this, "origin", {get: function () {
            return this.protocol + this.host;
        }
    })

    Object.defineProperty(this, "query", {get: function () {
            var obj = {};
            var aSearch = this.search.split('&');
            for (s in aSearch) {
                var aS = s.split('=');
                obj[aS[0]] = aS[1];
            }
            return obj;
        }
    });

    Object.defineProperty(this, "path", {get: function () {
            var ret = this.pathname;
            if (this.hash.length > 0)
                ret += "#" + this.hash;
            if (this.search.length > 0)
                ret += "?" + this.search;
            return ret;
        }
    });

    this.isAbsolute = function () {
        if (this.protocol.length)
            return true;
        return (this.pathname.startsWith('/'));
    }
}