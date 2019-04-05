
<?php  
//include get_template_directory() . '/js/addproduct.php';
class DifferentUserRegistration_Function{
    public function __construct() {
        // widget actual processes
    }
/*user registration code start*/
public function user_registration(){
	$username=$_POST['user_username'];
	$firstname=$_POST['user_firstname'];
	$user_lastname=$_POST['user_lastname'];
	$user_address1=$_POST['user_address1'];
	$user_email=$_POST['user_email'];
	$user_address2=$_POST['user_address2'];
	$user_contact=$_POST['user_contact'];
	$user_postcode=$_POST['user_postcode'];
	$password=wp_generate_password();
	$hash_pwd=wp_hash_password($password);
	$user_id = username_exists( $username );	
	if ( username_exists( $username ) || email_exists($user_email)){
			?><script>alert('Username/Email already exist,Please try again'); </script> <?php
		}
		else{
	$userdata = array(
			'user_login'  =>  $username,
			'first_name'    =>  $firstname,
			'last_name'   =>  $user_lastname,
			'user_pass'   => $hash_pwd,
			'user_email'   => $user_email,		
		);
	 $userid=wp_insert_user( $userdata ) ; 
	 add_user_meta( $userid, 'billing_address_1', $user_address1, true );
	 add_user_meta( $userid, 'billing_address_2', $user_address2, true );
	 add_user_meta( $userid, 'billing_postcode', $user_postcode, true );
	 add_user_meta( $userid, 'billing_phone', $user_contact, true );

	?><script>alert('You have successfully registered as user,please check your mail for further details.');</script><?php 
	 $siteurl=get_site_url();
		$to = $user_email;
		$subject = 'User registration for London dry cleaning company.';
	
		$body = 'Hello <b>'.$firstname.' '.$user_lastname.'</b>, You have successfully registered to <a href='.$siteurl.'>'.$siteurl.'</a><br/>Thank You.<br/>Please use this password to login into the site : '.$password.' ';
		$headers = array('Content-Type: text/html; charset=UTF-8');
	 
		wp_mail( $to, $subject, $body, $headers );
		}
	
}
/*user registration code end*/

/*student registration code start*/
public function student_registration(){
	$std_uname = $_POST['std_uname'];
	$std_fname= $_POST['std_fname'];
	$std_lname= $_POST['std_lname'];
	$std_email= $_POST['std_email'];
	$std_id= $_POST['std_id'];
	$std_instname= $_POST['std_instname'];
	$std_instemail= $_POST['std_instemail'];
	$std_insttel= $_POST['std_insttel'];
	$std_address1= $_POST['std_address1'];
	$std_address2= $_POST['std_address2'];
	$std_postcode= $_POST['std_postcode'];
	$std_tel= $_POST['std_tel'];
	
	$password=wp_generate_password();
	//$hash_pwd=wp_hash_password($password);
	$user_id = username_exists( $std_uname );	
	if ( username_exists( $std_uname ) || email_exists($std_email)){
			?><script>alert('Username/Email already exist,Please try again'); </script> <?php
		}
		else{
	$userdata = array(
			'user_login'  =>  $std_uname,
			'first_name'    =>  $std_fname,
			'last_name'   =>  $std_lname,
			'user_pass'   => $password,
			'user_email'   => $std_email,
			'role'   => 'student',
		);
	  $studentid = wp_insert_user( $userdata ) ; 
	 add_user_meta( $studentid, 'billing_address_1', $std_address1, true );
	 add_user_meta( $studentid, 'billing_address_2', $std_address2, true );
	 add_user_meta( $studentid, 'billing_postcode', $std_postcode, true );
	 add_user_meta( $studentid, 'billing_phone', $std_tel, true );
	 add_user_meta( $studentid, 'st_institutename', $std_instname, true );
	 add_user_meta( $studentid, 'student_id', $std_id, true );
	 add_user_meta( $studentid, 'st_institute_email', $std_instemail, true );
	 add_user_meta( $studentid, 'st_institutephone', $std_insttel, true );
	?><script>alert('You have successfully registered as student,please check your mail for further details.');</script><?php 
	 $siteurl=get_site_url();
		$to = $std_email;
		$subject = 'Student registration for London dry cleaning company.';
	
		$body = 'Hello <b>'.$std_fname.' '.$std_lname.'</b>, You have successfully registered to <a href='.$siteurl.'>'.$siteurl.'</a> as student.<br/>Thank You.<br/>Please use this password to login into the site : '.$password.' ';
		$headers = array('Content-Type: text/html; charset=UTF-8');
	 
		wp_mail( $to, $subject, $body, $headers );
		}
	
}
/*student registration code end*/

/*Contractor registration code start*/
public function contractor_registration(){
	require_once( ABSPATH . 'wp-admin/includes/image.php' );
	require_once( ABSPATH . 'wp-admin/includes/file.php' );
	require_once( ABSPATH . 'wp-admin/includes/media.php' );
	add_filter( 'upload_dir', 'contractor_custom_upload_dir' );
	$contractor_name = $_POST['contractor_name'];
	$contractor_uname= $_POST['contractor_uname'];
	$contractor_email= $_POST['contractor_email'];
	$contractor_bname= $_POST['contractor_bname'];
	$contractor_tel= $_POST['contractor_tel'];
	$contractor_mob= $_POST['contractor_mob'];
	$contractor_address1= $_POST['contractor_address1'];
	$contractor_address2= $_POST['contractor_address2'];
	$contractor_postcode= $_POST['contractor_postcode'];
	$contractor_file = media_handle_upload('contractor_file', 0);
	$password=wp_generate_password();
	//$hash_pwd=wp_hash_password($password);
	$user_id = username_exists( $contractor_uname );	
	
	remove_filter( 'upload_dir', 'contractor_custom_upload_dir' );
	if (!empty($_FILES['contractor_file']['name'])){
	if(!(is_wp_error($contractor_file))){	
		$imgattachment_contractor= wp_get_attachment_url( $contractor_file ); 				
	}
	}
	else{
		echo $contractor_file->get_error_message();
		$imgattachment_url1='';
	}
	if ( username_exists( $contractor_uname ) || email_exists($contractor_email)){
			?><script>alert('Username/Email already exist,Please try again'); </script> <?php
		}
		else{
	$userdata = array(
			'user_login'  =>  $contractor_uname,
			'first_name'    =>  $contractor_name,
			'user_pass'   => $password,
			'user_email'   => $contractor_email,
			'role'   => 'contractor',
		);
	 $contractorid = wp_insert_user( $userdata );
	 add_user_meta( $contractorid, 'billing_address_1', $contractor_address1, true );
	 add_user_meta( $contractorid, 'billing_address_2', $contractor_address2, true );
	 add_user_meta( $contractorid, 'billing_postcode', $contractor_postcode, true );
	 add_user_meta( $contractorid, 'billing_phone', $contractor_tel, true );
	 add_user_meta( $contractorid, 'contractor_mobile', $contractor_mob, true );
	 add_user_meta( $contractorid, 'contractor_businessname', $contractor_bname, true );
	 add_user_meta( $contractorid, 'contractor_logo', $imgattachment_contractor, true );
	?><script>alert('You have successfully registered as contractor,please check your mail for further details.');</script><?php 
	    $siteurl=get_site_url();
		$to = $contractor_email;
		$subject = 'Contractor registration for London dry cleaning company.';	
		$body = 'Hello <b>'.$contractor_name.'</b>, You have successfully registered to <a href='.$siteurl.'>'.$siteurl.'</a> as Contractor.<br/>Thank You.<br/>Please use this password to login into the site : '.$password.' ';
		$headers = array('Content-Type: text/html; charset=UTF-8');
	 
		wp_mail( $to, $subject, $body, $headers );
		}
	
}
/*Contractor registration code end*/

/*contractor custom logo folder code start*/
public function contractor_logofolder($dir_data){
$custom_dir = 'contractor-logos';
	return [
        'path' => $dir_data[ 'basedir' ] . '/' . $custom_dir,
        'url' => $dir_data[ 'url' ] . '/' . $custom_dir,
        'subdir' => '/' . $custom_dir,
        'basedir' => $dir_data[ 'error' ],
        'error' => $dir_data[ 'error' ],
    ];
}
/*contractor custom logo folder code end*/

/*Custom login code start  */
public function logincustom(){
	$creds = array();
		$creds['user_login'] = sanitize_user($_POST['login_username']);
		$creds['user_password'] = esc_attr($_POST['login_password']);
		$url1=get_site_url();
		$user = wp_signon( $creds, false ); 
		$role = $user->roles[0];
		if( is_wp_error($user)) 
		{ 
			?><script>
				alert('Please enter correct credentials');
			</script> <?php
		
		}
		else{
			if($role=='contractor'){
				?><script>window.location.href='<?php echo $url1; ?>/contractor-history-panel';</script>
			<?php } else if($role=='shop_manager' || $role=='admin' || $role=='administrator'){
				?><script>window.location.href='<?php echo $url1; ?>/shop-control-panel/';</script>
			<?php }
			else {
			//$url=$url1.'/my-login-welcome-page/';
		  ?><script>window.location.href='<?php echo $url1; ?>/login';</script><?php
			}
	  }
}
/*Custom login code end  */
}
