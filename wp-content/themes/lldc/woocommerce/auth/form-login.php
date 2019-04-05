<?php
/**
 * Auth form login
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/auth/form-login.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce/Templates/Auth
 * @version 3.4.0
 */

defined( 'ABSPATH' ) || exit;

//do_action( 'woocommerce_auth_page_header' ); ?>

<?php if(is_user_logged_in())
{ echo "You are loggded in ";
?>
<script type="text/javascript">
    window.location = "<?php  echo bloginfo('url'); ?>"

</script>
<?php
}
else
{
    ?>
<section class="login-page">
			<div class="container">
				<div class="login_form">
					<figure class="user-icon"><img src="<?php bloginfo('template_url'); ?>/images/user-icon.png" /></figure>
					<h2>Log In</h2>
<form method="post" class="wc-auth-login">
	<div class="fields">
	<span class="field">
		<input type="text" class="input-text" placeholder="User Name" name="username" id="username" value="<?php echo ( ! empty( $_POST['username'] ) ) ? esc_attr( $_POST['username'] ) : ''; ?>" /><?php //@codingStandardsIgnoreLine ?>
	</span>
	<span class="field">
		<input class="input-text" type="password" name="password" id="password" placeholder="Password"/>
	</span>
	
</div>
<div class="frgt-pass clearfix">
							<p>
							<input class="woocommerce-form__input woocommerce-form__input-checkbox" name="rememberme" type="checkbox" id="rememberme" value="forever" />Keep me logged in</p>
							<p class="forgt"><a href="<?php echo esc_url( wp_lostpassword_url() ); ?>">Forgot password?</a></p>
						</div>
<p class="wc-auth-actions">
		<?php wp_nonce_field( 'woocommerce-login', 'woocommerce-login-nonce' ); ?>
		<button type="submit" class="button button-large button-primary wc-auth-login-button loginformcus" name="login" value="<?php esc_attr_e( 'Login', 'woocommerce' ); ?>"><?php esc_html_e( 'Login', 'woocommerce' ); ?></button>
		<input type="hidden" name="redirect" value="<?php echo esc_url( $redirect_url ); ?>" />
	</p>
</form>
</div>
</section>
<?php  } //do_action( 'woocommerce_auth_page_footer' ); ?>
