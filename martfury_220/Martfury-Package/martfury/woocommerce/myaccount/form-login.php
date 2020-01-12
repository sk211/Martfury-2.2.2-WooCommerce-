<?php
/**
 * Login Form
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/myaccount/form-login.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce/Templates
 * @version 3.6.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

$login_actived = true;
if ( ! empty( $_POST ) && isset( $_POST['woocommerce-register-nonce'] ) && ! empty( $_POST['woocommerce-register-nonce'] ) ) {
	$login_actived = false;
}

$login_form_layout = martfury_get_option( 'login_register_layout' );
$login_layout      = 'martfury-login-' . $login_form_layout;
if ( $login_form_layout == 'promotion' ) {
	$login_layout .= ' martfury-login-tabs';
}

?>

<?php do_action( 'woocommerce_before_customer_login_form' ); ?>

<?php
$login_class     = $login_actived ? 'active' : '';
$register_class  = ! $login_actived ? 'active' : '';
$col_login_class = 'col-md-6 col-sm-6 col-md-offset-3 col-sm-offset-3';
if ( $login_form_layout == 'promotion' ) {
	$col_login_class = 'col-md-5 col-sm-12';
}
?>

<div class="customer-login">

    <div class="row">

        <div class="<?php echo esc_attr( $col_login_class ); ?> col-login">
            <div class="<?php echo esc_attr( $login_layout ); ?>">
                <ul class="tabs-nav">
                    <li class="active"><a href="#"
                                          class="<?php echo esc_attr( $login_class ); ?>"><?php esc_html_e( 'Log in', 'martfury' ); ?></a>
                    </li>
					<?php if ( get_option( 'woocommerce_enable_myaccount_registration' ) === 'yes' ) : ?>
                        <li><a class="<?php echo esc_attr( $register_class ); ?>"
                               href="#"><?php esc_html_e( 'Register', 'martfury' ); ?></a></li>
					<?php endif; ?>
                </ul>
                <div class="tabs-content">

                    <div class="tabs-panel <?php echo esc_attr( $login_class ); ?>">

                        <h2><?php esc_html_e( 'Log In Your Account', 'martfury' ); ?></h2>

                        <form class="woocommerce-form woocommerce-form-login login" method="post">

							<?php do_action( 'woocommerce_login_form_start' ); ?>

                            <p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
                                <input type="text" class="woocommerce-Input woocommerce-Input--text input-text" required
                                       placeholder="<?php esc_html_e( 'Username or email address', 'martfury' ); ?>"
                                       name="username" id="username" autocomplete="username"
                                       value="<?php echo ( ! empty( $_POST['username'] ) ) ? esc_attr( $_POST['username'] ) : ''; ?>"/>
                            </p>

                            <p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide form-row-password">
                                <input class="woocommerce-Input woocommerce-Input--text input-text" required
                                       placeholder="<?php esc_html_e( 'Password', 'martfury' ); ?>" type="password" autocomplete="current-password"
                                       name="password" id="password"/>
                                <a class="lost-password"
                                   href="<?php echo esc_url( wp_lostpassword_url() ); ?>"><?php esc_html_e( 'Forgot?', 'martfury' ); ?></a>
                            </p>

							<?php do_action( 'woocommerce_login_form' ); ?>

                            <p class="form-row">
                                <label class="woocommerce-form__label woocommerce-form__label-for-checkbox woocommerce-form-login__rememberme">
                                    <input class="woocommerce-form__input woocommerce-form__input-checkbox"
                                           name="rememberme" type="checkbox" id="rememberme" value="forever"/>
                                    <span><?php esc_html_e( 'Remember me', 'martfury' ); ?></span>
                                </label>
								<?php wp_nonce_field( 'woocommerce-login', 'woocommerce-login-nonce' ); ?>
                                <button type="submit" class="woocommerce-Button button" name="login"
                                        value="<?php esc_attr_e( 'Log in', 'martfury' ); ?>"><?php esc_html_e( 'Log in', 'martfury' ); ?></button>
                            </p>

							<?php do_action( 'woocommerce_login_form_end' ); ?>

                        </form>
                    </div>

					<?php if ( get_option( 'woocommerce_enable_myaccount_registration' ) === 'yes' ) : ?>

                        <div class="tabs-panel <?php echo esc_attr( $register_class ); ?>">

                            <h2><?php esc_html_e( 'Register An Account', 'martfury' ); ?></h2>

                            <form method="post" class="register woocommerce-form woocommerce-form-register">

								<?php do_action( 'woocommerce_register_form_start' ); ?>

								<?php if ( 'no' === get_option( 'woocommerce_registration_generate_username' ) ) : ?>

                                    <p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
                                        <input type="text" required
                                               class="woocommerce-Input woocommerce-Input--text input-text"
                                               placeholder="<?php esc_html_e( 'Username', 'martfury' ); ?>"
                                               name="username" id="reg_username" autocomplete="username"
                                               value="<?php echo ( ! empty( $_POST['username'] ) ) ? esc_attr( $_POST['username'] ) : ''; ?>"/>
                                    </p>

								<?php endif; ?>

                                <p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
                                    <input type="email" required
                                           class="woocommerce-Input woocommerce-Input--text input-text"
                                           placeholder="<?php esc_html_e( 'Email address', 'martfury' ); ?>"
                                           name="email" id="reg_email" autocomplete="email"
                                           value="<?php echo ( ! empty( $_POST['email'] ) ) ? esc_attr( $_POST['email'] ) : ''; ?>"/>
                                </p>

								<?php if ( 'no' === get_option( 'woocommerce_registration_generate_password' ) ) : ?>

                                    <p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
                                        <input type="password" required
                                               placeholder="<?php esc_html_e( 'Password', 'martfury' ); ?>"
                                               class="woocommerce-Input woocommerce-Input--text input-text" autocomplete="new-password"
                                               name="password" id="reg_password"/>
                                    </p>

								<?php else : ?>

                                    <p><?php esc_html_e( 'A password will be sent to your email address.', 'martfury' ); ?></p>

								<?php endif; ?>

								<?php do_action( 'woocommerce_register_form' ); ?>

                                <p class="woocommerce-FormRow form-row">
									<?php wp_nonce_field( 'woocommerce-register', 'woocommerce-register-nonce' ); ?>
                                    <button type="submit" class="woocommerce-Button button" name="register"
                                            value="<?php esc_attr_e( 'Register', 'martfury' ); ?>"><?php esc_html_e( 'Register', 'martfury' ); ?></button>
                                </p>

								<?php do_action( 'woocommerce_register_form_end' ); ?>

                            </form>

                        </div>

					<?php endif; ?>

                </div>
            </div>
        </div>

		<?php do_action( 'martfury_after_login_form' ); ?>

    </div>
</div>

<?php do_action( 'woocommerce_after_customer_login_form' ); ?>
