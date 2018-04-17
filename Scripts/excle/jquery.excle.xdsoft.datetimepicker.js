var _ExCleSnippetDateTimePicker = function (w, d, $, undefined) {

    $.excle = $.excle || {};

    var

        // Factory
        _plugin = $.excle.datetimepicker = function (options) {

            var config = $.extend({}, _defaults, options);

            if (config.element instanceof HTMLInputElement) {

                if (config.element._ExCleSnippetDateTimePicker) return config.element._ExCleSnippetDateTimePicker;

                return config.element._ExCleSnippetDateTimePicker = new _.init(config);

            }

            return false;

        },

        // Default Values
        _defaults = _plugin.defaults = {},

        // Static Data
        _data = _plugin.data = {

            culture: function() {

                var culture, index = navigator.language.indexOf('-');

                culture = navigator.language.trim();

                if (index >= 0) {

                    culture = navigator.language.substring(0, index).toLowerCase().trim();
                }

                return !!culture ? culture : _data.defaultCulture;

            }(),

            defaultFormat: 'Y-m-d',

            defaultCulture: 'en',

            format: {
                ar: '',
                az: '',
                bg: '',
                bs: '',
                ca: '',
                ch: 'Y-m-d',
                cs: '',
                da: '',
                de: 'd.m.Y',
                el: '',
                en: 'm/d/Y',
                es: 'd/m/Y',
                et: '',
                eu: '',
                fa: '',
                fi: '',
                fr: '',
                gl: '',
                he: '',
                hr: '',
                hu: '',
                id: '',
                it: '',
                ja: '',
                ko: '',
                kr: '',
                lt: '',
                lv: '',
                mk: '',
                mn: '',
                nl: '',
                no: '',
                pl: '',
                pt: '',
                ro: '',
                ru: '',
                se: '',
                sk: '',
                sl: '',
                sq: '',
                sr: '',
                sv: '',
                th: '',
                tr: '',
                uk: '',
                vi: '',
                zh: ''
            },

            hiddenStyle: {
                width: '0px',
                height: '0px',
                margin: '0px',
                padding: '0px',
                position: 'absolute',
                visibility: 'hidden'
            }

    },

    // Private methods
    _ = {

        init: function (config) { // Constructor

            this.config = config;

            _.create.call(this);

        },

        create: function () {

            this.$input = $(this.config.element);
            this.$showInput = $(d.createElement('input')).addClass('ec-dp-show-input tip-top');
            this.$container = $(d.createElement('div')).addClass('ec-dp-container');
            this.$input.addClass('ec-dp-input');

            this.$container.insertBefore(this.$input);
            this.$container.append(this.$input, this.$showInput);

            this.$input.css(_data.hiddenStyle);

            _.events.call(this);

        },

        events: function () {

            var self = this,
                date = new Date(self.$input.val()),
                dateString = !!date.getTime() ? date.toISOString() : '',
                format = self.getFormat();

            self.$showInput.val(dateString).attr('placeholder',format).datetimepicker({
                timepicker: false,
                format: format,
                lang: _data.culture,
                onChangeDateTime: function (time, $input) {

                    var time = time ? time.toISOString() : '';

                    self.$input.val(time);
                    self.$showInput.attr('data-iso-datetime', time);

                }

            }).trigger('blur.xdsoft');

            self.$showInput.on('focusout', function () { self.$input.trigger('focusout') });
            self.$showInput.on('focusin', function () { self.$input.trigger('focusin') });
            self.$showInput.on('change', function () { self.$input.trigger('change') });

        }

    },

    // Public methods
    _fn = _plugin.fn = {

        constructor: _plugin,

        getFormat: function () {

            var format;

            if (_data.culture in _data.format) {

                format = _data.format[_data.culture];

                if (!!format) return format;

            }

            return  _data.defaultFormat;

        }

    };

    _plugin.prototype = _.init.prototype = _fn;

    $.datetimepicker.setLocale(_data.culture);

    //$.datetimepicker.setLocale('ru');

    $('[data-datetimepicker]').each(function () {

        _plugin({ element: this });

    });

    return _plugin;

}(window, document, jQuery); // End

