//http://dotnetspeak.com/2012/05/validating-dependent-fields-in-asp-net-mvc
    window.customValidation = window.customValidation ||
    {        
        relatedControlValidationCalled: function (event) {
            if (!customValidation.activeValidator) {
                customValidation.formValidator = $(event.data.source).closest('form').data('validator');
            }
            customValidation.formValidator.element($(event.data.target));
        },
        relatedControlCollection: [],
        formValidator: undefined,
        addDependatControlValidaitonHandler: function (element, dependentPropertyName) {
            var id = $(element).attr('id');
            if ($.inArray(id, customValidation.relatedControlCollection) < 0) {
                customValidation.relatedControlCollection.push(id);

                //Control "Blur" Event
                $(element).on(
                    'blur',
                    { source: $(element), target: $('#' + dependentPropertyName) },
                    customValidation.relatedControlValidationCalled);
            }
        }
    };

var valChecker = [];
if ($.validator && $.validator.unobtrusive) {
    $.validator.addMethod('daterange',
        function (value, element, params) {
            value = new Date($(element).val().replace(/(\d{2})\/(\d{2})\/(\d{4})/, "$2/$1/$3"));;
            var comparteTo = new Date($("#" + params.compareTo).val().replace(/(\d{2})\/(\d{2})\/(\d{4})/, "$2/$1/$3"));

            if (JSON.parse(params.isStartingDate.toLowerCase())) {
                if (value > comparteTo) {
                    $(element).addClass('tip-top');
                    customValidation.addDependatControlValidaitonHandler(element, params.compareTo);
                    return false;
                }
            } else {
                if (value < comparteTo) {
                    $(element).addClass('tip-top');
                    customValidation.addDependatControlValidaitonHandler(element, params.compareTo);
                    return false;
                }
            }
            $(element).removeClass('tip-top');
            customValidation.addDependatControlValidaitonHandler(element, params.compareTo);
            return true;
        });
    $.validator.unobtrusive.adapters.add('daterange', ['propertytested', 'allowequaldates', 'isstartingdate'], function (options) {
        options.rules['daterange'] = {
            allowEqualDates: options.params.allowequaldates,
            compareTo: options.params.propertytested,
            isStartingDate: options.params.isstartingdate
        };
        options.messages['daterange'] = options.message;

        ////data relation
        //$.each(valChecker, function(i,e) {
        //    if (e.propertyName === options.params.propertytested) {
        //        valChecker.splice(i, 1);
        //    }
        //});
        valChecker.push({
            propertyName: options.params.propertytested,
            state: 0
        });
    });
}