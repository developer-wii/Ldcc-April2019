<?php
//remove product from cart
include('../../../../wp-config.php');
require_once( get_template_directory() . '/inc/class-order_product.php');

$prodid = $_POST['prodid'];
$_SESSION['datepicker_value']=$_POST['datepicker_ajax'];
$db = new OrderProduct_Function();
$response = $db->removeproduct($prodid);
echo $response;
?>