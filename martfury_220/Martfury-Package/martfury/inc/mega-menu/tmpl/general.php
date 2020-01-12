<?php
global $wp_widget_factory;
?>
<div id="tamm-panel-general" class="tamm-panel-general tamm-panel">
	<div class="mr-tamm-panel-box">
		<p>
			<label>
				<input type="checkbox" name="{{ taMegaMenu.getFieldName( 'visibleText', data.data['menu-item-db-id'] ) }}" value="1" {{ data.megaData.visibleText ? 'checked="checked"' : '' }} >
				<?php esc_html_e( 'Visible Text', 'martfury' ) ?>
			</label>
		</p>

		<p>
			<label>
				<input type="checkbox" name="{{ taMegaMenu.getFieldName( 'hideText', data.data['menu-item-db-id'] ) }}" value="1" {{ data.megaData.hideText ? 'checked="checked"' : '' }} >
				<?php esc_html_e( 'Hide Text', 'martfury' ) ?>
			</label>
		</p>
	</div>
	<div class="mr-tamm-panel-box">
		<p>
			<label>
				<input type="checkbox" name="{{ taMegaMenu.getFieldName( 'hot', data.data['menu-item-db-id'] ) }}" value="1" {{ data.megaData.hot ? 'checked="checked"': '' }} >
				<?php esc_html_e( 'Hot', 'martfury' ) ?>
			</label>
		</p>

		<p>
			<label>
				<input type="checkbox" name="{{ taMegaMenu.getFieldName( 'new', data.data['menu-item-db-id'] ) }}" value="1" {{ data.megaData.new ? 'checked="checked"' : '' }} >
				<?php esc_html_e( 'New', 'martfury' ) ?>
			</label>
		</p>

		<p>
			<label>
				<input type="checkbox" name="{{ taMegaMenu.getFieldName( 'trending', data.data['menu-item-db-id'] ) }}" value="1" {{ data.megaData.trending ? 'checked="checked"' : '' }} >
				<?php esc_html_e( 'Trending', 'martfury' ) ?>
			</label>
		</p>
	</div>
</div>