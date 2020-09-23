define([
    'Magento_Ui/js/lib/view/utils/async'
], function ($) {
    'use strict';

    // initialize slick carousel for '.product-items' when it is wrapped in '.argento-init-slick'
    $.async(
        {
            selector: '.argento-init-slick .product-items'
        },
        function (productList) {
            // Load 'slick' only when it really needed. When there is a match.
            require([
                'slick'
            ], function () {
                $(productList).slick({
                    'rtl': $(document.body).hasClass('rtl'),
                    'slidesToShow': 5,
                    'slidesToScroll': 5,
                    'dots': false,
                    'responsive': [
                        {
                            'breakpoint': 1024,
                            'settings': {
                                'slidesToShow': 4,
                                'slidesToScroll': 4
                            }
                        },
                        {
                            'breakpoint': 600,
                            'settings': {
                                'slidesToShow': 3,
                                'slidesToScroll': 3
                            }
                        },
                        {
                            'breakpoint': 480,
                            'settings': {
                                'slidesToShow': 2,
                                'slidesToScroll': 2
                            }
                        },
                        {
                            'breakpoint': 376,
                            'settings': {
                                'slidesToShow': 1,
                                'slidesToScroll': 1
                            }
                        }
                    ],
                    'autoplay': false
                });
            });
        }
    );

    // Show login popup when visitor clicks on `Sing in` link.
    $.async({
        selector: '.authorization-link a:not([data-post])'
    }, function (authorizationLink) {
        require([
            'Magento_Customer/js/model/authentication-popup',
            'mage/translate'
        ], function (authenticationPopup) {
            var updateHeaders = true;

            /**
             * Change login popup headers. Because it says 'Checkout...'.
             * And we show it at any page.
             */
            function changePopupHeaders() {
                $('#block-new-customer-heading').text(
                    $.mage.__('Proceed as a new customer')
                );
                $('#block-customer-login-heading').text(
                    $.mage.__('Proceed using your account')
                );
            }

            /**
             * @param  {jQuery.Event} event
             */
            function showPopup(event) {
                if ($(authorizationLink).data('no-popup') ||
                    !authenticationPopup.modalWindow
                ) {
                    return;
                }

                if (updateHeaders) {
                    changePopupHeaders();
                    updateHeaders = false;
                }

                authenticationPopup.showModal();
                event.preventDefault();
            }

            $(authorizationLink).click(showPopup);
        });
    });

    // Fix empty Account menu on mobile when using configurable header
    // @see magento/theme-frontend-blank/Magento_Theme/web/js/theme.js#29
    $(function() {
        $('.page-header.configurable .header.links').clone().appendTo('#store\\.links');
        // links in customer dropdown
        if ($('.page-header.configurable .switcher-customer').length) {
            $('.page-header.configurable .switcher-customer .switcher-dropdown').clone()
            .removeClass()
            .addClass('header links')
            .appendTo('#store\\.links');
        }
    });
});
