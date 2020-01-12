<?php

$sliders = martfury_get_option( 'page_header_blog_slider' );

if ( ! $sliders ) {
	return;
}

$output = array();
foreach ( $sliders as $slider ) {
	$bg = '';
	if ( isset( $slider['image'] ) && $slider['image'] ) {
		$image = wp_get_attachment_image_src( $slider['image'], 'full' );

		if ( $image ) {
			$bg = 'style="background-image:url(' . esc_url( $image[0] ) . ')"';
		}
	}

	$title = '';

	if ( isset( $slider['subtitle'] ) && $slider['subtitle'] ) {
		$title .= sprintf( '<span class="subtitle">%s</span>', wp_kses( $slider['subtitle'], wp_kses_allowed_html( 'subtitle' ) ) );
	}

	if ( isset( $slider['title'] ) && $slider['title'] ) {
		$title .= sprintf( '<h3 class="title">%s</h3>', wp_kses( $slider['title'], wp_kses_allowed_html( 'post' ) ) );
	}

	if ( isset( $slider['desc'] ) && $slider['desc'] ) {
		$title .= sprintf( '<p class="desc">%s</p>', wp_kses( $slider['desc'], wp_kses_allowed_html( 'desc' ) ) );
	}

	$item_html = '';
	if ( $title ) {
		$item_html .= sprintf( '<div class="page-header-content">%s</div>', $title );
	}

	if ( isset( $slider['link_url'] ) && $slider['link_url'] ) {
		$item_html = sprintf( '<a class="link" href="%s"></a> %s', esc_url( $slider['link_url'] ), $item_html );
	}

	$output[] = sprintf( '<li class="ph-slider"><div class="featured-img" %s></div>%s</li>', $bg, $item_html );

}

if ( ! $output ) {
	return;
}

$height   = intval( martfury_get_option( 'page_header_blog_height' ) ) . 'px';
$speed    = intval( martfury_get_option( 'page_header_blog_autoplay' ) );
$autoplay = 0;
if ( $speed ) {
	$autoplay = 1;
} else {
	$speed = 500;
}

?>

<div class="page-header page-header-blog page-header-sliders parallax" data-parallax="1" data-speed="<?php echo esc_attr( $speed ); ?>" data-auto="<?php echo esc_attr( $autoplay ); ?>" style="height: <?php echo esc_attr( $height ); ?>">
	<div class="page-header-inner" style="height: <?php echo esc_attr( $height ); ?>">
		<ul>
			<?php
			echo implode( ' ', $output );
			?>
		</ul>
		<div class="slick-arrow-content">
			<div class="container slick-arrow-cont">
				<div class="slick-arrow slick-prev-arrow">
					<i class="icon-chevron-left"></i>
				</div>
				<div class="slick-arrow slick-next-arrow">
					<i class="icon-chevron-right"></i>
				</div>
			</div>
		</div>
	</div>
</div>
