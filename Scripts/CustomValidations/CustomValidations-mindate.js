$.validator.addMethod('mindate',

    function (value, element, parameters) {

        var
            incomingDate = moment(value, 'DD-MM-YYYY hh:mm:ss').toDate(),
            todayDate = moment().toDate(),
            validationDate = moment(parameters['mindatevalue'], 'DD-MM-YYYY hh:mm:ss').toDate();
   
        if (parameters['untiltoday'].toLocaleLowerCase() === 'true') {
            if (incomingDate >= validationDate && incomingDate <= todayDate) {
                return true;
            }
        } else if (incomingDate > validationDate) {

            return true;
            
        }

        return false;

    });

/*$.validator.addMethod('mindate',
    function (value, element, parameters) {
        //Esta validación requiere MomentJs
        var d = value.split("/");
        //var incomingDate = new Date(value);
        //var todayDate = new Date((/chrom(e|ium)/.test(navigator.userAgent.toLowerCase())) ? d[1] + "/" + d[0] + "/" + d[2] : Date());
        var incomingDate = new Date((/chrom(e|ium)/.test(navigator.userAgent.toLowerCase())) ? d[1] + "/" + d[0] + "/" + d[2] : value);
        var todayDate = moment(Date());

        var validationDate = new Date(parameters['mindatevalue']);
        if (parameters['untiltoday'].toLocaleLowerCase() === 'true') {
            if (+incomingDate > +validationDate && +incomingDate < +todayDate) {
                return true;
            }
        } else {
            if (+incomingDate > +validationDate) {
                return true;
            }
        }
            
    });
*/

$.validator.unobtrusive.adapters.add(
    'mindate', ['mindatevalue', 'untiltoday'],
    function (options) {
        //var params = options.params.mindatevalue;
        options.rules['mindate'] = {
            untiltoday: options.params.untiltoday,
            mindatevalue: options.params.mindatevalue
        };
        options.messages['mindate'] = options.message;
    });