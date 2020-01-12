<div id="topbar" class="topbar">
    <div class="<?php echo martfury_header_container_classes(); ?>">
        <div class="row">
			<?php if ( intval( martfury_get_option( 'topbar' ) ) ) : ?>
                <div class="topbar-left topbar-sidebar col-xs-12 col-sm-12 col-md-5 hidden-xs hidden-sm">
					<?php if ( is_active_sidebar( 'topbar-left' ) ) {
						dynamic_sidebar( 'topbar-left' );
					} ?>
                </div>


                <div class="topbar-right topbar-sidebar col-xs-12 col-sm-12 col-md-7 hidden-xs hidden-sm">
					<?php if ( is_active_sidebar( 'topbar-right' ) ) {
						dynamic_sidebar( 'topbar-right' );
					} ?>
                </div>
			<?php endif; ?>

			<?php if ( is_active_sidebar( 'topbar-mobile' ) ) {
				?>
                <div class="topbar-mobile topbar-sidebar col-xs-12 col-sm-12 hidden-lg hidden-md">
					<?php dynamic_sidebar( 'topbar-mobile' ); ?>
                </div>
				<?php
			} ?>

        </div>
    </div>
</div>