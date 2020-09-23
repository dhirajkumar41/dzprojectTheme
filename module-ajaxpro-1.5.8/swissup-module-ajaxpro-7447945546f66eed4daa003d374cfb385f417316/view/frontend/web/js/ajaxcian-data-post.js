define([
    'jquery',
    'Swissup_Ajaxpro/js/loader',
    'Magento_Ui/js/modal/modal' // 2.3.3: create 'jquery-ui-modules/widget' dependency
], function ($, loader) {
    'use strict';

    $.widget('swissup.ajaxcianDataPost', {

        options: {
            processStart: null,
            processStop: null,
            bind: true,
            attributeName: 'post-ajax',
            formKeyInputSelector: 'input[name="form_key"]'
        },

        /**
         * Constructor
         */
        _create: function () {
            if (this.options.bind) {
                this._bind();
                loader.setLoaderImage(this.options.loaderImage)
                    .setLoaderImageMaxWidth(this.options.loaderImageMaxWidth);
            }
        },

        /**
         * Bind new ajax function
         */
        _bind: function () {
            var self = this,
            dataPost = this.element.attr('data-post'), form;

            // disable tocart patching because it dublicate catalog-addto-cart widget
            // if (!dataPost &&
            //     isToCartPatchEnable === true &&
            //     this.element.hasClass('tocart') &&
            //     this.element.closest('form') &&
            //     typeof this.element.attr('data-' + this.options.attributeName) === 'undefined'
            // ) {
            //     form = this.element.closest('form');
            //     if (form.attr('data-role') !== 'tocart-form' ||
            //         typeof form.attr('action') === 'undefined'
            //     ) {
            //         return;
            //     }
            //     dataPost = {
            //         action: form.attr('action'),
            //     };
            //     dataPost.data = {};
            //     form.select(':input').serializeArray().forEach(function (param) {
            //         dataPost.data[param.name] = param.value;
            //     });
            //     dataPost = JSON.stringify(dataPost);
            // }

            if (!dataPost) {
                return;
            }

            if (dataPost) {
                // $(document).undelegate('a[data-post]', 'click.dataPost0');
                this.element
                    .attr('data-' + this.options.attributeName, dataPost)
                    .removeAttr('data-post');
            }

            setTimeout(function () {
                self.element.on('click', function (e) {
                    e.preventDefault();
                    $.proxy(self._ajax, self, $(this))();
                });
            }, 500);
        },

        /**
         * Send ajax request
         * @param  {Element} element
         */
        _ajax: function (element) {
            var dataPost = element.data(this.options.attributeName),
            parameters = dataPost.data || {},
            formKey = $(this.options.formKeyInputSelector).val(),
            url = dataPost.action;

            if (formKey) {
                parameters['form_key'] = formKey;
            }

            $.ajax({
                method: 'POST',
                url: url,
                dataType: 'json',
                data: parameters,

                /**
                 *
                 */
                beforeSend: function () {
                    element.css({
                        'color': 'transparent'
                    });
                    loader.startLoader(element);
                },

                /**
                 * @param  {Object} response
                 * @return {void}
                 */
                success: function (response) {
                    element.css({
                        'color': ''
                    });
                    loader.stopLoader(element);

                    if (response && response.backUrl) {
                        if (response.backUrl.indexOf('customer/section/load/?sections=') !== -1) {
                            response.backUrl = url;
                        }
                        window.location = response.backUrl;

                        return;
                    }
                }
            })
            .fail(function (jqXHR) {
                throw new Error(jqXHR);
            })
            .done(function () {
                // console.log(arguments);
            });
        }
    });

    return $.swissup.ajaxcianDataPost;
});
