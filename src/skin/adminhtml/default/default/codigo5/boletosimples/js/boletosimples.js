(function() {
    'use strict';

    document.observe('dom:loaded', function() {
        var header = $('boletosimples-header');

        // Hacking System Config Interface :)
        if (header) {
            $('payment_boletosimples').insert({
                top: header.outerHTML
            });

            header.remove();
        }
    });
}());
