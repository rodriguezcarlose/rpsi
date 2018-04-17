(function(w, d, $, undefined) {
    
    $.validator.setDefaults({
        showErrors: function (errorMap, errorList) {
            //debugger;
            //this.defaultShowErrors();

            // destroy tooltips on valid elements
            $(this.currentElements).trigger('reset.excle.validator', [this]);

            // add/update tooltips 
            for (var i = 0, error = errorList[i]; error; i += 1, error = errorList[i]) {

                $(error.element).trigger('invalid.excle.validator', [error.message, this]);

            }
        }
    });

    var getElementToShowError = function (formElement) {

        var ele = $(formElement), returnElement = ele;

        // is autocomplete
        if (typeof ele.attr('data-autocomplete') !== typeof undefined) {

            returnElement = ele.closest('.ec-ac-widget, .ec-ac-multiple-widget').find('.ec-ac-search').first();

            if (returnElement.length > 0) return returnElement;

        }

        // is datepicker
        if (typeof ele.attr('data-datetimepicker') !== typeof undefined) {

            returnElement = ele.siblings('.ec-dp-show-input').first();

            if (returnElement.length > 0) return returnElement;

        }

        return returnElement;
    }

    $('[data-val]')
    .on('reset.excle.validator', function (e, validator) {

        var ele = getElementToShowError(this);

        ele.removeAttr('title').removeClass(validator.settings.errorClass);
        Foundation.libs.tooltip.getTip(ele).remove();

    })
    .on('invalid.excle.validator', function (e, message, validator) {

        var ele = getElementToShowError(this);

        ele.attr('title', message).addClass(validator.settings.errorClass);
        Foundation.libs.tooltip.create(ele);

        ele
        .focusin(function () { Foundation.libs.tooltip.show(ele); })
        .focusout(function () { Foundation.libs.tooltip.hide(ele); })
        .foundation('tooltip', 'reflow');

    });

}(window, document, jQuery))