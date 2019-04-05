<?php 
//add product to cart
include('../../../../wp-config.php');
require_once( get_template_directory() . '/inc/class-order_product.php');
// get variable values

$prodid = $_POST['prodid'];
$_SESSION['datepicker_value']=$_POST['datepicker_ajax'];
$db = new OrderProduct_Function();
$response = $db->add_product_to_cart($prodid);

echo $response;
?>