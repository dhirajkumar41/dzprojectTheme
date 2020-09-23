define([
    'jquery',
    'Magento_Ui/js/modal/modal' // 2.3.3: create 'jquery-ui-modules/widget' dependency
], function ($) {
    'use strict';

    var gallery = $('[data-gallery-role=gallery-placeholder]');

    $.widget('swissup.argentoPhotoInDescription', {
        options: {
            imageClass: 'argento-float-photo',
            addClasses: ''
        },

        /**
         * [_create description]
         */
        _create: function () {
            gallery.on('gallery:loaded', $.proxy(this._initialize, this));
            this.element.on('easytabs:contentLoaded', $.proxy(this._initialize, this));
        },

        /**
         * Initialize photo from product
         */
        _initialize: function () {
            var data,
                image,
                container;

            if (this.element.children().hasClass(this.options.imageClass)) {
                return;
            }

            data = this.getFotorama().data;
            if (!data) {
                return;
            }

            image = data.slice(-1).pop(); // last item
            container = this._prepareContainer();
            container.append('<img src="' + image.img + '">');
            this.element.prepend(container);

            if (image.type === 'video') {
                this._initializeVideo(container, data.length - 1);
            }
        },

        /**
         * Initialize product video
         */
        _initializeVideo: function (container, itemIndex) {
            var fotoramaVideo = this.getFotoramaVideo();

            if (!fotoramaVideo) {
                return;
            }

            fotoramaVideo._checkForVideoExist.apply(fotoramaVideo);
            fotoramaVideo._prepareForVideoContainer.apply(fotoramaVideo, [
                container,
                fotoramaVideo.options.videoData[itemIndex],
                this.getFotorama(),
                itemIndex
            ]);
        },

        /**
         * @return {jQuery}
         */
        _prepareContainer: function () {
            return $('<div/>', {
                    class: this.options.imageClass + ' ' + this.options.addClasses
                });
        },

        /**
         * @return {Object}
         */
        getFotorama: function () {
            return gallery.data('gallery') && gallery.data('gallery').fotorama ?
                gallery.data('gallery').fotorama :
                {};
        },

        /**
         * @return {Object}
         */
        getFotoramaVideo: function () {
            return gallery.data('mageAddFotoramaVideoEvents') ?
                gallery.data('mageAddFotoramaVideoEvents'):
                {};
        }
    });

    return $.swissup.argentoPhotoInDescription;
});
