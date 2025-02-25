define(
    [
        'mage/url'
    ],
    function (url) {
        'use strict';

        return {
            redirectUrl: window.checkoutConfig.payment.upnance_gateway.redirectUrl,

            /**
             * Provide redirect to page
             */
            execute: function () {
                window.location.replace(url.build(this.redirectUrl));
            }
        };
    }
);
