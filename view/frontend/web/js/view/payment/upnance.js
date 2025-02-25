define(
    [
        'uiComponent',
        'Magento_Checkout/js/model/payment/renderer-list'
    ],
    function (
        Component,
        rendererList
    ) {
        'use strict';
        rendererList.push(
            {
                type: 'upnance_gateway',
                component: 'Upnance_Gateway/js/view/payment/method-renderer/upnance'
            },
            {
                type: 'upnance_klarna',
                component: 'Upnance_Gateway/js/view/payment/method-renderer/klarna'
            },
            {
                type: 'upnance_mobilepay',
                component: 'Upnance_Gateway/js/view/payment/method-renderer/mobilepay'
            },
            {
                type: 'upnance_vipps',
                component: 'Upnance_Gateway/js/view/payment/method-renderer/vipps'
            },
            {
                type: 'upnance_paypal',
                component: 'Upnance_Gateway/js/view/payment/method-renderer/paypal'
            },
            {
                type: 'upnance_viabill',
                component: 'Upnance_Gateway/js/view/payment/method-renderer/viabill'
            },
            {
                type: 'upnance_swish',
                component: 'Upnance_Gateway/js/view/payment/method-renderer/swish'
            },
            {
                type: 'upnance_trustly',
                component: 'Upnance_Gateway/js/view/payment/method-renderer/trustly'
            },
            {
                type: 'upnance_anyday',
                component: 'Upnance_Gateway/js/view/payment/method-renderer/anyday'
            },
            {
                type: 'upnance_applepay',
                component: 'Upnance_Gateway/js/view/payment/method-renderer/applepay'
            },
            {
                type: 'upnance_googlepay',
                component: 'Upnance_Gateway/js/view/payment/method-renderer/googlepay'
            }
        );

        return Component.extend({});
    }
);
