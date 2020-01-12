<div class="mf-vendors-links">
	<span class="button active"><?php echo esc_html_e( 'Dashboard', 'martfury' ); ?></span>
	<a href="<?php echo esc_url($shop_page); ?>" class="button"><?php esc_html_e( 'View Your Store', 'martfury' ); ?></a>
	<a href="<?php echo esc_url($settings_page); ?>" class="button"><?php esc_html_e( 'Store Settings', 'martfury' ); ?></a>

	<?php if ( $can_submit ) { ?>
		<a target="_TOP" href="<?php echo esc_url($submit_link); ?>" class="button"><?php esc_html_e( 'Add New Product', 'martfury' ); ?></a>
		<a target="_TOP" href="<?php echo esc_url($edit_link); ?>" class="button"><?php esc_html_e( 'Edit Products', 'martfury' ); ?></a>
	<?php }
	do_action( 'wcvendors_after_links' );
	?>
</div>