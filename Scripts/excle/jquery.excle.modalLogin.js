(function (w, d, $) {

    if (!($ && $.fn.foundation)) return console.error('check if missing jQuery or jQuery.fn.foundation objects');

    $.excle = $.excle || {};
    $.excle.modalLogin = function (url) {
        var
            url = (url && typeof url == "string" && url) || "ajaxLogOn";
        $modal = $('#ModalLogin'),
        $content = $modal.find('#LoginContent'),
        $closeButton = $modal.find('.close-reveal-modal');

        $content.html('').load(url, function () {

            $closeButton.click(function (e) {
                $modal.foundation('reveal', 'close');
            });

            $modal.foundation('reveal', 'open')
                  .foundation('reveal', 'reflow');

        });
    }

}(window ? window : this, window.document, jQuery));