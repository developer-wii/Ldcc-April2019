<?php
global $wp_query, $category_ID;
global $wp;
$current_url = home_url(add_query_arg(array(),$wp->request));

$cat_obj = $wp_query->get_queried_object();
if($cat_obj != null){
    $category_name = $cat_obj->name;
    $category_desc = $cat_obj->description;
    $category_ID  = $cat_obj->term_id;
}

/**
 * The Template for displaying product archives, including the main shop page which is a post type archive.
 *
 * Override this template by copying it to yourtheme/woocommerce/archive-product.php
 *
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     2.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

get_header( 'shop' ); ?>

<?php
//Require Instance of customWoocheckout Class
require_once TEMPLATEPATH .'/classes/customWooCheckout.php';
$customWooCheckout = new customWooCheckout;

//$postData = $customWooCheckout->getPostedDataFromCheckout();

?>

	<?php
		/**
		 * woocommerce_before_main_content hook
		 *
		 * @hooked woocommerce_output_content_wrapper - 10 (outputs opening divs for the content)
		 * @hooked woocommerce_breadcrumb - 20
		 */
		do_action( 'woocommerce_before_main_content' );
	?>

        <div class="row">
            <div class="small-12 medium-12 large-12 columns order-page-intro"> 

           <?php echo do_shortcode('[postcode_lookup_section]'); ?>
            </div>
        </div>
        <?php echo do_shortcode('[show_top_level_categories]'); ?>
        
            <section class="products-category">
                <section class="row">
                    
                    <div class="view-basket-screen top-view-basket">
                        <?php
                        global $woocommerce;
                        ?>
                        <a href="#" class="button aside-button" data-items="<?php echo $woocommerce->cart->cart_contents_count; ?>">
                            <span>View Basket</span> - 
                        <?php
                        echo 'Items ('.$woocommerce->cart->cart_contents_count.')';
                        ?>
                        </a>
                    </div>
            <?php
                    /**
                     * woocommerce_sidebar hook
                     *
                     * @hooked woocommerce_get_sidebar - 10
                     */
                    do_action( 'woocommerce_sidebar' );
                    //get_sidebar(106);
            ?>
                    
                    <section class="small-12 medium-7 large-7 left columns">
                    <?php if ( apply_filters( 'woocommerce_show_page_title', true ) ) : ?>

                            <h1 class="page-title"><?php woocommerce_page_title(); ?></h1>

                    <?php endif; ?>

                    <?php do_action( 'woocommerce_archive_description' ); ?>

                    <?php if ( have_posts() ) : ?>

                            <?php
                                    /**
                                     * woocommerce_before_shop_loop hook
                                     *
                                     * @hooked woocommerce_result_count - 20
                                     * @hooked woocommerce_catalog_ordering - 30
                                     */
                                    do_action( 'woocommerce_before_shop_loop' );
                            ?>

                            <?php woocommerce_product_loop_start(); ?>

                                    <?php woocommerce_product_subcategories(); ?>

                                    <?php while ( have_posts() ) : the_post(); ?>

                                            <?php wc_get_template_part( 'content', 'product-custom' ); ?>

                                    <?php endwhile; // end of the loop. ?>

                            <?php woocommerce_product_loop_end(); ?>

                            <?php
                                    /**
                                     * woocommerce_after_shop_loop hook
                                     *
                                     * @hooked woocommerce_pagination - 10
                                     */
                                    do_action( 'woocommerce_after_shop_loop' );
                            ?>


                        <?php
                        $archScroller = get_field('archive_scroll_callout', 'options');
                        if(!empty($archScroller))
                        {
                            echo $archScroller;
                        }
                        ?>
                            
                    <?php elseif ( ! woocommerce_product_subcategories( array( 'before' => woocommerce_product_loop_start( false ), 'after' => woocommerce_product_loop_end( false ) ) ) ) : ?>

                            <?php wc_get_template( 'loop/no-products-found.php' ); ?>

                    <?php endif; ?>
   
                     <?php /*       
                    <div class="view-basket-screen bottom-view-basket">
                        <a href="#" class="button aside-button" data-items="<?php echo $woocommerce->cart->cart_contents_count; ?>">
                            View Basket -  <?php echo 'Items ('.$woocommerce->cart->cart_contents_count.')'; ?>
                        </a>
                    </div>
                      */
                     ?>
                            
            </section>
                    
         </section>
         </section>

    <form name="proceed-with-payment" method="post" action="<?php echo $current_url; ?>">
         <section id="collection" class="collection">
            <section class="row">
    
                <?php
                //If dates and times set by session
                $collectDate = $customWooCheckout->getSessionValue('collection-picker');
                //$collectTime = $customWooCheckout->getSessionValue('collect-time');
                $dropoffDate = $customWooCheckout->getSessionValue('dropoff-picker');
                //$dropoffTime = $customWooCheckout->getSessionValue('drop-time');
                
                //echo $collectDate.' <br>'.$collectTime.' <br>'.$dropoffDate.' <br>'.$dropoffTime.' <br>';
                
                ?>
                
                <div class="small-12 medium-3 large-3 columns">
                    
                    <div class="show-for-seven-six-eight">
                        <h3>Choose Collection date</h3>
                    </div>
                    
                    <div class="datepicker-collection">
                         <?php
                            require_once TEMPLATEPATH .'/vendor/donatj/simplecalendar/lib/donatj/SimpleCalendar.php';
                            $calendarOne = new donatj\SimpleCalendar();
                            $calendarOne->setStartOfWeek('Sunday');
                            
                            if(isset($_GET['collection-date']) && $_GET['collection-date'] != '')
                            {
                                $getDateCollectionParam = esc_sql(trim($_GET['collection-date']));
                                //$viewDate = strtotime(date("Y-m-d", strtotime($getDateParam)));
                                $dateNames = date("F", strtotime($getDateCollectionParam));
                                $dateYears = date("Y", strtotime($getDateCollectionParam));
                                $calendarOne->setDate($getDateCollectionParam);
                                $calendarOne->setTableId('collection-picker');

                                $prev_date = strtotime('-1 month', strtotime($getDateCollectionParam));
                                $next_date = strtotime('+1 month', strtotime($getDateCollectionParam));
                                $prev_date_link = date('Y-m-d', $prev_date);
                                $next_date_link = date('Y-m-d', $next_date);

                                ?>
                                <div class="tab-heading">
                                    <a href="?collection-date=<?php echo $prev_date_link;?>" class="chev-left left"><i class="fa fa-chevron-left"></i></a>
                                    <span><?php echo $dateNames . ' ' .$dateYears; ?></span>
                                    <a href="?collection-date=<?php echo $next_date_link;?>" class="chev-right right"><i class="fa fa-chevron-right"></i></a>
                                </div>
                                <?php
                                //If seesion has date assigned, assign the selected date
                                if(!empty($collectDate))
                                {
                                    $formatCurrCollectDate = new DateTime($collectDate);
                                    $calendarOne->assignAlreadyChosenDates($formatCurrCollectDate->format('Y-m-d'));
                                }
                                $calendarOne->show(true);
                                ?>
                                <div class="tab-footer">
                                    <?php 
                                    $currSelectCollect = $customWooCheckout->retriveSessionData('collection-picker'); 
                                    if(!empty($currSelectCollect))
                                    {
                                        echo $currSelectCollect;
                                    }
                                    ?>
                                </div>
                                <?php
                            }
                            else
                            {
                                //$calendar->addDailyHtml( 'Sample Event', 'today', 'tomorrow' );
                                $next_date = date('Y-m-d', strtotime('+1 month'));
                                $prev_date = date('Y-m-d', strtotime('-1 month'));
                                $dateNames = date("F");
                                $dateYears = date("Y");
                                $calendarOne->setDate();
                                $calendarOne->setTableId('collection-picker');
                                ?>
                                <div class="tab-heading">
                                    <a href="?collection-date=<?php echo $prev_date;?>" class="chev-left left"><i class="fa fa-chevron-left"></i></a>
                                    <span><?php echo $dateNames . ' ' .$dateYears; ?></span>
                                    <a href="?collection-date=<?php echo $next_date;?>" class="chev-right right"><i class="fa fa-chevron-right"></i></a>
                                </div>
                                <?php
                                //If seesion has date assigned, assign the selected date
                                if(!empty($collectDate))
                                {
                                    $formatCurrCollectDate = new DateTime($collectDate);
                                    $calendarOne->assignAlreadyChosenDates($formatCurrCollectDate->format('Y-m-d'));
                                }
                                $calendarOne->show(true);
                                ?>
                                <div class="tab-footer">
                                    <?php 
                                    $currSelectCollect = $customWooCheckout->retriveSessionData('collection-picker'); 
                                    if(!empty($currSelectCollect))
                                    {
                                        echo $currSelectCollect;
                                    }
                                    ?>
                                </div>
                                <?php
                            }
                        ?>
                    </div>
                </div>
                <div class="small-12 medium-8 large-8 large-offset-1 columns">
                    <h3>Choose Date of Collection</h3>
                    <p>Please choose the date you wish for collection to take place on from the calander on the left.  Once you have selected a date, please choose a time from the options below.</p>
                    
                    <h4>Collection Time</h4>
                    <?php echo generateCollectionAndDropoffs('collection'); 
                    
                    $sectionCompletedCollection  = ((flagSectionIfCompleted('collection') == true)) ? 'flagged' : '';
                    ?>
                    
                    <a id="collection-button" href="#" class="smooth-scroll button chevron large-buttons <?php echo $sectionCompletedCollection; ?>" data-target="dropoff" <?php if(flagSectionIfCompleted('collection') == false) { echo 'disabled="disabled"'; } ?>>Choose Drop Off Date/Time</a>
                </div>
            </section>
         </section>


         <section id="dropoff" class="dropoff">
            <section class="row">
                
                    <div class="show-for-seven-six-eight">
                        <h3>Choose Dropoff date</h3>
                    </div>

                <div class="small-12 medium-3 large-3 large-offset-1 columns right">
                    
                    <div class="datepicker-collection">
                        <?php
                            $calendarTwo = new donatj\SimpleCalendar();
                            $calendarTwo->setStartOfWeek('Sunday');

                            if(isset($_GET['dropoff-date']) && $_GET['dropoff-date'] != '')
                            {
                                $getDateDropoffParam = esc_sql(trim($_GET['dropoff-date']));
                                //$viewDate = strtotime(date("Y-m-d", strtotime($getDateParam)));
                                $dateNames = date("F", strtotime($getDateDropoffParam));
                                $dateYears = date("Y", strtotime($getDateDropoffParam));
                                $calendarTwo->setDate($getDateDropoffParam);
                                $calendarTwo->setTableId('dropoff-picker');

                                $prev_date = strtotime('-1 month', strtotime($getDateDropoffParam));
                                $next_date = strtotime('+1 month', strtotime($getDateDropoffParam));
                                $prev_date_link = date('Y-m-d', $prev_date);
                                $next_date_link = date('Y-m-d', $next_date);
                                ?>
                                <div class="tab-heading">
                                    <a href="?dropoff-date=<?php echo $prev_date_link;?>" class="chev-left left"><i class="fa fa-chevron-left"></i></a>
                                    <span><?php echo $dateNames . ' ' .$dateYears; ?></span>
                                    <a href="?dropoff-date=<?php echo $next_date_link;?>" class="chev-right right"><i class="fa fa-chevron-right"></i></a>
                                </div>
                                <?php
                                //If seesion has date assigned, assign the selected date
                                if(!empty($dropoffDate))
                                {
                                    $formatCurrDropDate = new DateTime($dropoffDate);
                                    $calendarTwo->assignAlreadyChosenDates($formatCurrDropDate->format('Y-m-d'));
                                }
                                $calendarTwo->show(true);
                                ?>
                                <div class="tab-footer">
                                    <?php
                                    $currSelectDrop = $customWooCheckout->retriveSessionData('dropoff-picker'); 
                                    if(!empty($currSelectDrop))
                                    {
                                        echo $currSelectDrop;
                                    }
                                    ?>
                                </div>
                                <?php
                            }
                            else
                            {
                                //$calendar->addDailyHtml( 'Sample Event', 'today', 'tomorrow' );
                                $next_date = date('Y-m-d', strtotime('+1 month'));
                                $prev_date = date('Y-m-d', strtotime('-1 month'));
                                $calendarTwo->setDate();
                                $calendarTwo->setTableId('dropoff-picker');
                                ?>
                                <div class="tab-heading">
                                    <a href="?dropoff-date=<?php echo $prev_date;?>" class="chev-left left"><i class="fa fa-chevron-left"></i></a>
                                    <span><?php echo $dateNames . ' ' .$dateYears; ?></span>
                                    <a href="?dropoff-date=<?php echo $next_date;?>" class="chev-right right"><i class="fa fa-chevron-right"></i></a>
                                </div>
                                <?php
                                //If seesion has date assigned, assign the selected date
                                if(!empty($dropoffDate))
                                {
                                    $formatCurrDropDate = new DateTime($dropoffDate);
                                    $calendarTwo->assignAlreadyChosenDates($formatCurrDropDate->format('Y-m-d'));
                                }
                                $calendarTwo->show(true);
                                ?>
                                <div class="tab-footer">
                                    <?php
                                    $currSelectDrop = $customWooCheckout->retriveSessionData('dropoff-picker'); 
                                    if(!empty($currSelectDrop))
                                    {
                                        echo $currSelectDrop;
                                    }
                                    ?>
                                </div>
                                <?php
                            }
                        ?>
                    </div>
                </div>

                <div class="small-12 medium-8 large-8 columns left">
                    <h3>Choose Drop Off Date</h3>
                    <p>Please choose the date you wish for your items to be dropped off  from the calander on the right.  Once you have selected a date, please choose a time from the options below.</p>
                    
                    <h4>Drop off time</h4>
                    <?php echo generateCollectionAndDropoffs('dropoff'); 
                    $sectionCompletedDropiff = ((flagSectionIfCompleted('dropoff') == true)) ? 'flagged' : '';
                    ?>
                    
                    <a id="dropoff-button" class="smooth-scroll button chevron large-buttons <?php echo $sectionCompletedDropiff; ?>" href="#" data-target="ldcc-checkout" <?php if(flagSectionIfCompleted('dropoff') == false) { echo 'disabled="disabled"'; } ?>>Enter Your Details</a>
                    
                </div> 
                
            </section>
         </section>

        <?php
        if(is_user_logged_in())
        {
            ?>
        <section id="ldcc-checkout" class="row">
               <div class="row">
                    <div class="small-12 medium-12 large-12 left" id="checkout-flash-errors">
                      <?php do_action( 'custom_process_notices' ); ?>
                    </div>
                    <div class="clearfix"></div>
               </div>   
        </section>
        
           <section id="payment-proceed" class="pay-proceed">
                <div class="row">
                    <div class="small-12 medium-3 large-3 medium-offset-1 columns">
                        <?php
                        $pPalLogo = get_field('paypal_logo', 'options');
                        if(!empty($pPalLogo))
                        {
                            $getPPLogo = wp_get_attachment_image($pPalLogo, 'checkout-pay-logo');
                            echo $getPPLogo;
                        }
                        ?>
                    </div>
                    <div class="small-12 medium-7 large-7 left columns">
                        <div class="checkout-proceed-button">
                            <div class="row">
                                <div class="small-12 medium-7 large-7 left columns">
                                 <?php
                                 $buttonText = get_field('paypal_button_text', 'options');
                                 $buttonLabel = get_field('proceed_button', 'options');
                                 echo (!empty($buttonText)) ? '<p>'.$buttonText.'</p>' : '';
                                 ?>
                                </div>
                                <div class="small-12 medium-5 large-5 left columns">
                                    <?php wp_nonce_field( 'woocommerce-process_checkout' ); ?>
                                 <button type="submit" name="custom-checkout" class="button proceed-btn chevron-right"><?php echo (!empty($buttonLabel)) ? $buttonLabel : 'Proceed to Checkout'; ?></button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="clearfix"></div>
                </div>
           </section>
            <?php
        }
        else
        {
        ?>
        <section id="ldcc-checkout" class="row">
            <?php
            //error_reporting(E_ALL ^ E_WARNING);
            require_once TEMPLATEPATH .'/classes/customWooCheckout.php';
            $customWoo = new customWooCheckout;
            $billingForm = $customWoo->showCheckoutbyShortcode();

            echo $billingForm;
            ?>

        </section>
        
        <?php
        }
        ?>
        
    </form>

            <?php
                    /**
                     * woocommerce_after_main_content hook
                     *
                     * @hooked woocommerce_output_content_wrapper_end - 10 (outputs closing divs for the content)
                     */
                    do_action( 'woocommerce_after_main_content' );
            ?>

<?php get_footer( 'shop' ); ?>
