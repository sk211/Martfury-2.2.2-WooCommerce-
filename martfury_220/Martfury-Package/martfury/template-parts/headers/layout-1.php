<?php
$css_header_menu = 'col-md-9 col-sm-9';
$show_department = true;
$extras          = martfury_menu_extras();
if ( empty( $extras ) || ! in_array( 'department', $extras ) ) {
	$show_department = false;
	$css_header_menu = 'col-md-12 col-sm-12';
}
?>

<div class="header-main-wapper">
	<div class="header-main">
		<div class="<?php echo martfury_header_container_classes(); ?>">
			<div class="row header-row">
				<div class="header-logo col-lg-3 col-md-3 col-sm-6 col-xs-6">
					<div class="d-logo">
						<?php get_template_part( 'template-parts/logo' ); ?>
					</div>
					<?php if ( intval( martfury_get_option( 'sticky_header' ) ) ) : ?>
						<div class="d-department">
							<?php martfury_extra_department( false ); ?>
						</div>
					<?php endif; ?>
				</div>
				<div class="header-extras col-lg-9 col-md-9 col-sm-6 col-xs-6">
					<?php martfury_extra_search(); ?>
					<ul class="extras-menu">
						<?php
						martfury_extra_hotline();
						martfury_extra_compare();
						martfury_extra_wislist();
						martfury_extra_cart();
						martfury_extra_account();
						?>
					</ul>
				</div>
			</div>
		</div>
	</div>
</div>
<div class="main-menu hidden-xs hidden-sm">
	<div class="<?php echo martfury_header_container_classes(); ?>">
		<div class="row header-row">
			<?php if ( $show_department ) : ?>
				<div class="col-md-3 col-sm-3 i-product-cats mr-extra-department">
					<?php martfury_extra_department( true ); ?>
				</div>
			<?php endif; ?>
			<div class="<?php echo esc_attr( $css_header_menu ); ?> mr-header-menu">
				<div class="col-header-menu">
					<?php martfury_header_menu(); ?>
					<?php martfury_header_bar(); ?>
				</div>
			</div>
		</div>
	</div>
</div>
<div class="mobile-menu hidden-lg hidden-md">
	<div class="container">
		<div class="mobile-menu-row">
			<a class="mf-toggle-menu" id="mf-toggle-menu" href="#">
				<i class="icon-menu"></i>
			</a>
			<?php martfury_extra_search( false ); ?>
		</div>
	</div>
</div>

