<?php 
/* Template Name: Contractor registration page */
get_header();
require_once( get_template_directory() . '/inc/class-registrations.php');
?>
<section class="student_rgstr_form new_contractr">
	<div class="container">
		<div class="formSR">
		<form method="POST" class="clearfix" enctype="multipart/form-data">
			<div class="form_fields clearfix">
				<div class="form_box box01 col-lg-6 col-md-6 col-sm-6 col-xs-12">
					<h2>Create a contractor account</h2>
						<span class="col-lg-6 col-md-6 col-xs-12 col-sm-6 field"><input type="text" placeholder="Name" name="contractor_name"/></span>
						<span class="col-lg-6 col-md-6 col-xs-12 col-sm-6 field"><input type="text" placeholder="User name" name="contractor_uname"/></span>
						<span class="col-lg-6 col-md-6 col-xs-12 col-sm-6 field"><input type="email" placeholder="Email" name="contractor_email"/></span>
						<span class="col-lg-6 col-md-6 col-xs-12 col-sm-6 field"><input type="text" placeholder="Business name" name="contractor_bname"/></span>
						<span class="col-lg-6 col-md-6 col-xs-12 col-sm-6 field"><input type="tel" placeholder="Tel" name="contractor_tel"/></span>
						<span class="col-lg-6 col-md-6 col-xs-12 col-sm-6 field"><input type="tel" placeholder="Mob" name="contractor_mob"/></span>
						<span class="col-lg-6 col-md-6 col-xs-12 col-sm-6 field"><input type="text" placeholder="Addres 1" name="contractor_address1"/></span>
						<span class="col-lg-6 col-md-6 col-xs-12 col-sm-6 field"><input type="text" placeholder="Addres 2" name="contractor_address2"/></span>
						<span class="col-lg-6 col-md-6 col-xs-12 col-sm-6 field"><input type="text" placeholder="Post code" name="contractor_postcode"/></span>
                    <span class="col-lg-6 col-md-6 col-xs-12 col-sm-6 up_button"><span class="upload_btn"><input id="upload" type="file" value="Add logo" name="contractor_file"/></span></span>
				
				</div>

				<div class="buttons col-lg-4 col-md-4 col-sm-4 col-xs-12">
				<span class="cencel"><input type="reset" value="Cancel"/></span>
					<span class="creat_acnt"><input type="submit" value="Create" name="contractor_sbmt"/></span>
				</div>	
			</div>
			</form>
		</div>
	</div>
</section>
<?php
if(isset($_POST['contractor_sbmt'])){
	$db = new DifferentUserRegistration_Function();
	echo $response = $db->contractor_registration();
}
?>
<?php get_footer(); ?>