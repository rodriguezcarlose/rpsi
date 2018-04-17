(function (w, d, $, m, undefined) {

    $.validator.addMethod('dateformat', function (value, element, options) {

        console.log(value);

        return true;

    });

}(window, document, jQuery, moment));