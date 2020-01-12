jQuery(document).ready(function ($) {
	"use strict";

	// Uploading files
	var file_frame,
		$banner_ids = $('#mf_cat_banners_id'),
		$banner_link = $('#mf_cat_banners_link'),
		$cat_banner = $('#mf_cat_banners'),
		$cat_images = $cat_banner.find('.mf-cat-images');

	$cat_banner.on('click', '.upload_images_button', function (event) {
		var $el = $(this);

		event.preventDefault();

		// If the media frame already exists, reopen it.
		if (file_frame) {
			file_frame.open();
			return;
		}

		// Create the media frame.
		file_frame = wp.media.frames.downloadable_file = wp.media({
			multiple: true
		});

		// When an image is selected, run a callback.
		file_frame.on('select', function () {
			var selection = file_frame.state().get('selection'),
				attachment_ids = $banner_ids.val();

			selection.map(function (attachment) {
				attachment = attachment.toJSON();

				if (attachment.id) {
					attachment_ids = attachment_ids ? attachment_ids + ',' + attachment.id : attachment.id;
					var attachment_image = attachment.sizes && attachment.sizes.thumbnail ? attachment.sizes.thumbnail.url : attachment.url;

					$cat_images.append('<li class="image" data-attachment_id="' + attachment.id + '"><img src="' + attachment_image + '" width="100px" height="100px" /><ul class="actions"><li><a href="#" class="delete" title="' + $el.data('delete') + '">' + $el.data('text') + '</a></li></ul></li>');
				}

			});
			$banner_ids.val(attachment_ids);
		});


		// Finally, open the modal.
		file_frame.open();
	});

	// Image ordering.
	$cat_images.sortable({
		items               : 'li.image',
		cursor              : 'move',
		scrollSensitivity   : 40,
		forcePlaceholderSize: true,
		forceHelperSize     : false,
		helper              : 'clone',
		opacity             : 0.65,
		placeholder         : 'wc-metabox-sortable-placeholder',
		start               : function (event, ui) {
			ui.item.css('background-color', '#f6f6f6');
		},
		stop                : function (event, ui) {
			ui.item.removeAttr('style');
		},
		update              : function () {
			var attachment_ids = '';

			$cat_images.find('li.image').css('cursor', 'default').each(function () {
				var attachment_id = $(this).attr('data-attachment_id');
				attachment_ids = attachment_ids + attachment_id + ',';
			});

			$banner_ids.val(attachment_ids);
		}
	});

	// Remove images.
	$cat_banner.on('click', 'a.delete', function () {
		$(this).closest('li.image').remove();

		var attachment_ids = '';

		$cat_images.find('li.image').css('cursor', 'default').each(function () {
			var attachment_id = $(this).attr('data-attachment_id');
			attachment_ids = attachment_ids + attachment_id + ',';
		});

		$banner_ids.val(attachment_ids);

		return false;
	});

	// Only show the "remove image" button when needed
	var $banner_2_ids = $('#mf_cat_banners_2_id'),
		$banner_2_link = $('#mf-cat-banners_2_link'),
		$cat_banner_2 = $('#mf_cat_banners-2'),
		$cat_image = $cat_banner_2.find('.mf-cat-image'),
		file_frame_2;
	if (!$banner_2_ids.val()) {
		$cat_banner_2.find('.remove_banner_2_button').hide();
	}

	// Uploading files
	$cat_banner_2.on('click', '.upload_banner_2_button', function (event) {

		event.preventDefault();

		// If the media frame already exists, reopen it.
		if (file_frame_2) {
			file_frame_2.open();
			return;
		}

		// Create the media frame.
		file_frame_2 = wp.media.frames.downloadable_file = wp.media({
			multiple: false
		});

		// When an image is selected, run a callback.
		file_frame_2.on('select', function () {
			var attachment = file_frame_2.state().get('selection').first().toJSON();
			if (attachment.id) {
				var attachment_image = attachment.sizes && attachment.sizes.thumbnail ? attachment.sizes.thumbnail.url : attachment.url;
				$banner_2_ids.val(attachment.id);
				$cat_banner_2.find('.remove_banner_2_button').show();
				$cat_image.attr('src', attachment_image);
			}
		});

		// Finally, open the modal.
		file_frame_2.open();
	});

	$cat_banner_2.on('click', '.remove_banner_2_button', function () {
		var image_src = $cat_banner_2.data('rel');
		$cat_image.attr('src', image_src);
		$banner_2_ids.val('');
		$(this).hide();
		return false;
	});

	$(document).ajaxComplete(function (event, request, options) {
		if (request && 4 === request.readyState && 200 === request.status
			&& options.data && 0 <= options.data.indexOf('action=add-tag')) {

			var res = wpAjax.parseAjaxResponse(request.responseXML, 'ajax-response');
			if (!res || res.errors) {
				return;
			}
			// Clear Thumbnail fields on submit
			$cat_banner.find('li.image').remove();
			$banner_ids.val('');
			$banner_link.val('');
			$banner_2_ids.val('');
			$banner_2_link.val('');
			$cat_image.attr('src', $cat_banner_2.data('rel'));
			$cat_banner_2.find('.remove_banner_2_button').hide();
			return;
		}
	});


	$('#mf_cat_layout').on('change', function () {
		var layout = $(this).val();
		mfCatLayout(layout);
	}).trigger('change');

	var $banners_group = $('.mf-cat-banners-group'),
		$banners_2_group = $('.mf-cat-banners-2-group');

	$('#parent').on('change', function () {
		var layout = $('#mf_cat_layout').val();
		var parent_id = $(this).val();
		if (parent_id != '-1') {
			mfCatLayout(layout);
			$banners_group.hide();

			var parent_class = $(this).find('option:selected').attr('class');
			console.log(parent_class);
			if(  parent_class =='level-0' || typeof parent_class == 'undefined') {
				$banners_2_group.show();
			} else {
				$banners_2_group.hide();
			}
		} else {
			$banners_group.show();
			$banners_2_group.show();
			mfCatLayout(layout);

		}
	}).trigger('change');

	function mfCatLayout(layout) {
		var $elements = $('#mf-custom-elements');
		if (layout == '0') {
			$elements.hide()
		} else {
			$elements.show();

			$elements.find('.mf-custom-elements').hide();
			$elements.find('#mf-custom-elements-' + layout).show();
		}
	}

});
