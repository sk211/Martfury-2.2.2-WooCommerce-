jQuery(document).ready(function ($) {
    "use strict";

    // Show/hide settings for post format when choose post format
    var $format = $('#post-formats-select').find('input.post-format'),
        $formatBox = $('#post-format-settings');

    $format.on('change', function () {
        var type = $(this).filter(':checked').val();
        postFormatSettings(type);
    });
    $format.filter(':checked').trigger('change');

    $(document.body).on('change', '.editor-post-format .components-select-control__input', function () {
        var type = $(this).val();
        postFormatSettings(type);
    });

    $(window).load(function () {
        var $el = $(document.body).find('.editor-post-format .components-select-control__input'),
            type = $el.val();
        postFormatSettings(type);
    });

    function postFormatSettings(type) {
        $formatBox.hide();
        if ($formatBox.find('.rwmb-field').hasClass(type)) {
            $formatBox.show();
        }

        $formatBox.find('.rwmb-field').slideUp();
        $formatBox.find('.' + type).slideDown();
    }

    // Show/hide settings for custom layout settings
    $('#custom_layout').on('change', function () {
        if ($(this).is(':checked')) {
            $('.rwmb-field.custom-layout').slideDown();
        }
        else {
            $('.rwmb-field.custom-layout').slideUp();
        }
    }).trigger('change');

    $('#post-style-settings #post_style').on('change', function () {
        if ($(this).val() == '2') {
            $('#post-style-settings').find('.show-post-header-2').slideDown();
        }
        else {
            $('#post-style-settings').find('.show-post-header-2').slideUp();
        }

    }).trigger('change');

    $('#product-360-view').find('.rwmb-image-item').css({
        width: '50px'
    });

    // Show/hide settings for template settings
    $('#page_template').on('change', function () {
        pageHeaderSettings($(this));
    }).trigger('change');

    $(document.body).on('change', '.editor-page-attributes__template .components-select-control__input', function () {
        pageHeaderSettings($(this));
    });

    $(window).load(function () {
        var $el = $(document.body).find('.editor-page-attributes__template .components-select-control__input');
        pageHeaderSettings($el);
    });

    function pageHeaderSettings($el) {

        if ($el.val() == 'template-homepage.php' ||
            $el.val() == 'template-home-full-width.php' ||
            $el.val() == 'template-coming-soon-page.php') {
            $('#page-header-settings').hide();
        } else {
            $('#page-header-settings').show();
        }

        if ($el.val() == 'template-homepage.php' ||
            $el.val() == 'template-home-full-width.php' ||
            $el.val() == 'template-full-width.php' ||
            $el.val() == 'template-coming-soon-page.php') {
            $('#display-settings').hide();
        } else {
            $('#display-settings').show();
        }
    }
});
