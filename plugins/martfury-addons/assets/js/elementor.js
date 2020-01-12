(function ($) {
    'use strict';

    /**
     * Product Deals Carousel
     */
    var productDealsCarouselHandler = function ($scope, $) {
        $scope.find('.mf-elementor-product-deals-carousel').each(function () {
            var $selector = $(this),
                elementSettings = $selector.data('settings'),
                slidesToShow = parseInt(elementSettings.slidesToShow),
                slidesToScroll = parseInt(elementSettings.slidesToScroll);

            $selector.find('ul.products').not('.slick-initialized').slick({
                rtl: $('body').hasClass('rtl'),
                slidesToShow: slidesToShow,
                slidesToScroll: slidesToScroll,
                arrows: true,
                dots: true,
                infinite: 'yes' === elementSettings.infinite,
                prevArrow: '<span class="icon-chevron-left slick-prev-arrow"></span>',
                nextArrow: '<span class="icon-chevron-right slick-next-arrow"></span>',
                autoplay: 'yes' === elementSettings.autoplay,
                autoplaySpeed: parseInt(elementSettings.autoplay_speed),
                responsive: [
                    {
                        breakpoint: 1366,
                        settings: {
                            slidesToShow: slidesToShow > 5 ? 5 : slidesToShow,
                            slidesToScroll: slidesToScroll > 5 ? 5 : slidesToScroll
                        }
                    },
                    {
                        breakpoint: 1200,
                        settings: {
                            slidesToShow: slidesToShow > 4 ? 4 : slidesToShow,
                            slidesToScroll: slidesToScroll > 4 ? 4 : slidesToScroll
                        },
                    },
                    {
                        breakpoint: 992,
                        settings: {
                            slidesToShow: 3,
                            slidesToScroll: slidesToScroll > 3 ? 3 : slidesToScroll
                        }
                    },
                    {
                        breakpoint: 767,
                        settings: {
                            slidesToShow: 2,
                            slidesToScroll: slidesToScroll > 2 ? 2 : slidesToScroll
                        },
                    }
                ]
            });

            $selector.on('afterChange', function () {
                lazyLoadHandler($selector);
            });

        });
    };

    /**
     * Product Deals Carousel 2
     */
    var productDealsCarousel2Handler = function ($scope, $) {
        $scope.find('.mf-product-deals-carousel-2').each(function () {
            var $selector = $(this),
                elementSettings = $selector.data('settings'),
                $gallery = $selector.find('.woocommerce-product-gallery');

            $selector.find('ul.products').not('.slick-initialized').slick({
                rtl: $('body').hasClass('rtl'),
                slidesToShow: 1,
                slidesToScroll: 1,
                arrows: true,
                dots: false,
                infinite: 'yes' === elementSettings.infinite,
                prevArrow: $selector.find('.slick-prev-arrow'),
                nextArrow: $selector.find('.slick-next-arrow'),
                autoplay: 'yes' === elementSettings.autoplay,
                autoplaySpeed: parseInt(elementSettings.autoplay_speed)
            });

            var options = {
                selector: '.woocommerce-product-gallery__wrapper > .woocommerce-product-gallery__image',
                allowOneSlide: false,
                animation: "slide",
                animationLoop: false,
                animationSpeed: 500,
                controlNav: "thumbnails",
                directionNav: false,
                rtl: false,
                slideshow: false,
                smoothHeight: true,
                start: function () {
                    $gallery.css('opacity', 1);
                },
            };

            $gallery.flexslider(options);

            $selector.find('.deal-expire-countdown').each(function () {
                $(document).trigger('deal_expire_countdown', $(this));
            });

            $gallery.each(function () {
                var $el = $(this);
                $el.imagesLoaded(function () {

                    var $thumbnail = $el.find('.flex-control-thumbs');

                    setTimeout(function () {
                        if ($thumbnail.length < 1) {
                            return;
                        }
                        var columns = $el.data('columns');
                        var count = $thumbnail.find('li').length;
                        if (count > columns) {
                            $thumbnail.not('.slick-initialized').slick({
                                slidesToShow: columns,
                                slidesToScroll: 1,
                                focusOnSelect: true,
                                vertical: true,
                                infinite: false,
                                prevArrow: '<span class="icon-chevron-up slick-prev-arrow"></span>',
                                nextArrow: '<span class="icon-chevron-down slick-next-arrow"></span>',
                                responsive: [
                                    {
                                        breakpoint: 768,
                                        settings: {
                                            slidesToShow: 4
                                        }
                                    },
                                    {
                                        breakpoint: 480,
                                        settings: {
                                            slidesToShow: 3
                                        }
                                    }
                                ]
                            });
                        } else {
                            $thumbnail.addClass('no-slick');
                        }
                    }, 100);

                });
            });

            $selector.find('.woocommerce-product-gallery__image').on('click', function () {
                return false;
            });

            $selector.on('afterChange', function () {
                lazyLoadHandler($selector);
            });

        });

    };

    /**
     * LazyLoad
     */
    var lazyLoadHandler = function ($els) {
        if ($els.length === 0) {
            $els = $('body');
        }
        $els.find('img.lazy').lazyload({
            load: function () {
                $(this).removeClass('lazy');
            }
        });
    };

    var getProductsCarouselHandler = function ($els) {
        var $selector = $els,
            elementSettings = $selector.data('settings'),
            slidesToShow = parseInt(elementSettings.slidesToShow),
            slidesToScroll = parseInt(elementSettings.slidesToScroll);

        $selector.find('ul.products').not('.slick-initialized').slick({
            rtl: $('body').hasClass('rtl'),
            slidesToShow: slidesToShow,
            slidesToScroll: slidesToScroll,
            arrows: true,
            dots: true,
            infinite: 'yes' === elementSettings.infinite,
            prevArrow: '<span class="icon-chevron-left slick-prev-arrow"></span>',
            nextArrow: '<span class="icon-chevron-right slick-next-arrow"></span>',
            autoplay: 'yes' === elementSettings.autoplay,
            autoplaySpeed: parseInt(elementSettings.autoplay_speed),
            speed: parseInt(elementSettings.speed),
            responsive: [
                {
                    breakpoint: 1600,
                    settings: {
                        slidesToShow: slidesToShow > 6 ? 6 : slidesToShow,
                        slidesToScroll: slidesToScroll > 6 ? 6 : slidesToScroll
                    }
                },
                {
                    breakpoint: 1366,
                    settings: {
                        slidesToShow: slidesToShow > 5 ? 5 : slidesToShow,
                        slidesToScroll: slidesToScroll > 5 ? 5 : slidesToScroll
                    }
                },
                {
                    breakpoint: 1200,
                    settings: {
                        slidesToShow: slidesToShow > 4 ? 4 : slidesToShow,
                        slidesToScroll: slidesToScroll > 4 ? 4 : slidesToScroll
                    }
                },
                {
                    breakpoint: 992,
                    settings: {
                        slidesToShow: slidesToShow > 3 ? 3 : slidesToShow,
                        slidesToScroll: slidesToScroll > 3 ? 3 : slidesToScroll
                    }
                },
                {
                    breakpoint: 767,
                    settings: {
                        slidesToShow: slidesToShow > 2 ? 2 : slidesToShow,
                        slidesToScroll: slidesToScroll > 2 ? 2 : slidesToScroll
                    }
                }
            ]
        });

        $selector.on('afterChange', function () {
            lazyLoadHandler($selector);
        });
    };

    /**
     * CountDown
     */
    var countDownHandler = function ($scope, $) {
        $scope.find('.martfury-countdown').mf_countdown();
    };

    /**
     * Catagory Banners Carousel
     */
    var catBannersCarouselHandler = function ($scope, $) {
        $scope.find('.mf-products-of-category').each(function () {
            var $selector = $(this),
                elementSettings = $selector.data('settings');

            $selector.find('.images-list').not('.slick-initialized').slick({
                rtl: $('body').hasClass('rtl'),
                slidesToShow: 1,
                arrows: 'yes' === elementSettings.arrows,
                prevArrow: '<span class="icon-chevron-left slick-prev-arrow"></span>',
                nextArrow: '<span class="icon-chevron-right slick-next-arrow"></span>',
                infinite: 'yes' === elementSettings.infinite,
                autoplay: 'yes' === elementSettings.autoplay,
                autoplaySpeed: parseInt(elementSettings.autoplay_speed),
                dots: 'yes' === elementSettings.dots,
                speed: parseInt(elementSettings.speed),
            });

            $selector.on('afterChange', function () {
                lazyLoadHandler($selector);
            });
        });
    };


    /**
     * Catagory Tabs Carousel
     */
    var categoryTabsCarouselHandler = function ($scope, $) {
        $scope.find('.mf-category-tabs').each(function () {
            var $selector = $(this);

            $selector.find('ul.tabs-nav').not('.slick-initialized').slick({
                rtl: $('body').hasClass('rtl'),
                slidesToShow: 8,
                prevArrow: '<span class="icon-chevron-left slick-prev-arrow"></span>',
                nextArrow: '<span class="icon-chevron-right slick-next-arrow"></span>',
                infinite: false,
                responsive: [
                    {
                        breakpoint: 1200,
                        settings: {
                            slidesToShow: 6,
                            slidesToScroll: 6
                        }
                    },
                    {
                        breakpoint: 992,
                        settings: {
                            slidesToShow: 4,
                            slidesToScroll: 4
                        }
                    },
                    {
                        breakpoint: 480,
                        settings: {
                            slidesToShow: 2,
                            slidesToScroll: 2
                        }
                    }
                ]
            });

            $selector.on('afterChange', function () {
                lazyLoadHandler($selector);
            });
        });
    };

    /**
     * category Tabs
     */
    var categoryTabsHandler = function ($scope, $) {
        $scope.find('.martfury-tabs').mrtabs();
    };

    /**
     * Product Carousel
     */
    var productsCarouselHandler = function ($scope, $) {
        $scope.find('.mf-products-carousel').each(function () {
            getProductsCarouselHandler($(this));
        });
    };

    /**
     * Product Tabs Carousel
     */
    var productTabsCarouselHandler = function ($scope, $) {
        $scope.find('.mf-products-tabs-carousel').each(function () {
            var $this = $(this);
            getProductsCarouselHandler($this);

            $this.find('.tabs-nav').on('click', 'a', function (e) {
                getProductsAJAXHandler($(this), $this);
            });
        });
    };


    /**
     * Product Tabs Grid
     */
    var productTabsGridHandler = function ($scope, $) {
        $scope.find('.mf-products-tabs-grid').each(function () {
            var $this = $(this);
            $this.find('.tabs-nav').on('click', 'a', function (e) {
                getProductsAJAXHandler($(this), $this, false);
            });
        });
    };


    /**
     * Get Product AJAX
     */
    var getProductsAJAXHandler = function ($el, $tabs, carousel = true) {

        if (typeof wc_add_to_cart_params === 'undefined') {
            return;
        }

        var tab = $el.data('href'),
            $content = $tabs.find('.tabs-' + tab);

        if ($content.hasClass('tab-loaded')) {
            return;
        }

        var data = {},
            elementSettings = $content.data('settings'),
            ajax_url = wc_add_to_cart_params.wc_ajax_url.toString().replace('%%endpoint%%', 'mf_elementor_load_products');


        $.each(elementSettings, function (key, value) {
            data[key] = value;
        });

        $.post(
            ajax_url,
            data,
            function (response) {
                if (!response) {
                    return;
                }
                $content.html(response.data);
                if (carousel === true) {
                    getProductsCarouselHandler($content.closest('.mf-products-tabs'));
                }
                lazyLoadHandler($content);
                $content.addClass('tab-loaded');
            }
        );
    };

    /**
     * Testimonials Sliders
     */
    var testimonialSlidesHandler = function ($scope, $) {
        $scope.find('.mf-elementor-testimonial-slides').each(function () {
            var $selector = $(this),
                $arrow_wrapper = $(this).find('.arrow-wrapper'),
                elementSettings = $selector.data('settings');

            var options = {
                rtl: $('body').hasClass('rtl'),
                slidesToShow: 2,
                arrows: true,
                dots: true,
                infinite: 'yes' === elementSettings.infinite,
                prevArrow: '<div class="mf-left-arrow"><i class="icon-chevron-left"></i></div>',
                nextArrow: '<div class="mf-right-arrow"><i class="icon-chevron-right"></i></div>',
                appendArrows: $arrow_wrapper,
                autoplay: 'yes' === elementSettings.autoplay,
                autoplaySpeed: parseInt(elementSettings.autoplay_speed),
                speed: parseInt(elementSettings.speed),
                responsive: [
                    {
                        breakpoint: 480,
                        settings: {
                            slidesToShow: 1,
                        }
                    }
                ]
            };

            $selector.find('.testimonial-list').not('.slick-initialized').slick(options);

            $selector.on('afterChange', function () {
                lazyLoadHandler($selector);
            });

        });
    };

    /**
     * Partner Sliders
     */
    var partnerSlidesHandler = function ($scope, $) {
        $scope.find('.martfury-partner-carousel').each(function () {
            var $selector = $(this),
                elementSettings = $selector.data('settings');

            $selector.find('.list-item').not('.slick-initialized').slick({
                rtl: $('body').hasClass('rtl'),
                slidesToShow: elementSettings.columns,
                infinite: false,
                arrows: false,
                dots: false,
                autoplay: 'yes' === elementSettings.autoplay,
                autoplaySpeed: parseInt(elementSettings.autoplay_speed),
                responsive: [
                    {
                        breakpoint: 1200,
                        settings: {
                            slidesToShow: parseInt(elementSettings.autoplay_speed) > 4 ? 4 : elementSettings.autoplay_speed
                        }
                    },
                    {
                        breakpoint: 768,
                        settings: {
                            slidesToShow: 3
                        }
                    },
                    {
                        breakpoint: 480,
                        settings: {
                            slidesToShow: 2
                        }
                    }
                ]
            });

            $selector.on('afterChange', function () {
                lazyLoadHandler($selector);
            });

        });
    };

    var imagesCarouselHandler = function ($scope, $) {
        $scope.find('.mf-images-gallery--slide').each(function () {
            var $selector = $(this),
                elementSettings = $selector.data('settings'),
                slidesToShow = parseInt(elementSettings.slidesToShow),
                slidesToScroll = parseInt(elementSettings.slidesToScroll);

            $selector.find('.images-list').not('.slick-initialized').slick({
                rtl: $('body').hasClass('rtl'),
                slidesToShow: slidesToShow,
                slidesToScroll: slidesToScroll,
                arrows: false,
                dots: 'yes' === elementSettings.dots,
                infinite: 'yes' === elementSettings.infinite,
                prevArrow: '<span class="icon-chevron-left slick-prev-arrow"></span>',
                nextArrow: '<span class="icon-chevron-right slick-next-arrow"></span>',
                autoplay: 'yes' === elementSettings.autoplay,
                autoplaySpeed: parseInt(elementSettings.autoplay_speed),
                speed: parseInt(elementSettings.speed),
                responsive: [
                    {
                        breakpoint: 1200,
                        settings: {
                            slidesToShow: slidesToShow > 4 ? 4 : slidesToShow,
                            slidesToScroll: slidesToScroll > 4 ? 4 : slidesToScroll
                        }
                    },
                    {
                        breakpoint: 992,
                        settings: {
                            slidesToShow: slidesToShow > 3 ? 3 : slidesToShow,
                            slidesToScroll: slidesToScroll > 3 ? 3 : slidesToScroll
                        }
                    },
                    {
                        breakpoint: 767,
                        settings: {
                            slidesToShow: slidesToShow > 2 ? 2 : slidesToShow,
                            slidesToScroll: slidesToScroll > 2 ? 2 : slidesToScroll
                        }
                    }
                ]
            });

            $selector.on('afterChange', function () {
                lazyLoadHandler($selector);
            });

        });
    };

    /**
     * Testimonials Sliders
     */
    var journeyHandler = function ($scope, $) {
        $scope.find('.martfury-journey-els').each(function () {
            var $el = $(this),
                $tabs = $el.find('ul li a'),
                $first = $tabs.filter(':first'),
                $content = $el.find('.journey-wrapper'),
                width = $el.find('ul').width(),
                elementSettings = $el.data('settings'),
                num = parseInt(elementSettings.number),
                space, pos, val;

            if (num == 1) {
                space = 0;
            } else {
                space = (width - 40) / (num - 1);
            }

            for (var i = 1; i <= num; i++) {
                var $this = $('.journey-tab-' + i);
                if ($('body').hasClass('rtl')) {
                    if ($this.hasClass('reverse')) {
                        pos = 'left';
                        val = (i - num) * space * -1;
                    } else {
                        pos = 'right';
                        val = (i - 1) * space;
                    }
                } else {
                    if ($this.hasClass('reverse')) {
                        pos = 'right';
                        val = (i - num) * space * -1;
                    } else {
                        pos = 'left';
                        val = (i - 1) * space;
                    }
                }

                $this.css(pos, val);
            }

            $first.addClass('active');
            $content.filter(':first').addClass('active');

            $tabs.on('click', function (e) {
                e.preventDefault();

                var $this = $(this),
                    tab_id = $this.attr('data-tab');

                if ($this.hasClass('active')) {
                    return;
                }

                $tabs.removeClass('active');
                $content.removeClass('active');

                $this.addClass('active');
                $('#' + tab_id).addClass('active');
            });
        });
    };

    var counterHandler = function ($scope, $) {
        $scope.find('.martfury-counter-els').each(function () {
            var $selector = $(this);
            $selector.find('.counter-value').counterUp();

        });
    };

    /**
     * Product Of Category 2
     */
    var productOfCategory2Handler = function ($scope, $) {
        $scope.find('.mf-products-of-category-2').each(function () {

            var $selector = $(this).find('.images-slider'),
                elementSettings = $selector.data('settings');

            $selector.find('.images-list').not('.slick-initialized').slick({
                rtl: $('body').hasClass('rtl'),
                slidesToShow: 1,
                arrows: 'yes' === elementSettings.arrows,
                dots: false,
                infinite: 'yes' === elementSettings.infinite,
                prevArrow: '<span class="icon-chevron-left slick-prev-arrow"></span>',
                nextArrow: '<span class="icon-chevron-right slick-next-arrow"></span>',
                autoplay: 'yes' === elementSettings.autoplay,
                autoplaySpeed: parseInt(elementSettings.autoplay_speed),
                speed: parseInt(elementSettings.speed),
            });

            $selector.on('afterChange', function () {
                lazyLoadHandler($selector);
            });

            var $carousel = $(this).find('.mf-products-tabs'),
                carouselSettings = $carousel.data('settings'),
                slidesToShow = parseInt(carouselSettings.slidesToShow),
                slidesToScroll = parseInt(carouselSettings.slidesToScroll);

            $carousel.find('ul.products').not('.slick-initialized').slick({
                rtl: $('body').hasClass('rtl'),
                slidesToShow: slidesToShow,
                slidesToScroll: slidesToScroll,
                arrows: true,
                dots: true,
                infinite: 'yes' === carouselSettings.infinite,
                prevArrow: '<span class="icon-chevron-left slick-prev-arrow"></span>',
                nextArrow: '<span class="icon-chevron-right slick-next-arrow"></span>',
                autoplay: 'yes' === carouselSettings.autoplay,
                autoplaySpeed: parseInt(carouselSettings.autoplay_speed),
                speed: parseInt(carouselSettings.speed),
                responsive: [
                    {
                        breakpoint: 1200,
                        settings: {
                            slidesToShow: slidesToShow > 4 ? 4 : slidesToShow,
                            slidesToScroll: slidesToScroll > 4 ? 4 : slidesToScroll
                        }
                    },
                    {
                        breakpoint: 992,
                        settings: {
                            slidesToShow: slidesToShow > 3 ? 3 : slidesToShow,
                            slidesToScroll: slidesToScroll > 3 ? 3 : slidesToScroll
                        }
                    },
                    {
                        breakpoint: 767,
                        settings: {
                            slidesToShow: 2,
                            slidesToScroll: 2
                        }
                    }
                ]
            });

            $carousel.on('afterChange', function () {
                lazyLoadHandler($selector);
            });

            $carousel.find('.tabs-nav').on('click', 'a', function (e) {
                getProductsAJAXHandler($(this), $carousel);
            });
        });
    };

    /**
     * Slides Widget
     * @param $scope
     * @param $
     */
    var slideCarousel = function ($scope, $) {
        $scope.find('.mf-slides').each(function () {
            var $selector = $(this),
                elementSettings = $selector.data('slider_options'),
                slidesToShow = parseInt(elementSettings.slidesToShow);

            $selector.not('.slick-initialized').slick({
                rtl: $('body').hasClass('rtl'),
                slidesToShow: slidesToShow,
                arrows: elementSettings.arrows,
                dots: elementSettings.dots,
                infinite: elementSettings.infinite,
                prevArrow: '<span class="slick-prev-arrow"><i class="icon-chevron-left"></i></span>',
                nextArrow: '<span class="slick-next-arrow"><i class="icon-chevron-right"></i></span>',
                autoplay: elementSettings.autoplay,
                autoplaySpeed: parseInt(elementSettings.autoplaySpeed),
                speed: parseInt(elementSettings.speed),
                pauseOnHover: elementSettings.pauseOnHover,
                responsive: []
            });

            var animation = $selector.data('animation');

            if (animation) {
                $selector
                    .on('beforeChange', function () {
                        var $sliderContentDiv = $selector.find('.mf-slide-content'),
                            $sliderPriceBox = $selector.find('.mf-slide-price-box');

                        $sliderContentDiv.removeClass('animated' + ' ' + animation).hide();

                        $sliderPriceBox.removeClass('animated zoomIn').hide();
                    })
                    .on('afterChange', function (event, slick, currentSlide) {
                        var $currentSlideDiv = $(slick.$slides.get(currentSlide)).find('.mf-slide-content'),
                            $currentPriceBox = $(slick.$slides.get(currentSlide)).find('.mf-slide-price-box');

                        $currentSlideDiv.show().addClass('animated' + ' ' + animation);

                        $currentPriceBox.show().addClass('animated zoomIn');
                    });
            }

            $selector.on('afterChange', function () {
                lazyLoadHandler($selector);
            });
        });
    };

    /**
     * Slides Widget
     * @param $scope
     * @param $
     */
    var slideCarouselHandler = function ($scope, $) {
        $scope.find('.mf-slides-wrapper').each(function () {
            var $selector = $(this).find('.mf-slides'),
                elementSettings = $selector.data('slider_options'),
                $arrow_wrapper = $(this).find('.arrows-inner'),
                slidesToShow = parseInt(elementSettings.slidesToShow);

            $selector.not('.slick-initialized').slick({
                rtl: $('body').hasClass('rtl'),
                slidesToShow: slidesToShow,
                arrows: elementSettings.arrows,
                appendArrows: $arrow_wrapper,
                dots: elementSettings.dots,
                infinite: elementSettings.infinite,
                prevArrow: '<span class="slick-prev-arrow"><i class="icon-chevron-left"></i></span>',
                nextArrow: '<span class="slick-next-arrow"><i class="icon-chevron-right"></i></span>',
                autoplay: elementSettings.autoplay,
                autoplaySpeed: parseInt(elementSettings.autoplaySpeed),
                speed: parseInt(elementSettings.speed),
                pauseOnHover: elementSettings.pauseOnHover,
                fade: elementSettings.fade,
                cssEase: elementSettings.fade ? 'linear' : '',
                responsive: []
            });

            var animation = $selector.data('animation');

            if (animation) {
                $selector
                    .on('beforeChange', function () {
                        var $sliderContent = $selector.find('.mf-slide-content'),
                            $sliderPriceBox = $selector.find('.mf-slide-price-box');

                        $sliderContent.removeClass('animated' + ' ' + animation).hide();

                        $sliderPriceBox.removeClass('animated zoomIn').hide();
                    })
                    .on('afterChange', function (event, slick, currentSlide) {
                        var $currentSlide = $(slick.$slides.get(currentSlide)).find('.mf-slide-content'),
                            $currentPriceBox = $(slick.$slides.get(currentSlide)).find('.mf-slide-price-box');

                        $currentSlide.show().addClass('animated' + ' ' + animation);

                        $currentPriceBox.show().addClass('animated zoomIn');
                    });
            }

            $selector.on('afterChange', function () {
                lazyLoadHandler($selector);
            });
        });
    };

    /**
     * Elementor JS Hooks
     */
    $(window).on("elementor/frontend/init", function () {

        // Product Deals Carousel
        elementorFrontend.hooks.addAction(
            "frontend/element_ready/martfury-product-deals-carousel.default",
            productDealsCarouselHandler
        );

        elementorFrontend.hooks.addAction(
            "frontend/element_ready/martfury-product-deals-carousel.default",
            countDownHandler
        );

        //Product Deals Grid

        elementorFrontend.hooks.addAction(
            "frontend/element_ready/martfury-product-deals-grid.default",
            countDownHandler
        );

        // Product Deals Carousel 2
        elementorFrontend.hooks.addAction(
            "frontend/element_ready/martfury-product-deals-carousel-2.default",
            productDealsCarousel2Handler
        );

        // Products of Category
        elementorFrontend.hooks.addAction(
            "frontend/element_ready/martfury-products-of-category.default",
            catBannersCarouselHandler
        );


        // Category Tabs
        elementorFrontend.hooks.addAction(
            "frontend/element_ready/martfury-category-tabs.default",
            categoryTabsCarouselHandler
        );

        elementorFrontend.hooks.addAction(
            "frontend/element_ready/martfury-category-tabs.default",
            categoryTabsHandler
        );

        // Products Tabs Carousel

        elementorFrontend.hooks.addAction(
            "frontend/element_ready/martfury-product-tabs-carousel.default",
            productTabsCarouselHandler
        );


        elementorFrontend.hooks.addAction(
            "frontend/element_ready/martfury-product-tabs-carousel.default",
            categoryTabsHandler
        );

        // Products Tabs Grid
        elementorFrontend.hooks.addAction(
            "frontend/element_ready/martfury-product-tabs-grid.default",
            productTabsGridHandler
        );

        elementorFrontend.hooks.addAction(
            "frontend/element_ready/martfury-product-tabs-grid.default",
            categoryTabsHandler
        );

        // Products List Carousel
        elementorFrontend.hooks.addAction(
            "frontend/element_ready/martfury-products-list-carousel.default",
            productsCarouselHandler
        );

        // Products Carousel
        elementorFrontend.hooks.addAction(
            "frontend/element_ready/martfury-products-carousel.default",
            productsCarouselHandler
        );

        // Testimonial
        elementorFrontend.hooks.addAction(
            "frontend/element_ready/martfury-testimonial-slides.default",
            testimonialSlidesHandler
        );

        // Counter
        elementorFrontend.hooks.addAction(
            "frontend/element_ready/martfury-counter.default",
            counterHandler
        );

        // Journey
        elementorFrontend.hooks.addAction(
            "frontend/element_ready/martfury-journey.default",
            journeyHandler
        );

        // Product Grid
        elementorFrontend.hooks.addAction(
            "frontend/element_ready/martfury-products-grid.default",
            categoryTabsHandler
        );

        // All widgets ready
        elementorFrontend.hooks.addAction(
            "frontend/element_ready/widget",
            lazyLoadHandler
        );

        elementorFrontend.hooks.addAction(
            "frontend/element_ready/martfury-countdown.default",
            countDownHandler
        );

        // Product Of Category 2
        elementorFrontend.hooks.addAction(
            "frontend/element_ready/martfury-products-of-category-2.default",
            productOfCategory2Handler
        );

        // Product Of Category 2
        elementorFrontend.hooks.addAction(
            "frontend/element_ready/martfury-products-of-category-2.default",
            categoryTabsHandler
        );

        // Partner
        elementorFrontend.hooks.addAction(
            "frontend/element_ready/martfury-partner.default",
            partnerSlidesHandler
        );

        // Image Gallery - Slide
        elementorFrontend.hooks.addAction(
            "frontend/element_ready/martfury-images-carousel.default",
            imagesCarouselHandler
        );

        // Slides
        elementorFrontend.hooks.addAction(
            "frontend/element_ready/martfury-slides.default",
            slideCarouselHandler
        );
    });
})
(jQuery);