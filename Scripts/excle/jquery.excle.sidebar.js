(function(w, d, $, undefined) { // Closure

    $.excle = $.excle || {};

    // Snippet
    var
    _background = $("#Menu-resposive-background"),
    _snippet = $.excle.sideBar = function () {

        var
            // Constructor
            _sideBar = function (opt) {

                var self = this;
                self.config = $.extend({}, _snippet.defaults, opt);
                self.$ = {};
                self.$.menu = $(self.config.element);

                if (!self.$.menu.length) return;

                self.$.menu = self.$.menu.removeClass('open close').addClass('init');
                self.$.items = self.$.menu.find('li');
                self.$.expandables = self.$.items.filter('.expandable').removeClass('open close').addClass('close');
                self.$.expAnchors = self.$.expandables.children('a');
                self.$.pin = self.$.menu.find('.pin');

                self.mediaQuery = w.matchMedia("(max-width: 1024px)"),
                self.color = self.$.menu.find('a:first').first().css('color');
                self.scrollTop = parseInt(self.$.menu.css('top'));
                self.menuClosedTimeOut;

                _fn.scrollSwitch.call(self);
                _fn.widthChange.call(self);

                self.$.items.filter('.active').parents(self.$.expandables).addClass('hasActive');

                self.$.expAnchors.on('click.excle.sidebar', function () {

                    var link = $(this),
                    li = link.parent(self.$.expandables),
                    brothers = {};

                    if (!li.hasClass('open')) {

                        brothers = li.siblings(self.$.expandables).filter('.open');

                        if (brothers.length > 0) {

                            brothers.children('a').css({ 'background-color': 'transparent', 'color': self.color });

                        }

                        _fn.closeAnimate.call(self, brothers, function () {

                            brothers
                                .removeClass('open').addClass('close')
                                .children('a').attr('style', '');

                        });

                        li.removeClass('close').addClass('open');

                    } else {
                        
                        li.removeClass('open').addClass('close');

                    }

                    return false;

                });
                
            };

        // Factory
        return function (opt) {
            _sideBar.prototype = _snippet.fn;
            return new _sideBar(opt);
        }

    }(),

    // Metodos privados
    _fn = _snippet._fn = {

        closeAnimate: function (expandable, Handler) {

            var self = this;

            expandable.children('ul').animate(
                { "height": 0, "opacity": 0, "margin": 0, "padding": 0 },
                250, function () {
                    Handler();
                    $(this).attr("style", "");
                }
            );

        },
        openAnimate: function () { },
        scrollSwitch: function () {

            var self = this;

            $(w).on('scroll.excle.sidebar', function () {

                if (w.pageYOffset > self.scrollTop) {

                    self.$.menu.addClass('fixed');
                    return;
                };

                self.$.menu.removeClass('fixed');

            })
            .trigger('scroll.excle.sidebar');

        },
        widthChange: function () {

        //if (mediaQuery.matches) { // PARA MOBILE O TABLET

        //    menu.off();

        //    if (menu.hasClass('open')) {

        //        pin.add(bakgrnd).css('display', 'block').click(closeWithPin);

        //    } else {

        //        pin.css('display', 'block').click(openWithPin);

        //    }

        //} else { // PARA DESKTOP

        //}

            var self = this;

            self.$.pin.add(_background).css('display', 'none').off();

            self.$.menu.hover(
            function () {

                clearTimeout(self.menuClosedTimeOut);
                self.openMenu();
            },

            function () {

                self.menuClosedTimeOut = setTimeout(function () {
                    self.closeMenu();
                }, 250);

            });

        }

    };

    // Metodos publicos
    _snippet.fn = {

        openMenu: function() {
            this.$.menu.removeClass('open close init').addClass('open');
        },
        closeMenu: function() {
            
            var
            self = this,
            exp = self.$.expandables.filter('.open'),
            close = function () {
                self.$.menu
                    .removeClass('open close init').addClass('close')
                    .find(exp)
                        .removeClass('open').addClass('close');
            }

            if (exp.length > 0)
                _fn.closeAnimate.call(self, exp, close);
            else close();

        },
        closeWithPin: function () { },
        openWithPin: function () { }

    };

    // Funciones utiles
    _snippet.util = {


    };
    
    // Valores por defecto
    _snippet.defaults = {

        element: '#EC-Menu'

    };

}(jQuery.isWindow(window) ? window : this, document, jQuery));