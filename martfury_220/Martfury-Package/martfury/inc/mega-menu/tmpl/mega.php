<div id="tamm-panel-mega" class="tamm-panel-mega tamm-panel">
	<p class="mr-tamm-panel-box">
		<label>
			<input type="checkbox" name="{{ taMegaMenu.getFieldName( 'mega', data.data['menu-item-db-id'] ) }}" value="1" {{ data.megaData.mega ? 'checked="checked"' : '' }} >
			<?php esc_html_e( 'Mega Menu', 'martfury' ) ?>
		</label>
	</p>

	<p class="mr-tamm-panel-box-large">
		<label>
			<?php esc_html_e( 'Mega Width', 'martfury' ) ?><br>
			<input type="text" name="{{ taMegaMenu.getFieldName( 'mega_width', data.data['menu-item-db-id'] ) }}" placeholder="100%" value="{{ data.megaData.mega_width }}">
		</label>
	</p>

	<hr>

	<div id="tamm-mega-content" class="tamm-mega-content">
		<#
		var items = _.filter( data.children, function( item ) {
		    return item.subDepth == 0;
		} );
		#>

		<# _.each( items, function( item, index ) { #>

		<div class="tamm-submenu-column" data-width="{{ item.megaData.width }}">
			<ul>
				<li class="menu-item menu-item-depth-{{ item.subDepth }}">
					<# if ( item.megaData.icon ) { #>
					<i class="{{ item.megaData.icon }}"></i>
					<# } #>
					{{{ item.data['menu-item-title'] }}}
					<# if ( item.subDepth == 0 ) { #>
					<span class="tamm-column-handle tamm-resizable-e"><i class="dashicons dashicons-arrow-left-alt2"></i></span>
					<span class="tamm-column-handle tamm-resizable-w"><i class="dashicons dashicons-arrow-right-alt2"></i></span>
					<input type="hidden" name="{{ taMegaMenu.getFieldName( 'width', item.data['menu-item-db-id'] ) }}" value="{{ item.megaData.width }}" class="menu-item-width">
					<# } #>
				</li>
			</ul>
		</div>

		<# } ) #>
	</div>
</div>
