<?php 
/* Template Name: Main Admin panel page */
get_header();
//require_once( get_template_directory() . '/inc/class-registrations.php');
?>
<?php if ( is_super_admin() ) { ?>
<section class="admin-main-section">
	<div class="container">
		 <div class="admin_content">
			<div class="admin_sec">
				<div class="actionT">
					<ul class="items clearfix">
						<!--li><a href="#"><img src="<?php //echo get_template_directory_uri(); ?>/images/edit.png" /></a></li-->
						<li><a href="#user_view"><img src="<?php echo get_template_directory_uri(); ?>/images/edit-pass.png" /></a></li>
						<!--li><a href="#"><img src="<?php// echo get_template_directory_uri(); ?>/images/delete01.png" /></a></li-->
					</ul>
				</div>
				<div class="admin-table">				
					<table class="table" width="100%">
						<tr>
							<th>Admin name</th>
							<th>User name</th>
							<th>Email</th>
							<th>Password</th>
							<th>Admin type</th>
							<th>Action</th>
							
						</tr>
						<?php 
							$users = array_merge( get_users(array('role'=>'administrator','exclude'=>1)), get_users('role=admin'),get_users('role=shop_manager') );
							//$users=get_users($args); 
							foreach($users as $user){
								$userid=$user->ID;
						?>
						<tr>
							<td><?php echo $user->display_name ; ?></td>
							<td><?php echo $user->user_login ; ?></td>
							<td><?php echo $user->user_email ; ?></td>
							<td>xxxxxxxxxx</td>
							<td><?php echo $user->roles[0]; ?></td>
							<td><span><a href="#user_view" id="viewuserajax" onclick="viewuserajax(<?php echo $userid; ?>,'<?php echo $user->roles[0]; ?>');"><img src="<?php echo get_template_directory_uri(); ?>/images/edit.png" /></a></span><span><a href="#" onclick="delete_user(<?php echo $userid; ?>)" id="delete<?php echo $userid; ?>"><img src="<?php echo get_template_directory_uri(); ?>/images/delete01.png" /></a></span></td>
						</tr>
							<?php } ?>
					</table>
				</div>
			</div>
			<div class="add_new_admin">
			<form method="POST">
				<h3>Add new admin</h3>
				<div class="new_admin_form">
					<span class="field"><input type="text" placeholder="First Name" name="admin_fname"/></span>
					<span class="field"><input type="text" placeholder="Last Name" name="admin_lname"/></span>
					<span class="field"><input type="text" placeholder="User name (email)" name="admin_uname"/></span>
					<span class="field"><input type="text" placeholder="Email" name="admin_email"/></span>
					<span class="field"><input type="password" placeholder="Password" name="admin_password"/></span>
					<span class="field"><select name="admin_role"><option value="">Select</option><option value="administrator">Type ( Super admin)</option><option value="admin">Type ( Admin)</option><option value="shop_manager">Type ( Shop manager )</option></select></span>
				</div>
				<div class="action_button">
					<span class="cencel_user"><input type="reset" value="Cancel"/></span>
					<span class="create_user"><input type="submit" name="add_admin" value="Create user"/></span>
				</div>
				</form>
			</div>
			<div class="adit_admin clearfix" >
				<div class="change-pass col-lg-4 col-md-4 col-sm-4 col-xs-12">
				<form method="POST">
					<h3>Change password</h3>
					<input type="password" name="admin_newpwd" placeholder="New password"/>
					<span class="change_btn"><input type="submit" value="Change password" name="admin_changepwd"/></span>
					</form>
				</div>
				<div class="admin_edit_table col-lg-8 col-md-8 col-sm-8 col-xs-12" id="user_view" style="display:none;" >
					<h3>Edit</h3>
					
			</div>
		 </div>
	</div>
	
</section>
<?php } else {
echo '<div class="text-center msg-permission">You do not have permissions to access this page.</div>';
}?>
<?php 
if(isset($_POST['add_admin'])){ 
	echo $admin_fname= $_POST['admin_fname'];
	$admin_lname= $_POST['admin_lname'];
	$admin_uname= $_POST['admin_uname'];
	$admin_email= $_POST['admin_email'];
	$admin_password= $_POST['admin_password'];
	echo $admin_role= $_POST['admin_role'];
	$userdata = array(
			'user_login'  =>  $admin_uname,
			'first_name'    =>  $admin_fname,
			'last_name'   =>  $admin_lname,
			'user_pass'   => $admin_password,
			'user_email'   => $admin_email,
			'role'   => $admin_role,
		);
	$userid = wp_insert_user( $userdata ) ; 
	$siteurl=get_site_url();
	?>
	<script>alert('New user successfully added as <?php echo $admin_role; ?>');</script><?php 
		$to = $admin_email;
		$subject = 'Registration for London dry cleaning company.';	
		$body = 'Hello <b>'.$admin_fname.' '.$admin_lname.'</b>, You have successfully registered to <a href='.$siteurl.' as '.$admin_role.'>'.$siteurl.'</a><br/>Thank You.<br/>Please use this credentials to login into the site : <br/> username: '.$admin_uname.' Password: '.$admin_password.' ';
		$headers = array('Content-Type: text/html; charset=UTF-8');
	 
		wp_mail( $to, $subject, $body, $headers );
} 


if(isset($_POST['save_changesadmin'])){ 
	$edit_displayname=$_POST['edit_displayname'];
	$edit_username=$_POST['edit_username'];
	$edit_email=$_POST['edit_emailname'];
	$edit_password=$_POST['edit_password'];
	$admin_roleedit=$_POST['admin_roleedit'];
	$userid = $_POST['id_user'];
	$user=get_user_by( 'id', $userid) ;
	$user->set_role($admin_roleedit);
	$user_id = wp_update_user( array( 'ID' => $userid, 'display_name' => $edit_displayname , 'user_login' => $edit_username, 'user_email' => $edit_email, 'user_pass' => $edit_password) );
	if ( is_wp_error( $user_id ) ) {
		 ?><script>alert('Please try again');</script><?php
	} else {
		?><script>alert('User updated successfully');</script><?php
	}	
}
	
if(isset($_POST['admin_changepwd'])){
	$password=$_POST['admin_newpwd'];
	$user_id= wp_set_password( $password, 1 );
	if ( is_wp_error( $user_id ) ) {
		 ?><script>alert('Please try again');</script><?php
	} else {
		?><script>alert('Your password updated successfully');</script><?php
	}	
}	

?>


<script>
function viewuserajax(id,role){ 
	jQuery('#user_view').show();
 	jQuery.ajax({		
			type: 'POST',		
            url: '<?php  echo admin_url('admin-ajax.php'); ?>',
			data:{
			id: id,
			role: role,
			action: 'view_user',	
			},
			success: function(data)
			{			 
				$('#user_view').html(data);								
			}
		
	});
	 
}

function discard_view_adminpanel(){
	jQuery('#user_view').hide();
}

function delete_user(id){
	if (confirm("Are you sure you want to delete this user?")){
		   jQuery.ajax({		
			type: 'POST',		
            url: '<?php  echo admin_url('admin-ajax.php'); ?>',
			data:{
			userid: id,
			action: 'deleteuser',	
			},
			success: function(data)
			{	
				alert('user#'+id+' successfully deleted.');
				window.location.reload();
				//$('.view_order').html(data);								
			}
		
	});
	}
}
</script>
<?php get_footer(); ?>