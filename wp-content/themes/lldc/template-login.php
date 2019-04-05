<?php 
/* Template Name: Login */

get_header();
?>
<?php
global $current_user;
$user_roles=$current_user->roles[0];
if($user_roles=='shop_manager' || $user_roles=='admin' || $user_roles=='administrator'){
	?><script>window.location.href='<?php echo get_site_url(); ?>/shop-control-panel/';</script><?php
}
else if($user_roles=='contractor'){
	?><script>window.location.href='<?php echo get_site_url(); ?>/contractor-history-panel';</script>
<?php }
else if($user_roles=='subscriber' || $user_roles=='student'){
	?><script>window.location.href='<?php echo get_site_url(); ?>/order';</script>
<?php }
else if($user_roles=='driver'){
	?><script>window.location.href='<?php echo get_site_url(); ?>/driver-order-delivery-panel/';</script>
<?php }
?>
<section class="login-page">
<?php
if(is_user_logged_in()){
	echo '<h3 id="login_page_text" class="text-center">You are already logged in.</h3>';
}else{
?>
			<div class="container">
				<div class="login_form">
					<figure class="user-icon"><img src="<?php bloginfo('template_url'); ?>/images/user-icon.png" /></figure>
					<h2>Log In</h2>
					<form method="POST">
						<div class="fields">
						<span class="field"><input type="text" placeholder="User Name" name="login_username"/></span>
						<span class="field"><input type="password" placeholder="Password" password="login_password" name="login_password"/></span>
						</div>
						<div class="frgt-pass clearfix">
							<p><input type="checkbox" />Keep me logged in</p>
							<p class="forgt"><a href="<?php echo get_site_url(); ?>/my-account/lost-password/">Forgot password?</a></p>
						</div>
						<input type="submit" value="Login" name="custom_login"/>
					</form>
				</div>
				<p class="creat_link">Don’t have an account? <a href="<?php echo get_site_url(); ?>/user-registration">Create your account</a></p>
			</div>
		</section>
<?php } ?>		

<?php		
get_footer();