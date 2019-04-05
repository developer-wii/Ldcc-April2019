<div id="sectionModal" class="reveal-modal" data-reveal aria-labelledby="modalTitle" aria-hidden="true" role="dialog">

     <a class="close-reveal-modal" aria-label="Close">&#215;</a>
   
    <section id="loginModelSection" class="login-section">
            <form style="" class="login" method="post">
            <?php do_action( 'woocommerce_login_form_start' ); ?>
            <p>If you have shopped with us before, please enter your details in the boxes below. If you are a new customer please proceed to the Enter Your Details section.</p>

            <label for="username">Username or email <span class="required">*</span></label>
            <input type="text" id="username" name="username" class="input-text">
            <label for="password">Password <span class="required">*</span></label>
            <input type="password" id="password" name="password" class="input-text">
            
            <?php do_action( 'woocommerce_login_form' ); ?>
            <div class="clear"></div>

            <?php wp_nonce_field( 'woocommerce-login' ); ?>
            <input type="submit" value="Login" name="login" class="button">

            <label class="inline" for="rememberme"><input type="checkbox" value="forever" id="rememberme" name="rememberme"> Remember me</label>

            <a href="<?php echo esc_url( wc_lostpassword_url() ); ?>">Lost your password?</a>

            <?php do_action( 'woocommerce_login_form_end' ); ?>
            <div class="clear"></div>
            </form>
    </section>

</div>