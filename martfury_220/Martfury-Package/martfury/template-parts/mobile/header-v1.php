<div class="header-mobile-v1">
    <div class="container">
        <div class="header-main header-row">
            <div class="header-logo">
				<?php get_template_part( 'template-parts/logo' ); ?>
            </div>
            <div class="header-extras">
                <ul class="extras-menu">
					<?php
					martfury_extra_compare();
					martfury_extra_wislist();
					martfury_extra_cart();
					martfury_extra_account();
					?>
                </ul>
            </div>
        </div>
        <div class="mobile-menu">
	        <?php martfury_extra_category(); ?>
			<?php martfury_extra_search( false ); ?>
        </div>
    </div>
</div>




