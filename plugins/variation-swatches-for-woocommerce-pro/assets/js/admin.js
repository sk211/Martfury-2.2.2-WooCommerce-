var frame,
	tawcvs = tawcvs || {};

jQuery( document ).ready( function ( $ ) {
	'use strict';
	var wp = window.wp,
		$body = $( 'body' );

	$( '#term-color' ).wpColorPicker();

	// Update attribute image
	$body.on( 'click', '.tawcvs-upload-image-button', function ( event ) {
		event.preventDefault();

		var $button = $( this );

		// If the media frame already exists, reopen it.
		if ( frame ) {
			frame.open();
			return;
		}

		// Create the media frame.
		frame = wp.media.frames.downloadable_file = wp.media( {
			title   : tawcvs.i18n.mediaTitle,
			button  : {
				text: tawcvs.i18n.mediaButton
			},
			multiple: false
		} );

		// When an image is selected, run a callback.
		frame.on( 'select', function () {
			var attachment = frame.state().get( 'selection' ).first().toJSON();

			$button.siblings( 'input.tawcvs-term-image' ).val( attachment.id );
			$button.siblings( '.tawcvs-remove-image-button' ).show();
			var attachment_image = attachment.sizes && attachment.sizes.thumbnail ? attachment.sizes.thumbnail.url : attachment.url;
			$button.parent().prev( '.tawcvs-term-image-thumbnail' ).find( 'img' ).attr( 'src', attachment_image );
		} );

		// Finally, open the modal.
		frame.open();

	} ).on( 'click', '.tawcvs-remove-image-button', function () {
		var $button = $( this );

		$button.siblings( 'input.tawcvs-term-image' ).val( '' );
		$button.siblings( '.tawcvs-remove-image-button' ).show();
		$button.parent().prev( '.tawcvs-term-image-thumbnail' ).find( 'img' ).attr( 'src', tawcvs.placeholder );

		return false;
	} );

	// Toggle add new attribute term modal
	var $modal = $( '#tawcvs-modal-container' ),
		$spinner = $modal.find( '.spinner' ),
		$msg = $modal.find( '.message' ),
		$metabox = null;

	$body.on( 'click', '.tawcvs_add_new_attribute', function ( e ) {
		e.preventDefault();
		var $button = $( this ),
			taxInputTemplate = wp.template( 'tawcvs-input-tax' ),
			data = {
				type: $button.data( 'type' ),
				tax : $button.closest( '.woocommerce_attribute' ).data( 'taxonomy' )
			};

		// Insert input
		$modal.find( '.tawcvs-term-swatch' ).html( $( '#tmpl-tawcvs-input-' + data.type ).html() );
		$modal.find( '.tawcvs-term-tax' ).html( taxInputTemplate( data ) );

		if ( 'color' == data.type ) {
			$modal.find( 'input.tawcvs-input-color' ).wpColorPicker();
		}

		$metabox = $button.closest( '.woocommerce_attribute.wc-metabox' );
		$modal.show();
	} ).on( 'click', '.tawcvs-modal-close, .tawcvs-modal-backdrop', function ( e ) {
		e.preventDefault();

		closeModal();
	} );

	// Send ajax request to add new attribute term
	$body.on( 'click', '.tawcvs-new-attribute-submit', function ( e ) {
		e.preventDefault();

		var $button = $( this ),
			type = $button.data( 'type' ),
			error = false,
			data = {};

		// Validate
		$modal.find( '.tawcvs-input' ).each( function () {
			var $this = $( this );

			if ( $this.attr( 'name' ) != 'slug' && !$this.val() ) {
				$this.addClass( 'error' );
				error = true;
			} else {
				$this.removeClass( 'error' );
			}

			data[$this.attr( 'name' )] = $this.val();
		} );

		if ( error ) {
			return;
		}

		// Send ajax request
		$spinner.addClass( 'is-active' );
		$msg.hide();
		wp.ajax.send( 'tawcvs_add_new_attribute', {
			data   : data,
			error  : function ( res ) {
				$spinner.removeClass( 'is-active' );
				$msg.addClass( 'error' ).text( res ).show();
			},
			success: function ( res ) {
				$spinner.removeClass( 'is-active' );
				$msg.addClass( 'success' ).text( res.msg ).show();

				$metabox.find( 'select.attribute_values' ).append( '<option value="' + res.id + '" selected="selected">' + res.name + '</option>' );
				$metabox.find( 'select.attribute_values' ).change();

				closeModal();
			}
		} );
	} );

	// Toggle the custom size on product data metabox
	$body.on( 'change', '#variable_product_swatches .size_field input', function() {
		var $field = $( this ).closest( '.form-field' );

		if ( 'custom' === $( 'input:checked', $field ).val() ) {
			$field.next( '.custom_size_field' ).show();
		} else {
			$field.next( '.custom_size_field' ).hide();
		}
	} );

	// Toggle swatches type options
	$body.on( 'change', '#variable_product_swatches .type_field select', function() {
		var type = $( this ).val();

		if ( 'default' !== type && 'select' !== type ) {
			$( this ).closest( '.options_group' ).siblings( '.swatches-options_group' ).children( '.swatch-' + type + 's' ).show().siblings().hide();
		} else {
			$( this ).closest( '.options_group' ).siblings( '.swatches-options_group' ).children().hide();
		}
	} );

	// Init color fields
	$( '.swatch-colors input', '#variable_product_swatches' ).wpColorPicker();

	// Uploading files
	var media_frame;

	$body.on( 'click', '#variable_product_swatches .swatch-images .edit-image', function ( event ) {
		event.preventDefault();

		var $this = $( this ),
			$remove = $this.siblings( '.remove-image' ),
			$image = $this.siblings( 'img' ),
			$input = $this.siblings( 'input' );

		// If the media frame already exists, reopen it.
		if ( media_frame ) {
			media_frame.off( 'select' );
		} else {
			// Create the media frame.
			media_frame = wp.media( {
				multiple: false
			} );
		}

		// When an image is selected, run a callback.
		media_frame.on( 'select', function () {
			var attachment = media_frame.state().get( 'selection' ).first().toJSON();

			$input.val( attachment.id );
			var attachment_image = attachment.sizes && attachment.sizes.thumbnail ? attachment.sizes.thumbnail.url : attachment.url;
			$image.attr( 'src', attachment_image );
			$remove.show();
		} );

		// Finally, open the modal.
		media_frame.open();
	} );

	$body.on( 'click', '#variable_product_swatches .swatch-images .remove-image', function ( event ) {
		event.preventDefault();

		var $this = $( this ),
			$image = $this.siblings( 'img' ),
			$input = $this.siblings( 'input' );

		$this.hide();
		$input.val( '' );
		$image.attr( 'src', $image.data( 'default' ) );
	} );

	// Reload the swatches tab when attributes updated
	$( '#variable_product_options' ).on( 'reload', function() {
		var this_page = window.location.toString();
		this_page = this_page.replace( 'post-new.php?', 'post.php?post=' + woocommerce_admin_meta_boxes.post_id + '&action=edit&' );

		$( '#variable_product_swatches' ).load( this_page + ' #variable_product_swatches_inner', function() {
			setTimeout( function() {
				// Init color fields
				$( '.swatch-colors input', '#variable_product_swatches' ).wpColorPicker();
			} );
		} );
	} );

	/**
	 * Close modal
	 */
	function closeModal() {
		$modal.find( '.tawcvs-term-name input, .tawcvs-term-slug input' ).val( '' );
		$spinner.removeClass( 'is-active' );
		$msg.removeClass( 'error success' ).hide();
		$modal.hide();
	}
} );

