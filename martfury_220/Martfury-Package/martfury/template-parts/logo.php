<?php
/**
 * The template part for displaying the main logo on header
 *
 * @package Martfury
 */

$logo = '';
$logo = apply_filters( 'martfury_site_logo', martfury_get_option( 'logo' ) );


if ( ! $logo ) {
	$logo = get_template_directory_uri() . '/images/logo/logo.png';
}

if ( is_page_template( 'template-coming-soon-page.php' ) ) {
	$logo = martfury_get_option( 'coming_soon_logo' );

	if ( ! $logo ) {
		return;
	}

	echo '<div class="container">';
}

$logo_sticky_img = '';
if ( intval( martfury_get_option( 'sticky_header' ) ) && intval( martfury_get_option( 'sticky_header_logo' ) ) ) {
	$logo_sticky = martfury_get_option( 'logo_sticky' );
	if ( ! $logo_sticky ) {
		$logo_sticky = $logo;
	}

	$logo_sticky_img = sprintf( '<img class="sticky-logo" alt="%s" src="%s"/>', esc_attr( get_bloginfo( 'name' ) ), esc_url( $logo_sticky ) );
}

?>
    <div class="logo">
        <a href="<?php echo esc_url( home_url( '/' ) ); ?>">
            <img class="site-logo" alt="<?php echo esc_attr( get_bloginfo( 'name' ) ); ?>" src="<?php echo esc_url( $logo ); ?>"/>
			<?php echo wp_kses_post( $logo_sticky_img ); ?>
        </a>
    </div>
<?php
printf(
	'<%1$s class="site-title"><a href="%2$s" rel="home">%3$s</a></%1$s>',
	is_home() || is_front_page() ? 'h1' : 'p',
	esc_url( home_url( '/' ) ),
	get_bloginfo( 'name' )
);
?>
    <h2 class="site-description"><?php bloginfo( 'description' ); ?></h2>

<?php

if ( is_page_template( 'template-coming-soon-page.php' ) ) {
	echo '</div>';
}

