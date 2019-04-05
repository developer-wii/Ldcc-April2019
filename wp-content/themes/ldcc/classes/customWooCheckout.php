<?php

/*
 * Custom WooCommerce Class
 */

/**
 * Description of customWooCheckout
 *
 * @author Andrew
 */

if( class_exists('WC_Checkout') )
{
    require_once TEMPLATEPATH .'/classes/customWooCart.php';
    
    class customWooCheckout extends WC_Checkout
    {
        var $countries;
        var $postedsession;
        var $cust_cart;
        
        public function __construct()
        {
            //Construct our parent
            //parent::instance();
            //parent::__construct();
             do_action( 'woocommerce_init' );
             
            $this->countries = new WC_Countries();
            $this->cust_cart = new customWooCart();

            add_action( 'wp_loaded', array( $this, 'init' ) );

        }
        
        public function init()
        {
            
        }
        
        /*
         * SESSION KEYS 
        array(4) { ["collection-picker"]=> string(10) "2015-06-12" ["drop-time"]=> string(8) "12pm-1pm" ["collect-time"]=> string(7) "5pm-6pm" ["dropoff-picker"]=> string(10) "2015-06-25" } 
         */
        public function setSessionValue($sessKey, $sessValue)
        {
            if($sessKey != null || !empty($sessKey) || $sessValue != null || !empty($sessValue) || isset($_SESSION[$sessKey]))
            {
                if($this->checkSessionExists($sessKey) == true)
                {
                    return $_SESSION[$sessKey] = $sessValue;
                }
                else
                {
                    return $_SESSION[$sessKey] = $sessValue;
                }
            }
            return null;
        }
        
        public function getSessionValue($sessKey)
        {
            if(!empty($sessKey))
            {
                $thisSessionByKey = $_SESSION[$sessKey];
                if(!empty($thisSessionByKey))
                {
                    return $thisSessionByKey;
                }
                return null;
            }
            return null;
        }
        
        private function checkSessionExists($sessKey)
        {
            if(isset($_SESSION[$sessKey]) && !empty($_SESSION[$sessKey]))
            {
                $_SESSION[$sessKey] = '';
                unset($_SESSION[$sessKey]);
                return true;
            }
            else
            {
                return false;
            }
        }
        
        public function retriveSessionData($typeCalendar)
        {
            switch($typeCalendar)
            {
                case 'collection-picker':
                    
                    $returnData = '';
                    $collectDate = $this->getSessionValue('collection-picker');
                    $collectTime = $this->getSessionValue('collect-time');       
                    $newDatesCollect = new DateTime($collectDate);
                    $returnData .= '<p class="para-date">Collection Date: '.$newDatesCollect->format('jS F Y').'</p>';
                    $returnData .= '<p class="para-time">Collection Time: '.$collectTime.'</p>';
                    $returnData .= '<input type="hidden" name="'.$typeCalendar.'-section" value="'.$newDatesCollect->format('jS F Y').'" />';
                    $returnData .= '<input type="hidden" name="'.$typeCalendar.'-times" value="'.$chosenTime.'" />';
                    
                    return $returnData;
                break;
                case 'dropoff-picker':
                    
                    $returnData = '';
                    $dropoffDate = $this->getSessionValue('dropoff-picker');
                    $dropoffTime = $this->getSessionValue('drop-time'); 
                    $newDatesDrop = new DateTime($dropoffDate);
                    $returnData .= '<p class="para-date">Dropoff Date: '.$newDatesDrop->format('jS F Y').'</p>';
                    $returnData .= '<p class="para-time">Dropoff Time: '.$dropoffTime.'</p>';
                    $returnData .= '<input type="hidden" name="'.$typeCalendar.'-section" value="'.$newDatesDrop->format('jS F Y').'" />';
                    $returnData .= '<input type="hidden" name="'.$typeCalendar.'-times" value="'.$dropoffTime.'" />';
                    
                    return $returnData;
                break;
            }
            
            if(!empty($returnData))
            
            return null;
        }
        
        public function assignOurCheckoutVars($checkoutArrys)
        {
           if(is_array($checkoutArrys) && count($checkoutArrys))
           {
               //$this->unsetAssignCheckoutVars();
               foreach($checkoutArrys as $cArrKey => $cArrVal)
               {
                   $this->posted[$cArrKey] = $cArrVal;
                   //Assign sessions
                   //$sessionPosted = $_SESSION[$cArrKey] = $cArrVal;
               }

              return true; 
           }
            return false;
        }
        
        public function assignOurCheckoutSessionVars($checkoutArrys)
        {
           if(is_array($checkoutArrys) && count($checkoutArrys))
           {
               $this->unsetAssignCheckoutVars($checkoutArrys);
               foreach($checkoutArrys as $cArrKey => $cArrVal)
               {
                   $sessionPosted = $_SESSION['sessposted'][$cArrKey] = $cArrVal;
               }
              return true; 
           }
            return false;
        }
        
        
        public function unsetAssignCheckoutVars($checkoutArrys)
        {
            if(isset($checkoutArrys))
            {
                foreach($checkoutArrys as $sesKey => $sessVal)
                {
                    unset($_SESSION['sessposted'][$sesKey]);
                }
                
            }
        }
        
        public function removeSessionbyKey($sessKey)
        {
            if(isset($_SESSION[$sessKey]) && !empty($_SESSION[$sessKey]))
            {
                $_SESSION[$sessKey] = '';
                unset($_SESSION[$sessKey]);
                return true;
            }
            else
            {
                return false;
            }
        }

        public function getPostedDataFromCheckout()
        {
            if(count($this->posted))
            {
                return $this->posted;
            }
            else if(count($_SESSION['sessposted']))
            {
                return $_SESSION['sessposted'];
            }
            else
            {
                return null;
            }
        }
        
        public function processUserCheckout()
        {
            $this->doProcessCheckout();
        }

        public function doProcessCheckout()
        {
            do_action('wp_loaded');
            
            try {   
                //Try checkout
                
                @set_time_limit(0);

                if ( 0 === sizeof( $this->cust_cart ) )
                {
                        throw new Exception( sprintf( __( 'Sorry, your session has expired. <a href="%s" class="wc-backward">Return to homepage</a>', 'woocommerce' ), home_url() ) );
                }
                
                do_action( 'woocommerce_checkout_process' );
                
                
                //We've needed to change this to cURL due to the lack of loading time from Chrome.
                /*
                $urlCheckout = get_bloginfo('url') .'/checkout/';
                
                $newFields = array();
                if(!is_null($this->posted))
                {
                    foreach ($this->posted as $k1 => $v1)
                    {
                         $newFields[$k1] = urlencode($v1);
                    }

                    foreach ($this->posted as $k2 => $v2)
                    {
                        $fields_string .= $k2.'='.urlencode($v2).'&'; 
                    }
                    rtrim($fields_string, '&');

                    $ch = curl_init();

                    //set the url, number of POST vars, POST data
                    curl_setopt($ch,CURLOPT_URL, $urlCheckout);
                    //curl_setopt($ch,CURLOPT_POST, count($newFields));
                    curl_setopt($ch,CURLOPT_POSTFIELDS, $fields_string);

                    //execute post
                    $result = curl_exec($ch);

                    //close connection
                    curl_close($ch);
                }
                
                //header('Location: '.$urlCheckout.'');  
                wp_redirect(get_permalink(6));
                exit;  
                
                wp_redirect($urlCheckout);
                exit;
                */
                wp_redirect(get_permalink(339));
                exit;
                ?>

                <?php
                /*

                <form name="redirectData" id="redirectData" method="post" action="<?php echo get_permalink(6); ?>" style="display:none;">
                    <?php
                    if ( !is_null($this->posted) ) {
                        foreach ($this->posted as $k => $v) {
                            echo '<input type="hidden" name="' . $k . '" value="' . $v . '"> ';
                        }
                    }
                    ?>
                </form>
            <script type="text/javascript">
                    //document.forms["redirectData"].submit();
               //document.getElementById('redirectData').submit();
            </script>
            */
                ?>
            
            
                <?php
                
            } 
            catch ( Exception $e ) {
                            if ( ! empty( $e ) ) {
                                    //die($e->getMessage());
                                    die;
                            }
            }
            
        }
        
        public function getOrderODTData($order)
        {
            global $wpdb;
            $order = esc_sql(trim($order));
            
            if(!empty($order))
            {
                $orderODTDetailsQuery = "SELECT * FROM wp_order_dates_and_times WHERE odt_order_id = '".$order."' AND odt_collect_date IS NOT NULL AND odt_dropoff_date IS NOT NULL LIMIT 1 ";
                $getODTDetat = $wpdb->get_results($orderODTDetailsQuery);
                if(count($getODTDetat))
                {
                    return $getODTDetat;
                }
            }
            
            return array();
        }
        
        public function setOrderODTData($odtData)
        {
            global $wpdb;
            if(is_array($odtData))
            {
                $inserOrderResult = $wpdb->insert('wp_order_dates_and_times', $odtData);
            }
        }
        
        public function showCheckoutbyShortcode()
        {
           ob_start();
           ?>
           
                <div class="row">
                    <div class="small-12 medium-12 large-12">
                        <h4>Please Enter Your Details</h4>
                        <p>Returning customer? <a class="loginReveal" href="#" data-reveal-id="sectionModal">Click here to login</a></p>
                    </div>
                </div>

                <?php //var_dump($_SESSION['sessposted']); ?>

               <div class="row">
                    <div class="small-12 medium-12 large-12 left" id="checkout-flash-errors">
                      <?php do_action( 'custom_process_notices' ); ?>
                    </div>
                    <div class="clearfix"></div>
               </div>   
               
               <div class="row">
                <div class="small-12 medium-6 large-5 left columns left-details">
                    <div class="row">
                        <div class="small-12 columns">
                            <div class="row">
                                <div class="small-4 columns form-label">
                                    <label for="right-label" class="right inline">Your First Name <i>*</i></label>
                                </div>
                                <div class="small-8 columns form-field">
                                    <input name="billing_first_name" type="text" id="right-label" placeholder="Your Full Name" value="<?php echo getPostedValueByKey('billing_first_name'); ?>">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="small-12 columns">
                            <div class="row">
                                <div class="small-4 columns form-label">
                                    <label for="right-label" class="right inline">Your Last Name <i>*</i></label>
                                </div>
                                <div class="small-8 columns form-field">
                                    <input name="billing_last_name" type="text" id="right-label" placeholder="Your Last Name" value="<?php echo getPostedValueByKey('billing_last_name'); ?>">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="small-12 columns">
                            <div class="row">
                                <div class="small-4 columns form-label">
                                    <label for="right-label" class="right inline">Your Email Address <i>*</i></label>
                                </div>
                                <div class="small-8 columns form-field">
                                    <input name="billing_email" type="text" id="right-label" placeholder="Your Email Address" value="<?php echo getPostedValueByKey('billing_email'); ?>">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="small-12 columns">
                            <div class="row">
                                <div class="small-4 columns form-label">
                                    <label for="right-label" class="right inline">Your Phone Number <i>*</i></label>
                                </div>
                                <div class="small-8 columns form-field">
                                    <input name="billing_phone" type="text" id="right-label" placeholder="Your Phone Number" value="<?php echo getPostedValueByKey('billing_phone'); ?>">
                                </div>
                            </div>
                        </div>
                    </div>
              
                    <?php
                    /*
                    <div class="row">
                        <div class="small-12 columns">
                            <div class="row">
                                <div class="small-4 columns">
                                    <label for="right-label" class="right inline">Your Mobile Number</label>
                                </div>
                                <div class="small-8 columns">
                                    <input name="billing_mobile_number" type="text" id="right-label" placeholder="Your Mobile Number">
                                </div>
                            </div>
                        </div>
                    </div>
                     */
                    ?>
                    
                 </div>
                <div class="small-12 medium-6 large-5 left medium-offset-1 columns right-details">
                    <div class="row">
                        <div class="small-12 columns">
                            <div class="row">
                                <div class="small-4 columns form-label">
                                    <label for="right-label" class="right inline">Your Address <i>*</i></label>
                                </div>
                                <div class="small-8 columns form-field">
                                    <input name="billing_address_1" type="text" id="right-label" placeholder="Your Address" value="<?php echo getPostedValueByKey('billing_address_1'); ?>">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="small-12 columns">
                            <div class="row">
                                <div class="small-4 columns form-label">
                                    <label for="right-label" class="right inline">Address Line 2 <span>optional</span></label>
                                </div>
                                <div class="small-8 columns form-field">
                                    <input name="billing_address_2" type="text" id="right-label" placeholder="Address Line 2" value="<?php echo getPostedValueByKey('billing_address_2'); ?>">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="small-12 columns">
                            <div class="row">
                                <div class="small-4 columns form-label">
                                    <label for="right-label" class="right inline">Your Town/City <i>*</i></label>
                                </div>
                                <div class="small-8 columns form-field">
                                    <input name="billing_city" type="text" id="right-label" placeholder="Your Town/City" value="<?php echo getPostedValueByKey('billing_city'); ?>">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="small-12 columns">
                            <div class="row">
                                <div class="small-4 columns form-label">
                                    <label for="right-label" class="right inline">Your Postcode <i>*</i></label>
                                </div>
                                <div class="small-8 columns form-field">
                                    <?php
                                    if(! is_user_logged_in())
                                    {
                                        $postcodeSess = getPostcodeSessionDetails();
                                        ?>
                                    <input name="billing_postcode" type="text" id="right-label" placeholder="Your Postcode" value="<?php echo (!empty($postcodeSess)) ? $postcodeSess : getPostedValueByKey('billing_postcode'); ?>">
                                    <?php
                                    }
                                    else
                                    {
                                    ?>
                                    <input name="billing_postcode" type="text" id="right-label" placeholder="Your Postcode" value="<?php echo getPostedValueByKey('billing_postcode'); ?>">
                                    <?php
                                    }
                                    ?>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="small-12 columns">
                            <div class="row">
                                <div class="small-4 columns form-label">
                                    <label for="right-label" class="right inline">Additional Notes <span>optional - for example, delivery instructions</span></label>
                                </div>
                                <div class="small-8 columns form-field">
                                    <textarea name="order_comments" id="right-label" placeholder="Additional Notes"><?php echo getPostedValueByKey('order_comments'); ?></textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
           
           <section id="payment-proceed" class="pay-proceed">
                <div class="row">
                    <div class="small-12 medium-7 large-7 right columns">
                        <div class="checkout-proceed-button">
                            <div class="row">
                                <div class="small-12 medium-5 large-5 right columns">
                                    <?php wp_nonce_field( 'woocommerce-process_checkout' ); ?>
                                 <button type="submit" name="custom-checkout" class="button proceed-btn chevron-right"><?php echo (!empty($buttonLabel)) ? $buttonLabel : 'Proceed to Checkout'; ?></button>
                                </div>
                                <div class="small-12 medium-7 large-7 left columns">
                                 <?php
                                 $buttonText = get_field('paypal_button_text', 'options');
                                 $buttonLabel = get_field('proceed_button', 'options');
                                 echo (!empty($buttonText)) ? '<p>'.$buttonText.'</p>' : '';
                                 ?>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="small-12 medium-3 large-3 medium-offset-1 columns left">
                        <?php
                        $pPalLogo = get_field('paypal_logo', 'options');
                        if(!empty($pPalLogo))
                        {
                            $getPPLogo = wp_get_attachment_image($pPalLogo, 'checkout-pay-logo');
                            echo $getPPLogo;
                        }
                        ?>
                    </div>
                    <div class="clearfix"></div>
                </div>
           </section>
           <?php
           return ob_get_clean();
        }
        
    }

}