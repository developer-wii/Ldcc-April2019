<?php 
/* Template Name: Student registration page */
get_header();
require_once( get_template_directory() . '/inc/class-registrations.php');
?>
<section class="student_rgstr_form">
	<div class="container">
		<div class="formSR">
		<form method="POST" class="clearfix">
			<div class="form_fields clearfix">
				<div class="form_box box01 col-lg-6 col-md-6 col-sm-6 col-xs-12">
					<h2>Create a student account</h2>
						<span class="col-lg-6 col-md-6 col-xs-12 col-sm-6 field"><input type="text" placeholder="User Name*" name="std_uname" required/></span>
						<span class="col-lg-6 col-md-6 col-xs-12 col-sm-6 field"><input type="text" placeholder="Your Email*" name="std_email" required/></span>
						<span class="col-lg-6 col-md-6 col-xs-12 col-sm-6 field"><input type="text" placeholder="First Name*" name="std_fname" required/></span>
						<span class="col-lg-6 col-md-6 col-xs-12 col-sm-6 field"><input type="text" placeholder="Last Name*" name="std_lname" required/></span>
						<span class="col-lg-6 col-md-6 col-xs-12 col-sm-6 field"><input type="text" placeholder="Student Id*" name="std_id" required/></span>
						<span class="col-lg-6 col-md-6 col-xs-12 col-sm-6 field"><input type="text" placeholder="Institute name*" name="std_instname" required/></span>
						<span class="col-lg-6 col-md-6 col-xs-12 col-sm-6 field"><input type="email" placeholder="Institute email*" name="std_instemail" required/></span>
						<span class="col-lg-6 col-md-6 col-xs-12 col-sm-6 field"><input type="tel" placeholder="Institute contact number*" name="std_insttel" required/></span>
				
				</div>
				<div class="form_box box02 col-lg-6 col-md-6 col-sm-6 col-xs-12">
					<h2>Address</h2>
						<span class="col-lg-6 col-md-6 col-xs-12 col-sm-6 field"><input type="text" placeholder="Address 1*" name="std_address1" required/></span>
						<span class="col-lg-6 col-md-6 col-xs-12 col-sm-6 field"><input type="text" placeholder="Address 2*" name="std_address2"required/></span>
						<span class="col-lg-6 col-md-6 col-xs-12 col-sm-6 field"><input type="text" placeholder="Postal Code*" name="std_postcode" required/></span>
						<span class="col-lg-6 col-md-6 col-xs-12 col-sm-6 field"><input type="tel" placeholder="Tel*" name="std_tel" required/></span>
					
				</div>	
<span class="creat_acnt"><input type="submit" value="Create my account" name="student_reg"/></span>				
			</div>
			</form>
		</div>
	</div>
</section>
<?php
if(isset($_POST['student_reg'])){
	$db = new DifferentUserRegistration_Function();
	echo $response = $db->student_registration();
}
?>
<?php get_footer(); ?>