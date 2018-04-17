//http://anthonyvscode.com/2011/07/14/mvc-3-requiredif-validator-for-multiple-values/
$.validator.addMethod('requiredif',
    function (value, element, parameters) {
        var dependentElement = $('#' + parameters['dependentproperty']);

        var type = $(dependentElement).attr("type"),
        actualVal = $(dependentElement).val();

        if (type === "radio" || type === "checkbox") {
            actualVal = $(dependentElement).prop('checked').toString();
        } else {
            if (typeof actualVal === "string") {
                actualVal = actualVal.replace(/\r/g, "");
            }
        }

        var targetvalue = parameters['targetvalue'];
        targetvalue = (targetvalue == null ? '' : targetvalue).toString();

        var targetvaluearray = targetvalue.split('|');

        for (var i = 0; i < targetvaluearray.length; i++) {
            
            if (targetvaluearray[i] === actualVal) {
                return $.validator.methods.required.call(this, value, element, parameters);
            }
        }

        return true;
    });

$.validator.unobtrusive.adapters.add(
    'requiredif',
    ['dependentproperty', 'targetvalue'],
    function (options) {
        options.rules['requiredif'] = {
            dependentproperty: options.params['dependentproperty'],
            targetvalue: options.params['targetvalue']
        };
        options.messages['requiredif'] = options.message;
    });