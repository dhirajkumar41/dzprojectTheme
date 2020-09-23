define([
    'jquery'
], function ($) {
    'use strict';

    return {
        elements: {},

        /**
         * data-bind="afterRender: afterRender
         *
         * @param {String} id
         * @param {Element} element
         */
        register: function (id, element) {
            this.elements[id] = element;
        },

        /**
         * Show window
         *
         * @param {String} key
         */
        show: function (key) {
            var id = 'ajaxpro-' + key,
            element;

            if (this.elements[id]) {
                element = this.elements[id];

                this.hide();

                element.trigger('openModal');
            }
        },

        /**
         * eval native additional js
         *
         * @param {String} key
         */
        evalJs: function (key) {
            var id = 'ajaxpro-' + key,
            self = this,
            element;

            if (self.elements[id]) {
                element = self.elements[id];

                $(element).find('script').filter(function (i, script) {
                    return !script.type;
                }).each(function (i, script) {
                    script = $(script).html();

                    if (script.indexOf('document.write(') !== -1) {
                        return console.error(
                            'document.write writes to the document stream, ' +
                            'calling document.write on a closed (loaded) ' +
                            'document automatically calls document.open, ' +
                            'which will clear the document.'
                        );
                    }

                    try {
                        return $.globalEval(script);
                    } catch (err) {
                        console.log(script);
                        console.error(err);
                    }
                });
            }
        },

        /**
         * Hide modal window
         */
        hide: function () {
            $('.block-ajaxpro').each(function (i, el) {
                $(el).trigger('closeModal');
            });
        }
    };
});
