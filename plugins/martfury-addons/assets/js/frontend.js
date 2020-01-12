(function ($) {
    'use strict';

    var martfury = martfury || {};

    martfury.init = function () {
        martfury.$body = $(document.body),
            martfury.$window = $(window),
            martfury.$header = $('#masthead');

        this.rowParallax();
        this.journey();
        this.testimonialSlides();
        this.partnerCarousel();
        this.gmaps();

        // products of category
        this.productsOfCategory();
        this.productsOfCategory2();

        // product tabs
        this.productsTabs();

        // category Tabs
        this.categoryTabs();

        // products carousel
        this.productsCarousel();
        this.dealsOfDay();
        this.productDealsCarousel();

        this.topSelling();
        this.productsListCarousel();

        /*Couterup*/
        if ($.fn.counterUp) {
            $('.martfury-counter .counter-value').counterUp();
        }

    };


    /*
     * Vc row parallax
     */
    martfury.rowParallax = function () {
        var $parallaxsRow = $('.vc_row.parallax');
        for (var i = 0; i < $parallaxsRow.length; i++) {
            $($parallaxsRow[i]).parallax('50%', 0.6);
        }
    };

    martfury.journey = function () {
        $('.martfury-journey').each(function () {
            var $el = $(this),
                $tabs = $el.find('ul li a'),
                $first = $tabs.filter(':first'),
                $content = $el.find('.journey-wrapper'),
                width = $el.find('ul').width(),
                num = $el.attr('data-number'),
                space, pos, val;

            if (num == 1) {
                space = 0;
            } else {
                space = (width - 40) / (num - 1);
            }

            for (var i = 1; i <= num; i++) {
                var $this = $('.journey-tab-' + i);
                if (martfuryShortCode.direction === 'true') {
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


                $this.css(pos, val + 15);
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

    /**
     * Init testimonial carousel
     */
    martfury.testimonialSlides = function () {
        if (martfuryShortCode.length === 0 || typeof martfuryShortCode.testimonial === 'undefined') {
            return;
        }

        $.each(martfuryShortCode.testimonial, function (id, testimonialData) {
            var $testimonial = $(document.getElementById(id));

            $testimonial.not('.slick-initialized').slick({
                rtl: (martfuryShortCode.direction === 'true'),
                slidesToShow: 2,
                infinite: testimonialData.autoplay,
                arrows: testimonialData.nav,
                prevArrow: '<div class="mf-left-arrow"><i class="icon-chevron-left"></i></div>',
                nextArrow: '<div class="mf-right-arrow"><i class="icon-chevron-right"></i></div>',
                autoplay: testimonialData.autoplay,
                autoplaySpeed: testimonialData.autoplay_speed,
                speed: 800,
                dots: testimonialData.dot,
                responsive: [
                    {
                        breakpoint: 1200,
                        settings: {
                            arrows: false,
                            dots: true
                        }
                    },
                    {
                        breakpoint: 768,
                        settings: {
                            arrows: false,
                            dots: true
                        }
                    },
                    {
                        breakpoint: 480,
                        settings: {
                            slidesToShow: 1,
                            arrows: false,
                            dots: true
                        }
                    }
                ]
            });

            $testimonial.on('afterChange', function () {
                martfury.lazyLoad($testimonial);
            });
        });
    };

    martfury.partnerCarousel = function () {
        $('.martfury-partner.carousel-type').each(function () {
            var $this = $(this),
                $items = $this.find('.list-item'),
                columns = $this.data('columns'),
                autoplay = $this.data('auto'),
                autoplaySpeed = autoplay;

            autoplay = parseInt(autoplaySpeed) > 0 ? true : false;

            $items.not('.slick-initialized').slick({
                rtl: (martfuryShortCode.direction === 'true'),
                slidesToShow: columns,
                infinite: false,
                arrows: false,
                dots: false,
                autoplay: autoplay,
                autoplaySpeed: autoplaySpeed,
                responsive: [
                    {
                        breakpoint: 1200,
                        settings: {
                            slidesToShow: 4
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
        });
    };

    martfury.productsOfCategory = function () {
        if (martfuryShortCode.length === 0 || typeof martfuryShortCode.productsOfCategory === 'undefined') {
            return;
        }
        $.each(martfuryShortCode.productsOfCategory, function (id, productsOfCategoryData) {
            var $viewPort = $(document.getElementById(id));
            martfury.catBannerCarousel($viewPort.find('.images-list'), productsOfCategoryData);
        });

        $(window).on('scroll', function () {
            var offSet = 0;

            $.each(martfuryShortCode.productsOfCategory, function (id, productsOfCategoryData) {
                var $viewPort = $(document.getElementById(id));
                if (!$viewPort.hasClass('no-infinite')) {
                    if ($viewPort.is(':in-viewport(' + offSet + ')')) {
                        productsOfCatAjax($viewPort, productsOfCategoryData);
                        $viewPort.addClass('no-infinite');
                    }
                }
            });


        }).trigger('scroll');

        function productsOfCatAjax($viewPort, productsOfCategoryData) {
            $.ajax({
                url: martfuryData.ajax_url,
                dataType: 'json',
                method: 'post',
                data: {
                    action: 'martfury_get_shortcode_ajax',
                    nonce: martfuryData.nonce,
                    params: productsOfCategoryData.params,
                    element: 'productsOfCat'
                },
                success: function (response) {
                    $viewPort.html(response.data);
                    martfury.lazyLoad($viewPort);
                    martfury.catBannerCarousel($viewPort.find('.images-list'), productsOfCategoryData);
                    $(document.body).trigger('martfury_get_products_ajax_success');
                }
            });
        }


    };

    martfury.productsOfCategory2 = function () {
        if (martfuryShortCode.length === 0 || typeof martfuryShortCode.productsOfCategory2 === 'undefined') {
            return;
        }
        $.each(martfuryShortCode.productsOfCategory2, function (id, productsOfCategoryData) {
            var $viewPort = $(document.getElementById(id));
            martfury.catBannerCarousel($viewPort.find('.images-list'), productsOfCategoryData);
        });

        $(window).on('scroll', function () {
            var offSet = 0;

            $.each(martfuryShortCode.productsOfCategory2, function (id, productsOfCategoryData) {
                var $viewPort = $(document.getElementById(id));
                if (!$viewPort.hasClass('no-infinite')) {
                    if ($viewPort.is(':in-viewport(' + offSet + ')')) {
                        productsOfCatAjax($viewPort, productsOfCategoryData);
                        $viewPort.addClass('no-infinite');
                    }
                }
            });


        }).trigger('scroll');

        function productsOfCatAjax($viewPort, productsOfCategoryData) {
            $.ajax({
                url: martfuryData.ajax_url,
                dataType: 'json',
                method: 'post',
                data: {
                    action: 'martfury_get_shortcode_ajax',
                    nonce: martfuryData.nonce,
                    params: productsOfCategoryData.params,
                    element: 'productsOfCat2'
                },
                success: function (response) {
                    $viewPort.html(response.data);
                    martfury.lazyLoad($viewPort);
                    martfury.catBannerCarousel($viewPort.find('.images-list'), productsOfCategoryData);
                    martfury.getproductsCarousel($viewPort.find('.mf-products-tabs'), productsOfCategoryData);
                    $(document.body).trigger('martfury_get_products_ajax_success');
                    $(document.body).trigger('martfury_get_tabs_ajax_success');
                }
            });
        }

    };

    martfury.catBannerCarousel = function ($id, productsOfCategoryData) {
        $id.not('.slick-initialized').slick({
            rtl: (martfuryShortCode.direction === 'true'),
            slidesToShow: 1,
            arrows: productsOfCategoryData.navigation,
            prevArrow: '<span class="icon-chevron-left slick-prev-arrow"></span>',
            nextArrow: '<span class="icon-chevron-right slick-next-arrow"></span>',
            infinite: productsOfCategoryData.infinite,
            autoplay: productsOfCategoryData.autoplay,
            autoplaySpeed: productsOfCategoryData.autoplay_speed,
            dots: productsOfCategoryData.pagination
        });

        $id.on('afterChange', function () {
            martfury.lazyLoad($id);
        });
    };

    /**
     * Products Tabs
     */
    martfury.productsTabs = function () {
        if (martfuryShortCode.length === 0 || typeof martfuryShortCode.productsTabs === 'undefined') {
            return;
        }

        $.each(martfuryShortCode.productsTabs, function (id, productsTabsData) {
            var $viewPort = $(document.getElementById(id));
            martfury.getproductsCarousel($viewPort, productsTabsData);

            $viewPort.find('.tabs-nav').on('click', 'a', function (e) {
                getProductsAJAXHandler($(this), $viewPort, productsTabsData);
            });
        });

        $(window).on('scroll', function () {
            var offSet = 0;

            $.each(martfuryShortCode.productsTabs, function (id, productsTabsData) {
                var $viewPort = $(document.getElementById(id));
                if (!$viewPort.hasClass('no-infinite')) {
                    if ($viewPort.is(':in-viewport(' + offSet + ')')) {
                        productsTabsAjax($viewPort, productsTabsData);
                        $viewPort.addClass('no-infinite');
                    }
                }
            });


        }).trigger('scroll');

        function productsTabsAjax($viewPort, productsTabsData) {
            $.ajax({
                url: martfuryData.ajax_url,
                dataType: 'json',
                method: 'post',
                data: {
                    action: 'martfury_get_shortcode_ajax',
                    nonce: martfuryData.nonce,
                    params: productsTabsData.params,
                    element: 'productTabs'
                },
                success: function (response) {
                    $viewPort.find('.tabs-content').html(response.data);
                    martfury.lazyLoad($viewPort);
                    martfury.getproductsCarousel($viewPort, productsTabsData);
                    $(document.body).trigger('martfury_get_tabs_ajax_success');
                    $(document.body).trigger('martfury_get_products_ajax_success');
                }
            });
        }

        function getProductsAJAXHandler($el, $tabs, productsTabsData) {

            if (typeof wc_add_to_cart_params === 'undefined') {
                return;
            }

            var tab = $el.data('href'),
                $content = $tabs.find('.tabs-' + tab);

            if ($content.hasClass('tab-loaded')) {
                return;
            }

            var data = {
                    'columns': productsTabsData.pro_columns,
                    'products': tab,
                    'per_page': productsTabsData.per_page,
                    'product_cats': productsTabsData.pro_cats,
                },
                ajax_url = wc_add_to_cart_params.wc_ajax_url.toString().replace('%%endpoint%%', 'mf_wpbakery_load_products');

            $.post(
                ajax_url,
                data,
                function (response) {
                    if (!response) {
                        return;
                    }
                    $content.html(response.data);
                    martfury.lazyLoad($content);
                    martfury.getproductsCarousel($content, productsTabsData);
                    $content.addClass('tab-loaded');
                }
            );
        };


    };

    /**
     * Products carousel
     */
    martfury.productsCarousel = function () {
        if (martfuryShortCode.length === 0 || typeof martfuryShortCode.productsCarousel === 'undefined') {
            return;
        }

        $.each(martfuryShortCode.productsCarousel, function (id, productsData) {
            var $viewPort = $(document.getElementById(id));
            martfury.getproductsCarousel($viewPort, productsData);
        });

        $(window).on('scroll', function () {
            var offSet = 0;

            $.each(martfuryShortCode.productsCarousel, function (id, productsData) {
                var $viewPort = $(document.getElementById(id));
                if (!$viewPort.hasClass('no-infinite')) {
                    if ($viewPort.is(':in-viewport(' + offSet + ')')) {
                        productsCarouselAjax($viewPort, productsData);
                        $viewPort.addClass('no-infinite');
                    }
                }
            });


        }).trigger('scroll');

        function productsCarouselAjax($viewPort, productsData) {
            $.ajax({
                url: martfuryData.ajax_url,
                dataType: 'json',
                method: 'post',
                data: {
                    action: 'martfury_get_shortcode_ajax',
                    nonce: martfuryData.nonce,
                    params: productsData.params,
                    element: 'productsCarousel'
                },
                success: function (response) {
                    $viewPort.html(response.data);
                    martfury.lazyLoad($viewPort);
                    martfury.getproductsCarousel($viewPort, productsData);
                    $(document.body).trigger('martfury_get_products_ajax_success');
                }
            });
        }


    };

    /**
     * Products carousel
     */
    martfury.dealsOfDay = function () {
        if (martfuryShortCode.length === 0 || typeof martfuryShortCode.DealsOfDay === 'undefined') {
            return;
        }

        $.each(martfuryShortCode.DealsOfDay, function (id, productsData) {
            var $viewPort = $(document.getElementById(id));
            $viewPort.find('ul.products').not('.slick-initialized').slick({
                rtl: (martfuryShortCode.direction === 'true'),
                slidesToShow: productsData.pro_columns,
                slidesToScroll: productsData.pro_columns,
                arrows: productsData.pro_navigation,
                dots: productsData.pro_navigation,
                infinite: productsData.pro_infinite,
                prevArrow: '<span class="icon-chevron-left slick-prev-arrow"></span>',
                nextArrow: '<span class="icon-chevron-right slick-next-arrow"></span>',
                autoplay: productsData.pro_autoplay,
                autoplaySpeed: productsData.pro_autoplay_speed,
                responsive: [
                    {
                        breakpoint: 1366,
                        settings: {
                            slidesToShow: parseInt(productsData.pro_columns) > 5 ? 5 : productsData.pro_columns,
                            slidesToScroll: parseInt(productsData.pro_columns) > 5 ? 5 : productsData.pro_columns
                        }
                    },
                    {
                        breakpoint: 1200,
                        settings: {
                            slidesToShow: parseInt(productsData.pro_columns) > 4 ? 4 : productsData.pro_columns,
                            slidesToScroll: parseInt(productsData.pro_columns) > 4 ? 4 : productsData.pro_columns
                        }
                    },
                    {
                        breakpoint: 992,
                        settings: {
                            slidesToShow: 3,
                            slidesToScroll: 3
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

            $viewPort.on('afterChange', function () {
                martfury.lazyLoad($viewPort);
            });
        });
    };


    /**
     * Products carousel
     */
    martfury.productDealsCarousel = function () {
        if (martfuryShortCode.length === 0 || typeof martfuryShortCode.productDealsCarousel === 'undefined') {
            return;
        }

        $.each(martfuryShortCode.productDealsCarousel, function (id, productsData) {
            var $viewPort = $(document.getElementById(id));
            $viewPort.find('.products').not('.slick-initialized').slick({
                rtl: (martfuryShortCode.direction === 'true'),
                slidesToShow: productsData.pro_columns,
                slidesToScroll: productsData.pro_columns,
                arrows: productsData.pro_navigation,
                infinite: productsData.pro_infinite,
                prevArrow: $viewPort.find('.slick-prev-arrow'),
                nextArrow: $viewPort.find('.slick-next-arrow'),
                autoplay: productsData.pro_autoplay,
                autoplaySpeed: productsData.pro_autoplay_speed
            });

            $viewPort.on('afterChange', function () {
                martfury.lazyLoad($viewPort);
            });
        });
    };

    /**
     * Top Selling Products
     */
    martfury.topSelling = function () {
        if (martfuryShortCode.length === 0 || typeof martfuryShortCode.topSelling === 'undefined') {
            return;
        }

        $.each(martfuryShortCode.topSelling, function (id, productsData) {
            var $viewPort = $(document.getElementById(id));
            martfury.getproductsCarousel($viewPort, productsData);
        });

        $(window).on('scroll', function () {
            var offSet = 0;

            $.each(martfuryShortCode.topSelling, function (id, productsData) {
                var $viewPort = $(document.getElementById(id));
                if (!$viewPort.hasClass('no-infinite')) {
                    if ($viewPort.is(':in-viewport(' + offSet + ')')) {
                        productsCarouselAjax($viewPort, productsData);
                        $viewPort.addClass('no-infinite');
                    }
                }

            });


        }).trigger('scroll');

        function productsCarouselAjax($viewPort, productsData) {
            $.ajax({
                url: martfuryData.ajax_url,
                dataType: 'json',
                method: 'post',
                data: {
                    action: 'martfury_get_shortcode_ajax',
                    nonce: martfuryData.nonce,
                    params: productsData.params,
                    element: 'topSelling'
                },
                success: function (response) {
                    $viewPort.html(response.data);
                    martfury.lazyLoad($viewPort);
                    martfury.getproductsCarousel($viewPort, productsData);
                    $(document.body).trigger('martfury_get_products_ajax_success');
                }
            });
        }


    };

    /**
     * Top Selling Products
     */
    martfury.productsListCarousel = function () {
        if (martfuryShortCode.length === 0 || typeof martfuryShortCode.productsListCarousel === 'undefined') {
            return;
        }

        $.each(martfuryShortCode.productsListCarousel, function (id, productsData) {
            var $viewPort = $(document.getElementById(id));
            $viewPort.find('ul.products').not('.slick-initialized').slick({
                rtl: (martfuryShortCode.direction === 'true'),
                slidesToShow: 1,
                slidesToScroll: 1,
                arrows: false,
                dots: productsData.dots,
                infinite: productsData.infinite,
                autoplay: productsData.autoplay,
                autoplaySpeed: productsData.autoplay_speed
            });

            $viewPort.on('afterChange', function () {
                martfury.lazyLoad($viewPort);
            });
        });

    };

    martfury.getproductsCarousel = function ($id, productsData) {
        $id.find('ul.products').not('.slick-initialized').slick({
            rtl: (martfuryShortCode.direction === 'true'),
            slidesToShow: productsData.pro_columns,
            slidesToScroll: productsData.pro_columns,
            arrows: productsData.pro_navigation,
            dots: productsData.pro_navigation,
            infinite: productsData.pro_infinite,
            prevArrow: '<span class="icon-chevron-left slick-prev-arrow"></span>',
            nextArrow: '<span class="icon-chevron-right slick-next-arrow"></span>',
            autoplay: productsData.pro_autoplay,
            autoplaySpeed: productsData.pro_autoplay_speed,
            responsive: [
                {
                    breakpoint: 1600,
                    settings: {
                        slidesToShow: parseInt(productsData.pro_columns) > 6 ? 6 : productsData.pro_columns,
                        slidesToScroll: parseInt(productsData.pro_columns) > 6 ? 6 : productsData.pro_columns
                    }
                },
                {
                    breakpoint: 1366,
                    settings: {
                        slidesToShow: parseInt(productsData.pro_columns) > 5 ? 5 : productsData.pro_columns,
                        slidesToScroll: parseInt(productsData.pro_columns) > 5 ? 5 : productsData.pro_columns
                    }
                },
                {
                    breakpoint: 1200,
                    settings: {
                        slidesToShow: parseInt(productsData.pro_columns) > 4 ? 4 : productsData.pro_columns,
                        slidesToScroll: parseInt(productsData.pro_columns) > 4 ? 4 : productsData.pro_columns
                    }
                },
                {
                    breakpoint: 992,
                    settings: {
                        slidesToShow: 3,
                        slidesToScroll: 3
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

        $id.on('afterChange', function () {
            martfury.lazyLoad($id);
        });
    };

    /**
     * Category Tabs
     */
    martfury.categoryTabs = function () {
        var $tabs = $('.mf-category-tabs');
        if ($tabs.length < 1) {
            return;
        }

        $tabs.find('ul.tabs-nav').not('.slick-initialized').slick({
            rtl: (martfuryShortCode.direction === 'true'),
            slidesToShow: 8,
            infinite: false,
            prevArrow: '<span class="icon-chevron-left slick-prev-arrow"></span>',
            nextArrow: '<span class="icon-chevron-right slick-next-arrow"></span>',
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
    };

    /**
     * LazyLoad
     */
    martfury.lazyLoad = function ($els) {
        $els.find('img.lazy').lazyload({
            load: function () {
                $(this).removeClass('lazy');
            }
        }).trigger('appear');
    };


    /**
     * Init Google maps
     */
    martfury.gmaps = function () {

        if (martfuryShortCode.length === 0 || typeof martfuryShortCode.map === 'undefined') {
            return;
        }

        var mapOptions = {
                scrollwheel: false,
                draggable: true,
                zoom: 10,
                mapTypeId: google.maps.MapTypeId.ROADMAP,
                panControl: false,
                zoomControl: true,
                zoomControlOptions: {
                    style: google.maps.ZoomControlStyle.SMALL
                },
                scaleControl: false,
                streetViewControl: false

            },
            customMap;

        var bounds = new google.maps.LatLngBounds();
        var infoWindow = new google.maps.InfoWindow();


        $.each(martfuryShortCode.map, function (id, mapData) {
            var map_color = mapData.map_color,
                road_highway_color = mapData.road_highway_color;

            var styles =
                [
                    {
                        'featureType': 'administrative',
                        'elementType': 'labels.text.fill',
                        'stylers': [{'color': '#444444'}]
                    },
                    {
                        'featureType': 'landscape',
                        'elementType': 'all',
                        'stylers': [{'color': '#f2f2f2'}]
                    },
                    {
                        'featureType': 'landscape',
                        'elementType': 'geometry.fill',
                        'stylers': [{'color': '#f2f2f2'}]
                    },
                    {
                        'featureType': 'landscape',
                        'elementType': 'geometry.stroke',
                        'stylers': [{'color': '#000000'}]
                    },
                    {
                        'featureType': 'poi',
                        'elementType': 'all',
                        'stylers': [{'visibility': 'off'}]
                    },
                    {
                        'featureType': 'road',
                        'elementType': 'all',
                        'stylers': [{'saturation': -100}, {'lightness': 45}]
                    },
                    {
                        'featureType': 'road.highway',
                        'elementType': 'all',
                        'stylers': [{'visibility': 'simplified'}]
                    },
                    {
                        'featureType': 'road.highway',
                        'elementType': 'geometry.fill',
                        'stylers': [{'color': road_highway_color}]
                    },
                    {
                        'featureType': 'road.arterial',
                        'elementType': 'labels.icon',
                        'stylers': [{'visibility': 'off'}]
                    },
                    {
                        'featureType': 'road.local',
                        'elementType': 'geometry.fill',
                        'stylers': [{'color': '#e6e6e6'}]
                    },
                    {
                        'featureType': 'transit',
                        'elementType': 'all',
                        'stylers': [{'visibility': 'off'}]
                    },
                    {
                        'featureType': 'water',
                        'elementType': 'all',
                        'stylers': [{'visibility': 'on'}, {'color': map_color}]
                    }
                ];

            customMap = new google.maps.StyledMapType(styles,
                {name: 'Styled Map'});

            if (mapData.number > 1) {
                mutiMaps(infoWindow, bounds, mapOptions, mapData, id, styles, customMap);
            } else {
                singleMap(mapOptions, mapData, id, styles, customMap);
            }

        });
    };

    function singleMap(mapOptions, mapData, id, styles, customMap) {
        var map,
            marker,
            location = new google.maps.LatLng(mapData.lat, mapData.lng);

        // Update map options
        mapOptions.zoom = parseInt(mapData.zoom, 10);
        mapOptions.center = location;
        mapOptions.mapTypeControlOptions = {
            mapTypeIds: [google.maps.MapTypeId.ROADMAP]
        };

        // Init map
        map = new google.maps.Map(document.getElementById(id), mapOptions);

        // Create marker options
        var markerOptions = {
            map: map,
            position: location
        };
        if (mapData.marker) {
            markerOptions.icon = {
                url: mapData.marker
            };
        }

        map.mapTypes.set('map_style', customMap);
        map.setMapTypeId('map_style');

        // Init marker
        marker = new google.maps.Marker(markerOptions);

        if (mapData.info) {
            var infoWindow = new google.maps.InfoWindow({
                content: '<div class="info-box mf-map">' + mapData.info + '</div>',
                maxWidth: 600
            });

            google.maps.event.addListener(marker, 'click', function () {
                infoWindow.open(map, marker);
            });
        }
    }

    function mutiMaps(infoWindow, bounds, mapOptions, mapData, id, styles, customMap) {

        // Display a map on the page
        mapOptions.zoom = parseInt(mapData.zoom, 10);
        mapOptions.mapTypeControlOptions = {
            mapTypeIds: [google.maps.MapTypeId.ROADMAP]
        };

        var map = new google.maps.Map(document.getElementById(id), mapOptions);
        map.mapTypes.set('map_style', customMap);
        map.setMapTypeId('map_style');
        for (var i = 0; i < mapData.number; i++) {
            var lats = mapData.lat,
                lng = mapData.lng,
                info = mapData.info;

            var position = new google.maps.LatLng(lats[i], lng[i]);
            bounds.extend(position);

            // Create marker options
            var markerOptions = {
                map: map,
                position: position
            };
            if (mapData.marker) {
                markerOptions.icon = {
                    url: mapData.marker
                };
            }

            // Init marker
            var marker = new google.maps.Marker(markerOptions);

            // Allow each marker to have an info window
            googleMaps(infoWindow, map, marker, info[i]);

            // Automatically center the map fitting all markers on the screen
            map.fitBounds(bounds);
        }
    }

    function googleMaps(infoWindow, map, marker, info) {
        google.maps.event.addListener(marker, 'click', function () {
            infoWindow.setContent(info);
            infoWindow.open(map, marker);
        });
    }

    /**
     * Document ready
     */
    $(function () {
        martfury.init();
    });

})(jQuery);