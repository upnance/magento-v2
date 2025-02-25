define(
    [
        'Magento_Checkout/js/view/payment/default',
        'Upnance_Gateway/js/action/redirect-on-success'
    ],
    function (Component, upnanceRedirect) {
        'use strict';

        return Component.extend({
            defaults: {
                template: 'Upnance_Gateway/payment/form',
                paymentReady: false
            },
            redirectAfterPlaceOrder: false,

            /**
             * @return {exports}
             */
            initObservable: function () {
                this._super()
                    .observe('paymentReady');

                return this;
            },

            /**
             * @return {*}
             */
            isPaymentReady: function () {
                return this.paymentReady();
            },

            getCode: function() {
                return 'upnance_viabill';
            },
            getData: function() {
                return {
                    'method': this.item.method,
                };
            },
            afterPlaceOrder: function() {
                upnanceRedirect.execute();
            },
            getPaymentLogo: function () {
                return window.checkoutConfig.payment.upnance_viabill.paymentLogo;
            },
            getDescription: function () {
                return window.checkoutConfig.payment.upnance_viabill.description;
            }
        });
    }
);