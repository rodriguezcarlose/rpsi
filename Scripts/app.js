var UiCulture = window.navigator.userLanguage || window.navigator.language;
$(function () {
    
    $(document).foundation();

    //Added functionality for MSIE detecction after jquery 1.9
    jQuery.browser = {};
    (function () {
        jQuery.browser.msie = false;
        jQuery.browser.version = 0;
        if (navigator.userAgent.match(/MSIE ([0-9]+)\./)) {
            jQuery.browser.msie = true;
            jQuery.browser.version = RegExp.$1;
        }
    })();

    //Modal close button
    $('#ModalDialogMessagesCloseButton').click(function () {
        $('#ModalDialogMessages').foundation('reveal', 'close');
    });

    BindSpinner();


    /***********************/
    /*Menu******************/
    /***********************/
    $('.menuIcon').click(function(e) {
        var local = this;
        var list = [];

        $('#NewMenu .menuIcon').each(function(i, e) {
            if (e != local) {
                //Agreglo los elementos a la lista 
                list.push(e);

                //si termino la lista
                if ($('#NewMenu .menuIcon').length == i + 1) {
                    clearMenuItems(list,local);
                }
            }
        });

    });

    $('.menuBack').click(function(e) {
        $('#NewMenu .menuIcon').each(function (i, e) {
            $('.menuList').fadeOut('', function() {
                $('.menuIcon').fadeIn(); 
            });
        });
    });

    function clearMenuItems(elementList, element) {

        if (elementList.length > 0) {
            var el = elementList.pop();
            $(el).hide('fast', function() {
                clearMenuItems(elementList, element);

                if (elementList.length == 0) {
                    showSubMenu(element);
                }

            });
        }

    }

    function animateFadeOut() { }

    function moveSubMenu() { }

    function showSubMenu(element) {
        $(element).parent().find('.menuList').fadeIn();
    }

    /**
     * Al resetear un formulario disparo el evento "blur.xdsoft" por cada input del mismo.
     * Este evento es capturado por el plugin "jquery.datetimepicker" para parsear el valor del input.
     * De esta forma al utilizar el metodo .datetimepicker('getValue') luego de resetear un formulario 
     * devolverá el valor correspondiente, y no el valor anterior. 
     * 
     * reseña: 
     * se observa que antes de disparar el evento "blur.xdsoft" se vuelve a resetear el formulario.
     * Esto se debe a que, en javascript, la captura del evento se hace antes de resetear los campos de dicho formulario. 
     * Para capturar los campos reseteados se tienen que traer inline despues de disparar el evento, y 
     * NO mediante la captura del evento. 
     * ----------------------------------
     * ¿Por que se captura el evento reset entonces?
     * Para evitar introducir codigo en cada script que dispara un reset de un formulario, optamos
     * por capturar dicho evento y volver hacer un reset para proseguir con el codigo correctamente. 
     * Podría decirse que se hace un alias o emulacion del evento reset.
     */

    $('form').on('reset', function () {
        
        this.reset();

        $(this).find('input').trigger('blur.xdsoft');

    });

    $(document).on('draw.dt', '.dataTable', function () {

        addResponsiveDataToTable(this);

    });

    $('table').each(function() {

        addResponsiveDataToTable(this);

    });

    // Fix Bug when Drawing an "Autocomplete Select Input" in a inactive Fundation Tab

    var restoreAutocompleteMultiple = function($target){

        $target.find('[data-autocomplete][multiple]').each(function(){

            var autocomplete = this._ExCleSnippetAutocompleteMultiple;

            if(autocomplete && !autocomplete._isFirstReload){

                autocomplete.reload();
                autocomplete._isFirstReload = true;

            }

        });

    };

    $('[data-tab]').on('toggled', function(e, tab){ restoreAutocompleteMultiple(tab)});

});//Fin


// Add attributes and clases to table elements
function addResponsiveDataToTable(table) {

    var table = $(table),
        thead = table.find('thead'),
        th = thead.find('tr:first-child td'),
        tr = table.find('tbody tr');

    table.addClass('responsive');
    tr.removeClass('expand').addClass('contract');

    tr.find('td:first-child').on('click', function () {

        var self = $(this),
            self_tr = self.closest('tr');

        tr.removeClass('expand').addClass('contract');
        self_tr.removeClass('contract').addClass('expand');

    });

    th.each(function (index) {

        var title = this.innerText;

        if (title) tr.children('td:nth-of-type(' + (index + 1) + ')').attr('data-th', title);

    });

}

// Json date to string Date
function jsonDateToString(data) {
    if (data == null) return '1/1/1111';
    var jsonDate = eval(data.replace(/\/Date\((\d+)\)\//gi, "new Date($1)"));
    var day = jsonDate.getDate();
    var month = jsonDate.getMonth() + 1;
    var year = jsonDate.getFullYear();
    return day + '/' + month + '/' + year;
}

// Json date to string Date
function jsonDateTimeToString(data) {
    if (data == null) return '1/1/1111';
    var jsonDate = eval(data.replace(/\/Date\((\d+)\)\//gi, "new Date($1)"));
    var day = ("0" + jsonDate.getDate()).slice(-2);
    var month = ("0" + (jsonDate.getMonth() + 1)).slice(-2);
    var year = jsonDate.getFullYear();
    var hour = jsonDate.getHours();
    var minute = ("0" + jsonDate.getMinutes()).slice(-2);
    var second = ("0" + jsonDate.getSeconds()).slice(-2);
    return day + '/' + month + '/' + year + ' ' + hour + ":" + minute + ":" + second;
}

/*Add Tootips on custom validation elements*************************/
/*******************************************************************/
function AddCustomValidationToolTip(elementSelector, elementId, add, message) {
    $(document).on('close.fndtn.reveal', '[data-reveal]', function () {
        $(elementSelector).removeAttr('data-tooltip');
        $(elementSelector).removeAttr('aria-haspopup');
        $(elementSelector).removeAttr('title');
        $(elementSelector).removeClass('input-validation-error');
        Foundation.libs.tooltip.getTip($(elementId)).remove();
        $(document).foundation('tooltip', 'reflow');
    });

    if (add) {
        $(elementSelector).attr('data-tooltip', '');
        $(elementSelector).attr('aria-haspopup', 'true');
        $(elementSelector).attr('title', message);
        $(elementSelector).addClass('input-validation-error');
        $(document).foundation('tooltip', 'reflow');
        Foundation.libs.tooltip.show($(elementId));
        return;
    } else {
        $(elementSelector).removeAttr('data-tooltip');
        $(elementSelector).removeAttr('aria-haspopup');
        $(elementSelector).removeAttr('title');
        $(elementSelector).removeClass('input-validation-error');
        $(document).foundation('tooltip', 'reflow');
    }
}

/*Ajax Loading******************************************************/
/*******************************************************************/
function UnbindSpinner() {
    $("#LoadingDiv").unbind("ajaxSend");
}

function BindSpinner() {
    $("#LoadingDiv").bind("ajaxSend", function () {
        $(this).show();
    }).bind("ajaxStop", function () {
        $(this).hide();
    }).bind("ajaxError", function () {
        $(this).hide();
    });

    $(document).ajaxError(function (event, jqxhr, settings, thrownError) {
        if (jqxhr.status == 401 || jqxhr.status == 302 || jqxhr.status == 0) {
            modalLogin(ajaxLogin);
        } else {
            modalMessage(attentionTitle, "Ha ocurrido un error. Intentelo nuevamente o contacte a su administrador.", -1, null, null);
        }
    });
};

/*Modal Login*******************************************************/
/*******************************************************************/
function modalLogin(url) {
    var $modal = $('#ModalLogin'),
        $content = $modal.find('#LoginContent'),
        $closeButton = $modal.find('.close-reveal-modal');

    $content.html('').load(url, function() {

        $closeButton.click(function (e) {
            $modal.foundation('reveal', 'close');
        });

        $modal.foundation('reveal', 'open')
              .foundation('reveal', 'reflow');

    });
}

/*Form Functions****************************************************/
/*******************************************************************/
function validateNumberEntry(e) {
    //console.log(e.keyCode);
    if ($.inArray(e.keyCode, [46, 8, 9, 27, 13]) !== -1 ||
        // Allow: Ctrl+A
                (e.keyCode == 65 && e.ctrlKey === true) ||
        // Allow: home, end, left, right
                (e.keyCode >= 35 && e.keyCode <= 39)) {
        // let it happen, don't do anything
        return;
    }
    // Ensure that it is a number and stop the keypress
    if ((e.shiftKey || (e.keyCode < 48 || e.keyCode > 57)) && (e.keyCode < 96 || e.keyCode > 105)) {
        e.preventDefault();
    }
}


/*Grid Functions****************************************************/
/*******************************************************************/
//$('#jqxGrid').jqxGrid('updatebounddata');
function updateGridData(tableName) {
    $('#'+tableName).jqxGrid('updatebounddata');
}

function updateDataTableData(tableName) {
    //$('#' + tableName).ajax.reload();
    $('#' + tableName).DataTable().ajax.reload();
}

/*Modal Editor******************************************************/
/*******************************************************************/
function modalEditor(url) {

    $.get(url, function(html) {

        modalEditorContent(html);

    });
    
}

function modalEditorContent(html) {

    $('#ModalEditor #Content').html(html);
    $('#ModalEditor')
        .foundation('reveal', 'open')
        .foundation('reveal', 'reflow');

    modalEditorActions();

};

function modalEditorActions() {
    $('#btnCloseModalEditor').click(function() {
        closeModalEditor();
    });
}
function closeModalEditor() {
    $('#ModalEditor').foundation('reveal', 'close');
    $('#ModalEditor #Content').html('');
}

/*Modal Messages****************************************************/
/*******************************************************************/
function modalMessage(title, content, type, callback, size) {

    var $reveal = $('.reveal-modal.open'),
        args = arguments;

    if ($reveal.length > 0) {
        toastMessage.apply(this, args);
        return;
    }

    showModalMessage.apply(this, args);

}

function toastMessage(title, content, type, callback, size) {
    //Size
    //null: Tiny
    //1: Normal

    //Type Map
    // -1:Error 
    // 0:Message
    // 1:Valid
    // 2:Alert

    var
        $closeAlert = $('<a>').addClass('close'),
        $alert = $('<div>').addClass('alert-box').attr('data-alert', '').html('<strong>' + title + '</strong> &#183; ' + content).append($closeAlert),
        $toastArea = $('#toastArea'),
        $toastActive = $toastArea.find('[data-alert]'),
        alertHeight,
        toastAreaTop = parseInt($toastArea.css('top'));

    switch (type) {
        case 1:
            $alert.addClass('success');
            break;
        case 2:
            $alert.addClass('warning');
            break;
        case -1:
            $alert.addClass('alert');
            break;
        default:
            break;
        }

    if ($toastActive.length > 3) {
        $toastActive.slice(3).find('a.close').click();
    }

    alertHeight = $alert.prependTo($toastArea).outerHeight();

    $alert.css({ marginTop: -(alertHeight + toastAreaTop), marginBottom: toastAreaTop })
    .animate({ marginTop: 0, marginBottom: 10 }, 'fast');

    $toastArea.foundation('alert', 'reflow');

    $alert.delay(5500).promise().done(function () { $closeAlert.click() });

    $closeAlert.off('click.fndtn.alert').on('click.fndtn.alert', function() {
        $alert.stop()
            .animate({ opacity: 0 }, 100).delay(150)
            .animate({ height: 0 }, 300 , function() { $alert.remove() });
    });

    if (callback instanceof Function) {
        callback();
    }
}

///*Modal Messages****************************************************/
///*******************************************************************/
function showModalMessage(title, content, type, callback, size) {
    //Size
    //null: Tiny
    //1: Normal

    //Type Map
    // -1:Error 
    // 0:Message
    // 1:Valid
    // 2:Alert

    $('#ModalDialogMessages h3').html('');
    $('#ModalDialogMessages p').html('');

    $('#ModalDialogMessages h3').html(title);
    $('#ModalDialogMessages p').html(content);

    $('#ModalDialogMessages').foundation('reveal', 'open');
    $('#ModalDialogMessages').foundation('reveal', 'reflow');

    $('#ModalDialogMessages').removeClass("valid");
    $('#ModalDialogMessages').removeClass("warning");
    $('#ModalDialogMessages').removeClass("error");

    switch (type) {
        case 1:
            $('#ModalDialogMessages').addClass('valid');
            break;
        case 2:
            $('#ModalDialogMessages').addClass('warning');
            break;
        case -1:
            $('#ModalDialogMessages').addClass('error');
            break;
        default:
            break;
    }

    if (size == null) {
        $('#ModalDialogMessages').addClass('tiny');
    }

    if (callback) {
        $(document).on('close.fndtn.reveal', '#ModalDialogMessages[data-reveal]', function () {
            callback();
        });
    }
}

/*******************************************************************/
/*******************************************************************/
function reparseFormValidation() {
    $("form").removeData("validator");
    $("form").removeData("unobtrusiveValidation");
    $.validator.unobtrusive.parse("form");
}

function formSuccess(data) {
    if (data.Code == -1) {
        modalMessage(attentionTitle, data.Message, data.Code, null);
    }
    else if (data.Code == 1) {
        modalMessage(messageTitle, data.Message, data.Code, null);
        //updateGridData("dataTable");
        updateDataTableData("dataTable");
    }
}

function setButtonEvents() {
    $("a.imgDeleteBtn").ecconfirm({
        reveal: '.myConfirm.reveal-modal',
        className: "tiny warning",
        title: attentionTitle,
        message: deleteTitle + "<br/>" + deleteMessage,
        Confirm: function () {
            //window.location = $(this).attr('href');
            $.get($(this).attr('href'), function (data) {
                formSuccess(data);
            });
        }
    });

    $("a.imgEditBtn").click(function (e) {
        e.preventDefault();
        modalEditor(this.href);
        //$('#ModalDialog').foundation('reveal', 'open', this.href);
        //$('#ModalDialog').foundation('reveal', 'reflow');
    });

    $("a.imgDetailsBtn").click(function (e) {
        e.preventDefault();
        modalEditor(this.href);
        //$('#ModalDialog').foundation('reveal', 'open', this.href);
        //$('#ModalDialog').foundation('reveal', 'reflow');
    });
}

// Json date to string Date
function jsonDateToString(data) {
    if (data == null || data == "") return '1/1/1111';
    var jsonDate = eval(data.replace(/\/Date\((\d+)\)\//gi, "new Date($1)"));
    var day = jsonDate.getDate();
    var month = jsonDate.getMonth() + 1;
    var year = jsonDate.getFullYear();
    return day + '/' + month + '/' + year;
}

/*******************************************************************/
/*******************************************************************/
(function ($, window, document, undefined) {

    var _util = {

        DateInputError: function () {

            for (var i = 0; i < arguments.length; i++) {

                var target = arguments[i];

                if (target instanceof $) {
                    target.addClass('tip-top');
                    target.addClass('hasError');

                    target.attr("title", "Ingrese un intervalo correcto");
                    Foundation.libs.tooltip.create(target);

                    //TODO: Fix mouseOver
                    $(target).focusin(function (e) {
                        Foundation.libs.tooltip.show($(this));
                    });
                    $(target).focusout(function (e) {
                        Foundation.libs.tooltip.hide($(this));
                    });
                }
                $(document).foundation('tooltip', 'reflow');
            }

        },

        DateInputCorrect: function () {

            for (var i = 0; i < arguments.length; i++) {

                var target = arguments[i];

                if (target instanceof $) {
                    target.removeClass('tip-top');
                    target.removeClass('hasError');
                    target.removeAttr("title");
                    Foundation.libs.tooltip.getTip(target).remove();
                }
                $(document).foundation('tooltip', 'reflow');
            }

        },

    } // END UTIL

    Date.prototype.Month = function () {

        var mes = this.getMonth() + 1;
        return mes < 10 ? '0' + mes : mes.toString();

    }

    Date.prototype.Day = function () {

        var dia = this.getDate();
        return dia < 10 ? '0' + dia : dia.toString();
    }

    Date.prototype.Hour = function () {

        var hour = this.getHours();
        return hour < 10 ? '0' + hour : hour.toString();
    }

    Date.prototype.Minute = function () {

        var minute = this.getMinutes();
        return minute < 10 ? '0' + minute : minute.toString();
    }

    $.DateInputUI = function (selector) {

        var $this = $(selector);

        if ($this.length === 0) return;

        if ($this.hasClass('hasDatepicker')) return;

        var timepicker = arguments[1] != null && arguments[1] === true ? true : false;

        /*Formato*/
        var format = timepicker ? "d/m/Y H:i" : "d/m/Y";

        /*Max date*/
        var maxDate = arguments[2] != null && arguments[2] === true ? 0 : '31/12/9999';
        
        $this.datetimepicker({
            timepicker: timepicker,
            format: format,
            formatDate: "d/m/Y",
            minDate: "01/01/1870",
            maxDate: maxDate
        });        
    };

/*Usaro solo en reportes, para validar fechas utilizar el validador por atributos*/
/*********************************************************************************/

    $.RangeDatesInputUI = function (_options) {

        /*======================================================================
        EMULO UNA "SOBRECARGA"
        ------------------------------------------------------------------------
        Permite llamar a la funcion RageDateInput de dos maneras:
        1) Pasando un string (selector jQuery) como primer argumento para el 
        elemento "fecha desde" y lo mismo como segundo argumento para elemento "fecha hasta"
        2) Pasando JSON con las diferentes opciones
        ======================================================================*/

        var _From, _To;

        /* Primer argumento */

        if (typeof arguments[0] === "string") _From = $(arguments[0]); // Si es string, lo interpreto como un selector jQuery y lo guardo

        else if (arguments[0] instanceof $) _From = arguments[0]; // Si es un objeto jQuery lo guardo

        /* Segundo argumento */

        if (typeof arguments[1] === "string") _To = $(arguments[1]); // Si es string, lo interpreto como un selector jQuery y lo guardo

        else if (arguments[1] instanceof $) _To = arguments[1]; // Si es un objeto jQuery lo guardo

        if (_From, _To) // Si fueron guardados valores en los dos argumentos

            _options = {
                dateSetting: {
                    timepicker: false,
                    format: "d/m/Y",
                    formatDate: "d/m/Y",
                    minDate: "01/01/1900",
                    maxDate: 0
                },
                from: { element: _From }, // Asigno el primer arguemnto como "from"
                to: { element: _To } // Asigno el segundo argumento como "to"
            }

        if (!$.isPlainObject(_options)) _options = {}; // Si no es un JSON Plano asigno valor por defecto

        var exp = arguments[2] ? /^(([0-2]?[0-9]|3[0-1])[/]([0]?[1-9]|1[0-2])[/][1-2]\d{3}) (20|21|22|23|[0-1]?\d{1}):([0-5]?\d{1})$/ : /^(0[1-9]|[12][0-9]|3[01])[- \/\.](0[1-9]|1[012])[- \/\.](18|19|20)\d\d$/;
        /*======================================================================
        PROPIEDADES 
        ======================================================================*/

        var
            _Now = new Date(),

            _MinDate = new Date(1900, _Now.getMonth(), _Now.getDate()),

            _setting = {},

            /*======================================================================
            VALORES DE CONFIGURACION POR DEFECTO
            ======================================================================*/

            _default = {

                dateExp: {
                    es: exp
                },
            },

            _CheckInterval = function () {
                var from = _setting.from.element.datetimepicker('getValue');
                var to = _setting.to.element.datetimepicker('getValue');

                _util.DateInputCorrect(_setting.from.element, _setting.to.element);

                if ((to && from) && (to < from)) {
                    _util.DateInputError(_setting.from.element, _setting.to.element);
                    return false;
                }

                return true;

            },

            _CheckFormat = function () {

                var from = _setting.from.element.val();
                var to = _setting.to.element.val();

                if (from && !_setting.dateExp.es.test(from)) {
                    _util.DateInputError(_setting.from.element);
                    from = false;
                } else {
                    _util.DateInputCorrect(_setting.from.element);
                    from = true;
                }

                if (to && !_setting.dateExp.es.test(to)) {
                    _util.DateInputError(_setting.to.element);
                    to = false;
                } else {
                    _util.DateInputCorrect(_setting.to.element);
                    to = true;
                }

                return from && to;

            },

            _CheckDates = function () {

                check = _CheckFormat();

                if (check) check = _CheckInterval();

                return check;

            },

        /*======================================================================
        INPUT DATE
        ======================================================================*/

        _Init = function () {

            if (!_setting.from.element.hasClass('hasDatepicker')) {
                _setting.from.element.datetimepicker(_setting.dateSetting);
            }
            if (!_setting.to.element.hasClass('hasDatepicker')) {
                _setting.to.element.datetimepicker(_setting.dateSetting);
            }

            var Checkers = {

                "interval": _CheckInterval,
                "format": _CheckFormat

            }

            $.extend(_setting.from.element.change(_CheckDates).get(0), Checkers);
            $.extend(_setting.to.element.change(_CheckDates).get(0), Checkers);

            //return _CheckInterval;

            return Checkers;

        };

        /* ==============================================
        BOOTSTRAP
        ============================================= */

        $.extend(true, _setting, _default, _options);

        /* ---------------------------------------------
        Validaciones antes de iniciar la ejecución
        --------------------------------------------- */

        try {

            if (!(_setting.from.element instanceof $)) throw "El elemento 'From' tiene que ser un objeto jQuery";
            if (!(_setting.to.element instanceof $)) throw "El elemento 'To' tiene que ser un objeto jQuery";
            if (!(_setting.from.element.length)) throw "No se encontraron elementos en el DOM para 'Form'";
            if (!(_setting.to.element.length)) throw "No se encontraron elementos en el DOM para 'To'";

            _setting.from.element = _setting.from.element.first();
            _setting.to.element = _setting.to.element.first();

            /* Inicio la ejecucion */

            return _Init();

        } catch (err) {

            if (window.console) console.log("Error de la funcion $.RangeDatesInputUI: " + err);
            return false;

        }



    }

    $.RangeNumberInputs = function () {

        var
            _checkInterval = function () {

                var
                    data = this,
                    max_value = new Number(data.max.val()).valueOf(),
                    min_value = new Number(data.min.val()).valueOf();

                _util.DateInputCorrect(data.min, data.max);

                if ((max_value && min_value) && (max_value < min_value)) {
                    _util.DateInputError(data.min, data.max);
                    return false;
                }

                return true;

            },

            _fun = function ($min, $max) {

                var check, data = {};

                data.min = $min.jquery ? $min : $($min);
                data.max = $max.jquery ? $max : $($max);

                check = _checkInterval.bind(data);

                data.min.add(data.max).change(function (e) {

                    check();

                });

                return check;

            }

        return _fun;

    }();

}(jQuery, window, window.document));


