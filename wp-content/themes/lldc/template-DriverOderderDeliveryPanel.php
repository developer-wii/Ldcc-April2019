<?php 
/* Template Name: Driver Order Delivery Panel */

get_header('driver');

$list_heading = "Delivery items list ";

global $current_user;
$user_roles=$current_user->roles[0];

if( $user_roles=='driver' || $user_roles=='administrator' ){}
else{
	?>
<script>
    window.location.href = '<?php echo get_site_url(); ?>/login/';
</script>
<?php 
}
// only administrator and driver can see this. ( page validation )

    $order_btn = true;

    $sap_url = explode("/", $_SERVER['REQUEST_URI']);
    $order_id = array_values(array_slice($sap_url, -2))[0]; // get order id from url 
    if(!is_numeric($order_id)){
        echo '<script>window.location.href = "'.get_site_url().'/driver-order-delivery-panel/";</script>';
    }
    $order_data = wc_get_order($order_id);  // get order data from order id

if($_REQUEST['submit']){
    
    $order_data = wc_get_order($_REQUEST['orders']);
    
    $meta_data = array(
        'cuid'      => $order_data->get_user_id(),
        'orders'    => $_REQUEST['orders'],
        'ak_recipet'=> $_REQUEST['ak_recipet'],
        'd_w_sign'  => $_REQUEST['d_w_sign'],
        'p_name'    => $_REQUEST['FirstName'],
        'image64base' => $_REQUEST['image64base'],
    );
    // order meta data 
    
    $data = serialize($meta_data);
    // add meta data with serialize on database.
    
    if(add_post_meta($_REQUEST['orders'],'_d_order_meta',$data,true)){}
    else{
        update_post_meta($_REQUEST['orders'],'_d_order_meta',$data);
    }
    $order = new WC_Order($_REQUEST['orders']);
    $order->update_status('completed', 'order dilever successfully.'); 
    // order status chage prossing to completed.
    
    $get_data_fields = get_post_meta( $_REQUEST['orders'],'_d_order_meta' );
    $get_data_fields = unserialize($get_data_fields[0]);
    // get post meta and display after unserialized.
    
    $order_btn = false;
    
    // deliverd message
    echo '<H1 align="center"> Order has been delivered successfully. </H1>';
    $list_heading = "Delivered items list ";
    
}   
    $address = '';
?>

<main>
    <section id="serices" class="driveroderder">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <h1 class="text-center current_delivry_head"><?php echo $list_heading; ?></h1>
                    <div class="servicecover_in">
                        <h4> 
                            <p class="pull-left"> Item Name </p>
                            <p class="pull-right"> Qty </p>
                        </h4>
                        <h4>  </h4>
                        <ul>
                            <div class="overflow_scroll">
                                <?php
								        $list_of_products = '';
								        $i = 0;
								        
							            $items = $order_data->get_items();
                                        
                                        foreach ( $items as $item ) {
                                            $product_data = $item->get_data();
                                            $product_name = $item->get_name();
                                            ?>
                                                <li><span><?php echo $product_name; ?></span> <span class="pull-right"><?php echo $product_data['quantity']; ?></span></li>
                                            <?php
                                        }
                                        // get product detail from order data.
								    
								        $address .= $order_data->get_billing_address_1();
                                        $address .= $order_data->get_billing_address_2().', ';
                                        $address .= $order_data->get_billing_city().' ';
                                        $address .= $order_data->get_billing_postcode();
                                        
                                        // create full address
								    ?>
                            </div>
                        </ul>
                    </div>
                    <div class="cover_servi">
                        <div class="row">
                            <div class="col-sm-6">
                                <p class="clinet"><span class="clientcover"><img src="<?php echo esc_url( get_template_directory_uri() ); ?>/images/client.png" alt="">
							<strong>Client name: </strong> <label><?php echo $order_data->get_billing_first_name(); ?></label>
                                    </span></p>
                            </div>
                            <div class="col-sm-6">
                                <p class="clinet"><span class="map_co"><label><img src="<?php echo esc_url( get_template_directory_uri() ); ?>/images/map.png" alt="">
							<strong>Address: </strong><?php echo $address; ?></label></span></p>
                            </div>
                        </div>
                        <div class="row">


                            <div class="col-md-6 sign_pad">

                                <?php  
							            if($_REQUEST['submit'] ){
							                echo '<img src="'.$get_data_fields['image64base'].'" >';    
							            }
							            else{
							                ?>
                                <canvas id="signature-pad" class="signature-pad"></canvas>
                                <button class="sign_pad_clear">Clear</button>
                                <?php
							            }
							        ?>

                            </div>
                            <div class="col-md-6 acknowledge">

                                <p class="check_box"><input type="checkbox" name="ak_recipet_check" class="ak_recipet_check" value="ak_recipet" checked> <span>I acknowledge receipt of items from LDCC</span></p>
                                <p class="tex_tbox"><input type="checkbox" name="d_w_sign_check" class="d_w_sign_check" value="d_w_sign" <?php echo ($_REQUEST[ 'submit'] && $get_data_fields[ 'd_w_sign'] )? "checked" : "" ; ?> > <span>Delivered without Signature</span></p>
                            </div>
                        </div>
                        <form method="post" class="form">
                            <input type="hidden" value="ak_recipet" name="ak_recipet">
                            <input type="hidden" value="" name="d_w_sign">
                            <input type="hidden" value="<?php echo $order_id; ?>" name="orders">
                            <input type="hidden" value="" name="image64base" class="hidden_image64base">
                            <p class="person_name">
                                <?php 
									    
									    if($_REQUEST['submit'] && $get_data_fields['p_name']){
									        echo '<input class="per_name" type="text" name="FirstName" value="'.$get_data_fields['p_name'].'" disabled>';
									    }
									    else{
									        echo '<input class="per_name" type="text" name="FirstName" Placeholder="Person Name"  required>';
									    }
									    
									    ?>
                                <?php 
									if($order_btn){
									    echo '<input class="sub_mit_btn" type="submit" name="submit" value="Submit">';
									}
									else
									{
									    echo '<input class="sub_mit_btn" type="submit" name="submit" value="Delivered" onclick="return false;">';
									}
									?>

                            </p>

                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
</main>

<div class="social">
    <ul class="list">
        <li><a href="#"><img src="<?php echo esc_url( get_template_directory_uri() ); ?>/images/facebook.png" /></a></li>
        <li><a href="#"><img src="<?php echo esc_url( get_template_directory_uri() ); ?>/images/twtr.png" /></a></li>
        <li><a href="#"><img src="<?php echo esc_url( get_template_directory_uri() ); ?>/images/insta.png" /></a></li>
        <li><a href="#"><img src="<?php echo esc_url( get_template_directory_uri() ); ?>/images/you-tube.png" /></a></li>
        <li><a href="#"><img src="<?php echo esc_url( get_template_directory_uri() ); ?>/images/in.png" /></a></li>
    </ul>
</div>


<?php
	   get_footer();
?>
