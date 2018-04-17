/**
 * 
 */
(function (w, d, $, undefined) {

    //var $body = $(body);

    var subNav, snippet, _fn;

    $.excle = $.excle || {};

    snippet = $.excle.topBarMenu = function() { // Closure

        /**
         * Constructor
         * @param {} options 
         * @returns {} 
         */
        var _topBarMenu = function (options) {

            var self = this; // Guardo el objeto TopBar para evitar conflicto

            self.config = $.extend({}, $.excle.topBarMenu.defaults, options);
            self.$ = {};
            self.$.topBar = $(self.config.element).first();

            // Validaciones y Requerimientos
            if (self.$.topBar.length > 0) {

                self.id = self.$.topBar.attr('data-topbar-menu');
                self.$.responsive = $('[data-topbar-menu-id="' + self.id + '"]');
                self.$.responsiveIcon = self.$.responsive.find('i.fi-list');
                self.$.allSections = self.$.topBar.find('.section');
                self.$.logo = self.$.allSections.filter('.logo');
                self.$.sections = self.$.allSections.not(self.$.logo);
                self.$.submenus = self.$.sections.children('.sub-menu');
                self.config.useClass = (self.config.useClass + ' ' + (this.config.animation ? 'animate' : '')).trim();
                self.config.expandedClass = ('expanded' + ' ' + (this.config.animation ? 'animate' : '')).trim();
                self.breakpoint = {
                    small: 640,
                    medium: 1024
                }
                
                _fn.responsiveEventTrigger.call(self);

                if (self.windowWidth <= self.breakpoint.small) {
                    _fn.closeMenu.call(self);
                }

                if (self.windowWidth > self.breakpoint.small) {
                    _fn.openMenu.call(self);
                }

                self.$.sections.find('ul[data-subnav]').each(function() {

                    new subNav($(this), self);

                });

                // EVENTOS

                $(w).on('resize.excle.topbar', function () {

                    _fn.responsiveEventTrigger.call(self);

                });

                self.$.responsive.on('click.excle.topbar', function() {

                    if (self.$.topBar.hasClass(self.config.expandedClass)) { // Acciones para contraer

                        _fn.closeMenu.call(self);

                    } else { // Acciones para expandir

                        _fn.openMenu.call(self);

                    }

                });

                if (self.config.event === 'click') {

                    self.$.sections.find('.item').on('click.excle.topbar', function(e) {
                        
                        var item = $(this),
                            section = item.parent('.section'),
                            isOpen = section.hasClass(self.config.useClass);

                        self.closeAll();

                        return isOpen || self.open(section);

                    });

                    $(document).on('click.excle.topbar', function (e) {

                        var target = $(e.target),
                            parent = target.closest(self.$.sections);

                        if (parent.length === 0) self.closeAll();

                    });

                } else {

                    self.$.sections.hover(
                    function () {

                        self.open(this);

                    },
                    function () {

                        self.close(this);

                    });

                }
            }

        }

        /**
         * Factory de la clase TopBar
         * 
         * Esta es la funcion que se guardará
         * en el objeto jQuery.excle.topBarMenu.
         * Funciona como una factory, prepara el 
         * objeto y lo retorna. En este caso se aplica
         * el prototype de nuestra clase TopBar utilizando
         * el objeto jQuery.excle.topBarMenu.fn
         */
        return function(options) {
            _topBarMenu.prototype = $.excle.topBarMenu.fn;
            return new _topBarMenu(options);
        }

    }(); // End Closure

    /**
     * Valores por defecto
     * 
     * En este objeto defino todos los valores
     * por defecto que utilizará la clase TopBar.
     * ---------------------------------------
     * Mediante esta técnica el plugin nos permite
     * modificar dinamicamente dichos valores.
     */
    $.excle.topBarMenu.defaults = {
        element: '[data-topbar-menu]:eq(0)',
        useClass: 'open',
        event: 'click',
        animation: true,
        topMargin: 0
    };

    /**
     * Métodos
     * 
     * En este objeto se guardaran los métodos
     * de la clase TopBar. 
     * Estos métodos serán utilizados por 
     * la funciona anónima (Factory)
     * ---------------------------------------
     * Mediante esta técnica el plugin nos permite
     * modificar o agregar dinamicamente métodos
     * del mismo como en jQuery.fn.
     */
    $.excle.topBarMenu.fn = {

        open: function(section) {

            this.$.sectionActive = $(section).addClass(this.config.useClass);
            this.$.topBar.addClass(this.config.expandedClass);

            //_fn.reloadSubMenuPosition.call(this, this.$.subMenuActive = this.$.sectionActive.find('.sub-menu').first());

            this.$.subMenuActive = this.$.sectionActive.find('.sub-menu').first();

            _fn.responsiveEventTrigger.call(this);

            return this;

        },

        close: function(section) {

            $(section).removeClass(this.config.useClass);
            //if (this.windowWidth > this.breakpoint.small) this.$.topBar.removeClass(this.config.expandedClass);
            return this;
        },

        closeAll: function() {
            this.$.sections.removeClass(this.config.useClass);
            //if (this.windowWidth > this.breakpoint.small) this.$.topBar.removeClass(this.config.expandedClass);
            return this;
        }
    };

    /**
     * Funciones utiles.
     * 
     * En este objeto defino funciones utiles
     * para hacer operaciones con los TopBar.  
     */
    _fn = snippet.util = { // METODOS UTILES 

        openMenu: function() {
            
            this.$.topBar.addClass(this.config.expandedClass);

            this.$.responsive.addClass('open');

            _fn.responsiveEventTrigger.call(this);

        },

        closeMenu: function() {
            
            this.$.topBar.removeClass(this.config.expandedClass);

            this.$.responsive.removeClass('open');

            _fn.responsiveEventTrigger.call(this);

        },

        responsiveEventTrigger: function () {

            var self = this;

            self.$.topBar.css('marginTop', self.config.topMargin);

            self.windowWidth = w.innerWidth;
            self.windowHeight = w.innerHeight;

            self.menuHeight = parseInt(self.$.topBar.get(0).offsetHeight); //parseInt(self.$.topBar.height());
            self.menuOffset = self.$.topBar.offset().top;
            self.subMenuTop = self.menuHeight + self.config.topMargin;
            self.subMenuArea = self.windowHeight - self.subMenuTop;

            d.body.style.overflow = 'auto';

            self.$.submenus.css({
                'bottom': 'auto',
                'height': 'auto',
                'overflowY': 'hidden'
            }).perfectScrollbar('destroy');

            if (self.windowWidth <= self.breakpoint.small) { // Only small

                _fn.reloadMobileMenu.call(self);

                self.subMenuTop = self.config.topMargin;

            }

            if (self.windowWidth > self.breakpoint.small && // Only Medium
                self.windowWidth <= self.breakpoint.medium) {
                
            }

            if (self.windowWidth > self.breakpoint.medium) { // Large Up
                
            }

            if (self.windowWidth > self.breakpoint.small) { // Medium Up

                _fn.reloadSubMenuPosition.call(self);
                
                self.$.topBar.perfectScrollbar('destroy');

            }

            _fn.reloadMenuHeight.call(self);

            // Always

        },

        reloadMobileMenu: function ( isExpanded ) {

            var self = this;

            isExpanded = isExpanded || self.$.topBar.hasClass(self.config.expandedClass);
            
            if (isExpanded) { // Expandido

                d.body.style.overflow = 'hidden';
                self.$.topBar.perfectScrollbar();

            } else { // Cerrado

                d.body.style.overflow = 'auto';
                self.$.topBar.perfectScrollbar('destroy');
            }

        },

        reloadSubMenuPosition: function (subMenu) {

            var self = this, oHeight;

            subMenu = subMenu || self.$.subMenuActive;

            if (!subMenu) return;
            
            oHeight = subMenu.outerHeight();

            if (oHeight >= self.subMenuArea) {

                subMenu.css({
                    'bottom': '0px',
                    'height': self.windowHeight - self.subMenuTop,
                    'overflow': 'auto'
                }).perfectScrollbar();

                d.body.style.overflow = 'hidden';

            }

        },


        // Defino la distancia del menu con el top del window y la ubicacion de los submenus. 
        reloadMenuHeight: function () {

            var self = this;

            self.$.submenus.css('top', self.subMenuTop);

            d.body.style.paddingTop = self.subMenuTop + 'px';

            return self;

        }
    };

    /* OBJETO SUB NAV */

    (function() {
     
        subNav = function ($element, _topBar) {

            var self = this;
            self.topBar = _topBar;
            self.$ = {};
            self.$.list = $element;
            self.$.li = self.$.list.find('li');
            self.$.expandables = self.$.li.filter('.expandable');
            self.$.expandAnchor = self.$.expandables.children('a');
            self.$.backToHome = self.$.list.find('.backToHome').first().hide(); //$('<li class="backToHome">Volver</li>');
            self.$.section = self.$.list.closest(_topBar.$.allSections);

            self.$.list.prepend(self.$.backToHome);

            self.init();

            self.$.backToHome.on('click.excle.subnav', function() {

                self.init();

                return false;

            });

            self.$.section.children('.item').on('click.excle.subnav', function () {

                self.init();

                return false;

            });

        };

        subNav.prototype = {

            init: function () {

                var self = this;
                
                self.$.backToHome.hide('fast');
                self.$.li.removeClass('hidden expanded active');
                self.$.expandAnchor
                .off('click.excle.subnav')
                .on('click.excle.subnav', function () {

                    var
                        $anchor = $(this),
                        $item = $anchor.parent(self.$.expandables),
                        $siblings = $item.siblings('li');

                    self.expand($anchor, $item, $siblings);

                    self.$.backToHome.slideDown('fast');

                    return false;

                });

            },

            expand: function ($anchor, $item, $siblings) {

                var self = this;

                $item.find(self.$.expandables).each(function() {

                    var $item = $(this),
                        $anchor = $item.children('a'),
                        $siblings = $item.siblings('li');

                    self.contract($anchor, $item, $siblings);

                });

                self.$.li.removeClass('active');
                
                $siblings
                    .removeClass('expanded')
                    .addClass('hidden');

                $item
                    .removeClass('hidden expanded')
                    .addClass('expanded active');

               

                //setTimeout(function() {

                //    _fn.reloadMenuHeight.call(self.topBar); 
                //    _fn.reloadSubMenuPosition.call(self.topBar);

                //}, 50);


                _fn.responsiveEventTrigger.call(self.topBar);


            },

            contract: function ($anchor, $item, $siblings) {

                var self = this;

                $item.removeClass('expanded');

                $siblings.removeClass('hidden');

                $anchor.off('click.excle.subnav').on('click.excle.subnav', function () {

                    self.expand($anchor, $item, $siblings);

                    return false;

                });
                
            }
            

    };

    }());

}(jQuery.isWindow(window) ? window : this, document, jQuery));


/**
 * 
 */
