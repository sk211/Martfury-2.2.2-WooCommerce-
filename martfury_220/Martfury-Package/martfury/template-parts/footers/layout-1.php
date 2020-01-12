<?php
$footer_class = 'footer-' . martfury_get_option('footer_skin');
?>
<nav class="footer-layout footer-layout-1 <?php echo esc_attr($footer_class); ?>">
	<?php martfury_footer_newsletter(); ?>
	<div class="<?php echo martfury_footer_container_classes(); ?>">
		<div class="footer-content">
			<?php
			martfury_footer_info();
			martfury_footer_widgets();
			martfury_footer_links();
			?>
		</div>
		<div class="footer-bottom">
			<div class="row footer-row">
				<div class="col-footer-copyright col-lg-6 col-md-12 col-sm-12 col-xs-12">
					<?php martfury_footer_copyright(); ?>
				</div>
				<div class="col-footer-payments col-lg-6 col-md-12 col-sm-12 col-xs-12">
					<?php martfury_footer_payments(); ?>
				</div>
			</div>
		</div>
	</div>
</nav>