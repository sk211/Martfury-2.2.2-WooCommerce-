$( document ).ready( function( $ ) {
	// Sale price schedule.
	$( '._deal_quantity_field' ).each( function() {
		var $these_sale_dates = $( this );
		var sale_schedule_set = false;
		var $wrap = $these_sale_dates.closest( 'div, table' );

		$these_sale_dates.find( 'input' ).each( function() {
			if ( '' !== $( this ).val() ) {
				sale_schedule_set = true;
			}
		});

		if ( sale_schedule_set ) {
			$wrap.find( '.sale_price_dates_fields' ).show();
			$wrap.find( '._deal_quantity_field' ).show();
            $wrap.find( '._deal_sales_counts_field' ).show();
			$wrap.find( '.sale_schedule' ).hide();
		} else {
			$wrap.find( '.sale_price_dates_fields' ).hide();
			$wrap.find( '._deal_quantity_field' ).hide();
            $wrap.find( '._deal_sales_counts_field' ).hide();
			$wrap.find( '.sale_schedule' ).show();
		}
	});

	$( '#woocommerce-product-data' ).on( 'click', '.sale_schedule', function() {
		var $wrap = $( this ).closest( 'div, table' );

		$wrap.find( '._deal_quantity_field' ).show();
        $wrap.find( '._deal_sales_counts_field' ).show();

		return false;
	}).on( 'click', '.cancel_sale_schedule', function() {
		var $wrap = $( this ).closest( 'div, table' );

		$wrap.find( '._deal_quantity_field' ).hide();
		$wrap.find( '.deal-sold-counts' ).hide();
        $wrap.find( '._deal_sales_counts_field' ).hide();
		$wrap.find( '._deal_quantity_field' ).find( 'input' ).val( '' );

		return false;
	});
} );