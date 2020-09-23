define([
    // 'jquery',
    'Magento_Ui/js/lib/view/utils/async',
    'Magento_Customer/js/customer-data',
    'Magento_Customer/js/section-config',
    'underscore',
    'mage/translate'
], function ($, customerData, sectionConfig, _, $t) {
    'use strict';

    var options = false,
        AjaxproQuickView = {

        /**
         * Constructor
         * @param {Object} settings
         */
        'Swissup_Ajaxpro/js/quick-view': function (settings) {
            if (options === false) {
                options = settings;
                AjaxproQuickView.init();
            }
        },

        /**
         * Initialize
         */
        init: function () {
            $.async({selector: '.products.list a.product.photo'},
                // _.once(
                    this.renderLabel.bind(this)
                // )
            );
            // customerData.invalidate(['ajaxpro-product']);
        },

        /**
         *
         * @param  {Object} element
         */
        renderLabel: function (element) {
            var targetElement = $(element),
                targetContainer = $(element).closest('.product-item-info'),
                self = this,
                productIdElement, productId,
                quickViewElement;

            if (targetElement.length !== 1) {
                return;
            }

            productIdElement = targetContainer
                .find('form [name="product"]');

            if (productIdElement.length !== 1) {
                return;
            }
            productId = productIdElement.val();

            if (!productId) {
                return;
            }


            targetElement.after(`<div class="swissup-ajaxpro-quick-view-wrapper">
                <a class="quick-view" href="#">
                    <span>${$t('Quick View')}</span>
                </a>
            </div>`);

            quickViewElement = targetContainer.find('a.quick-view');

            quickViewElement.on('click', function (e) {
                e.preventDefault();
                self.request(productId);
                // $.proxy(self._ajax, self, $(this))();
            });
        },

        /**
         * Send ajax request
         * @param {Integer} productId
         */
        request: function (productId) {
            var sectionNames = ['ajaxpro-product'],
            parameters = {
                sections: sectionNames.join(','),
                'update_section_id': false,
                ajaxpro: {
                    'product_id': productId,
                    blocks: ['product.view']
                }
            },
            url = options.sectionLoadUrl;

            parameters[options.refererParam] = options.refererValue;

            $.ajax({
                // method: 'POST',
                url: url,
                dataType: 'json',
                data: parameters
            })
            .fail(function (jqXHR) {
                throw new Error(jqXHR);
            })
            .done(function (sections) {
                _.each(sections, function (sectionData, sectionName) {
                    if (sectionName === 'ajaxpro-product') {
                        customerData.set(sectionName, sectionData);
                        customerData.reload(['cart', 'messages']);
                    }
                });
            });
        }
    };

    return AjaxproQuickView;
});
