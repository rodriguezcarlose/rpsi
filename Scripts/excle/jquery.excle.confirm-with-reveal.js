/* =================================================
jQuery Ex-Cle Confirm Whit Foundation
----------------------------------------------------
Version: 0.9.8-beta
Propiedad: Ex-Cle s.a
WebSite: http://www.ex-cle.com.ar

Advertencia: este plugin depende del script jQuery
y del framework de front-end Foundation 5 (funciona-
lidad del reveal)
================================================= */

(function ($, window, document, undefined) {

    /* =================================================
        METODO jQuery ecconfirm
    ================================================= */

    $.fn.extend({

        ecconfirm: function (_event, _options) {

            /* -------------------------------------------------
           Emulo una "sobrecarga"
           ------------------------------------------------- */

            if (typeof _event !== 'string') { // Si el evento no es un string

                _options = _event; // Se define como options

                _event = 'click'; // Y asigno evento por defecto

            }

            _options = _options || {};

            /* -------------------------------------------------
            Le agrego un namespace a cada evento
            ------------------------------------------------- */

            var

            _event_compiled = _event.split(' ').join('.ex-cle.InitConfirm ') + '.ex-cle.InitConfirm',

            _InitC = function (event) {

                event.preventDefault(); // Evito que se dispare el evento Submit

                event.stopImmediatePropagation(); // Evito bubbling de eventos

                $.ecconfirm.call(this, _options);

            };

            /* -------------------------------------------------
            Confirm por defecto
            ------------------------------------------------- */

            if (

              (typeof _options !== undefined && $.isPlainObject(_options)) && // SI options es un objeto Y

              (!_options.hasOwnProperty('Confirm') || // NO tiene la propiedad Confirm O

              !$.isFunction(_options.Confirm)) // NO es una funcion

            ) {

                _options.Confirm = function () {

                    var
                    event = _event.split(' ')[0], // Utilizo el primer evento
                    target = $(this);

                    /* -------------------------------------------------
                    Gestiono las acciones por default segun los eventos
                    ------------------------------------------------- */

                    switch (event) {

                        /* -------------------------------------------------
                        Casos para los eventos SUBMIT
                        ------------------------------------------------- */

                        case 'submit':

                            /* Emular submit de un formulario */

                            target.off(_event_compiled).trigger(event).on(_event_compiled, _InitC); // Disparo evento

                            break;

                            /* -------------------------------------------------
                            Casos para los eventos CLICK
                            ------------------------------------------------- */

                        case 'click':

                            /* Emular click de link */

                            if (target.is('a')) // Si es un anchor
                                window.location = target.attr('href'); // direcciono a la url del href del elementos

                            break;

                            /* -------------------------------------------------
                            Accion por defecto para los casos no contemplados
                            ------------------------------------------------- */

                        default: target.off(_event_compiled).trigger(event).on(_event_compiled, _InitC); // Disparo evento

                    }



                }

            }

            /* -------------------------------------------------
            Asigno evento y ejecuto la funcion ecconfirm a cada
            elemento seleccionado
            ------------------------------------------------- */

            this.each(function (index) { // Para cada elemento

                $(this).off(_event_compiled).on(_event_compiled, _InitC); // Evito la multiple adición del mismo evento

            });

            return this;

        }

    });


    /* =================================================
     FUNCION jQuery ecconfirm
    ================================================= */

    $.ecconfirm = function (_options) {

        /* -------------------------------------------------
        Emulo una "sobrecarga"
        ------------------------------------------------- */

        if ($.isFunction(_options)) // Si _options es una funcion

            _options = { Confirm: _options }; // lo defino como metodo Confirm

        else if (!$.isPlainObject(_options))  // Si _options no es un objeto

            _options = {}; // se le asignara valor por defecto

        /* =================================================
         PROPIEDADES (configuración)
        ================================================= */

        var _default = {

            className: 'tiny',

            title: 'Confirmar acción',

            message: '',

            accept: {

                text: 'Aceptar',

                className: '',

            },

            cancel: {

                text: 'Cancelar',

                className: '',

            },

            reveal: '',


            /* =================================================
             CALLBACKS
            ================================================= */

            Middleware: function () { return true }, // Evita abortar el flujo de ejecución

            Abort: function () { },

            Confirm: function () { },

            Revoke: function () { },

            Finally: function () { }

        },


    /* =================================================
     PROPIEDADES
    ================================================= */

        target = (this instanceof HTMLElement) ? this : {}, // Si no es un elemento lo defino como un Object

        setting = $.extend(true, {}, _default, _options); // extiendo las funcionalidades

        /* =================================================
         FUNCIONES
        ================================================= */

        /* -------------------------------------------------
        Creo los elementos, los anido y les asigno el atri-
        buto data
        ------------------------------------------------- */

        _CreateReveal = function () {

            var div = $('<div/>').addClass('reveal-modal').attr('data-reveal', '');

            $('<h2/>').attr('data-confirm', 'title').appendTo(div);

            $('<p/>').attr('data-confirm', 'message').appendTo(div);

            $('<button/>').attr('data-confirm', 'accept').appendTo(div);

            $('<button/>').attr('data-confirm', 'cancel').appendTo(div);

            $('<a/>').addClass('close-reveal-modal').attr('aria-label', 'Close').html('&#215;').appendTo(div);

            return div;

        },

        /* -------------------------------------------------
        Asigno los valores correspondientes segun sus atri-
        butos data
        ------------------------------------------------- */

        _ComposerReveal = function (reveal) {

            this._reveal.attr('data-options', 'close_on_background_click:false;close_on_esc:false;');

            this._reveal.addClass(this.className);

            this._reveal.find('[data-confirm="title"]').html(this.title);

            this._reveal.find('[data-confirm="message"]').html(this.message);

            this._reveal.find('[data-confirm="accept"]')

                  .addClass(this.accept.className)

                  .html(this.accept.text);

            this._reveal.find('[data-confirm="cancel"]')

                  .addClass(this.cancel.className)

                  .html(this.cancel.text);

            return this._reveal;

        },

        /* ---------------------------------------------------------------------
        Evito conflicto al abrir ecconfirms anidados
        --------------------------------------------------------------------- */

        _CloseModal = function (callback) {


            setting._reveal.off('closed closed.fndtn.reveal').on('closed closed.fndtn.reveal', function (event) { // Cuando se cerró el reveal de confirmacion

                event.stopPropagation();

                callback(setting); // Llamo callback Confirm/Revoke

                setting._reveal.remove(); // Remuevo la modal del DOM

                setting.Finally(setting);  // LLamo callback Finally

            })

            .foundation('reveal', 'close'); // Cierro reveal

        },

        /* ---------------------------------------------------------------------
        FLUJO DE EJECUCION
        ------------------------------------------------------------------------
        Ejecuto los callbacks, eventos y condicionales en orden
        correspondiente:

        Middleware ?
            |
            |-> FALSE -> Abort -> Finally
            |
            '-> TRUE -> OPEN -> Reveal
                                    |-> ACCEPT ----------> Confirm --,
                                    |               ↑                |
                                    |             CLOSE              |-> Finally
                                    |               ↓                |
                                    '-> CANCEL ----------> Revoke ---'

        --------------------------------------------------------------------- */

        _Init = function () {

            /* -------------------------------------------------
            Ejecuto el Middleware
            ------------------------------------------------- */

            if ($.isFunction(setting.Middleware) && !setting.Middleware(setting)) // Si Middleware es una funcion que retorna false

            {

                setting.Abort(setting); // Ejecuto callback Abort

                setting.Finally(setting); // Ejecuto callback Finally

            }

            else // De lo contrario continuo la ejecución normalmente

            {

                /* -------------------------------------------------
                Creo la ventana modal y la muestro
                ------------------------------------------------- */

                if (typeof setting.reveal === "string") // Si reveal es un selector jQuery

                    setting._reveal = $(setting.reveal).first().clone(); // Clono el reveal

                else // Sino

                    setting._reveal = _CreateReveal.call(setting); // Creo el reveal

                if (!setting._reveal.length) setting._reveal = _CreateReveal.call(setting); // Si no hay elementos en el DOM creo el reveal

                _ComposerReveal.call(setting) // Agrego los textos al reveal

                .attr('id', ('ecconfirm' + $.now())) // Le asigno un id unico

                .appendTo('body') // Lo agrego al DOM

                .foundation('reveal', 'open'); // Y lo muestro

                /* -------------------------------------------------
                LLamo callback Confirm al aceptar
                ------------------------------------------------- */

                setting._reveal.find('[data-confirm="accept"]').off('click.ex-cle.confirm').on('click.ex-cle.confirm', function (event) { // Si se acepta en la confirmacion

                    event.stopImmediatePropagation();

                    _CloseModal(setting.Confirm); // Cierro la modal, ejecuto callback Confirm y Finally

                });

                /* -------------------------------------------------
                LLamo callback Revoke al aceptar
                ------------------------------------------------- */

                setting._reveal.find('[data-confirm="cancel"], .close-reveal-modal').off('click.ex-cle.confirm').on('click.ex-cle.confirm', function (event) { // Si se revoca en la confirmacion

                    event.stopImmediatePropagation();

                    _CloseModal(setting.Revoke); // Cierro la modal, ejecuto callback Revoke y Finally

                });

            }

        };

        /* --------------------------------------------------
         Bootstrap
        ---------------------------------------------------*/

        setting.Middleware = setting.Middleware.bind(target); // Defino el objeto "this" de todos los callbacks
        setting.Abort = setting.Abort.bind(target);
        setting.Confirm = setting.Confirm.bind(target);
        setting.Revoke = setting.Revoke.bind(target);
        setting.Finally = setting.Finally.bind(target);

        _Init();

        return target;
    };

}(jQuery, window, window.document));
