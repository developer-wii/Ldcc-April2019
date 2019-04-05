<?php
/**
 * The template for displaying the footer
 *
 * Contains the closing of the #content div and all content after.
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package LLDC
 */

?>

	</div>
	<!-- #content -->

	<footer class="footer_btm">
		<div class="container">
			<!--p>© 2018 LDCC, Inc. All Rights Reserved.</p-->
			<p>&copy;
				<?php echo date("Y"); ?>
				<?php dynamic_sidebar('footer-copyright'); ?>
			</p>
		</div>
	</footer>
	<div class="social">
		<ul class="list">

			<?php
				if ( is_active_sidebar( 'sidebar-3' ) ) :
				 dynamic_sidebar( 'sidebar-3' );
				 endif;
				 ?>
		</ul>
	</div>
	</div>
	<!-- #page -->

	<?php wp_footer(); ?>


	<link rel="stylesheet" href="<?php echo esc_url( get_template_directory_uri() ); ?>/css/jquery-ui.css">
	<script src="<?php echo esc_url( get_template_directory_uri() ); ?>/js/jquery-1.12.4.js"></script>
	<script src="<?php echo esc_url( get_template_directory_uri() ); ?>/js/jquery-ui.js"></script>
	<script type="text/javascript" src="<?php echo esc_url( get_template_directory_uri() ); ?>/js/bootstrap.js"></script>
	<!--script type="text/javascript" src="<?php //echo esc_url( get_template_directory_uri() ); ?>/js/jQuery.scrollSpeed.js"></script-->
	<?php
	if(is_page(160)){
		?>
	<script src="https://cdn.jsdelivr.net/npm/signature_pad@2.3.2/dist/signature_pad.min.js"></script>
	<script src="<?php echo esc_url( get_template_directory_uri() ); ?>/js/signature_pad_custom.js"></script>
	<?php
	}
  ?>

		<script type="text/javascript" src="<?php echo esc_url( get_template_directory_uri() ); ?>/js/customjs.js"></script>




		<script>
			$(".checkboxorder").change(function() {
			if (this.checked) {
			$("label[for='" + this.id + "']").text("Remove");
			} else {
			$("label[for='" + this.id + "']").text("Add To Cart");
			}
			});

			//$(".checkboxorder").on("change", function() {
			//$("label[for='" + this.id + "']").text(this.checked ? "Remove From Cart" : "Add To Cart");
			//});

		</script>

		<script>
			$(function() {
				$("#datepicker").datepicker({
					minDate: 0
				});
			});

		</script>
		<script>
			jQuery('div.woocommerce').on('change', '.qty', function() {
				jQuery("[name='update_cart']").prop("disabled", false);
				e.preventDefault();
				jQuery("[name='update_cart']").trigger("click");

			});

		</script>


		</body>

		</html>
