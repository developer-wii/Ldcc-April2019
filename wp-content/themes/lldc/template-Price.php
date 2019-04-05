<?php 

/*
    Template Name: Price
*/

    get_header();
    
    $head = ""; // acf heading value
    $desc = ""; // acf Description value
    $More_money_saving_package = ""; //  acf More money saving package button text
    
    while ( have_posts() ) {
		the_post(); 
		$head = get_field('price_page_heading', get_the_ID());
		$desc = get_field('price_page_heading', get_the_ID());
		$More_money_saving_package = get_field('more_money_saving_btn_text',get_the_ID());
	} 
    
?>

<main>
			<section id="heading_sec">
				<div class="container">
					<div class="row">
						<div class="col-md-12 text-center">
							<h2><?php echo $head; ?> </h2>
							<h3><?php echo $desc; ?></h3>
						</div>
					</div>
				</div>
			</section>
			<section id="moremony">
				<div class="container">
					<div class="row">
					    <div class="images_cover">
					    <?php 
					    $args = array(
                            'taxonomy'   => "product_cat",
                            'number'     => 3,
                            'hide_empty' => false,
                        );
                        $product_categories = get_terms($args);
                        
                        
                        foreach($product_categories as $product){
                            $thumbnail_id = get_woocommerce_term_meta( $product->term_id, 'thumbnail_id', true );
	                        $image = wp_get_attachment_url( $thumbnail_id );
	                        // $image getting Product category images
                            ?>  
                            <div class="">
								<div class="imagecover">
									<img class="img-responsive" src="<?php echo $image; ?>" alt="couples plan"></div>
							</div>
                            
                            <?php
                        }
                        wp_reset_query();
                        // reset wp query
                        
					    ?>
						</div>
					</div>
					<div class="row">
						<div class="col-sm-12">

							<div class="monylink"><a href="<?php echo get_site_url(); ?>/monthly-plan/" class="hovereffect"><?php echo $More_money_saving_package; ?></a></div>

						</div>
					</div>
				</div>


			</section>

			<section id="serices">
				<div class="container">
					<div class="row">
						<div class="col-md-12 text-center">
							<h2><?php echo $head; ?> </h2>
							<h3><?php echo $desc; ?></h3>
						</div>
					</div>
					<div class="row">
					    <?php 
        				    $args = array(
                                'taxonomy'   => "product_cat",
                            );
                            $product_categories = get_terms($args);
                            // get product texonomy "category"
                            
                            
                            foreach($product_categories as $cat){
                                $args = array( 
                                    'post_type'=> 'product',
                                    'tax_query' => array(
                                        array(
                                        'taxonomy' => 'product_cat',
                                         'field'    => 'id',
                                         'terms'     =>  $cat->term_id, 
                                        )
                                     
                                     )
                                );
                                $cat_product = get_posts( $args );
                                // get product texonomy "category" and its products only
                                
                                ?>
                                
                                <div class="col-md-3 col-sm-6">
        							<div class="servicecover">
        								<h4><?php echo $cat->name; ?> </h4>
        								<ul>
                                            <?php
                                            foreach($cat_product as $product){
                                                $product_data = wc_get_product( $product->ID );
                                                echo '<li><span> '.$product->post_name.'</span> '.get_woocommerce_currency_symbol(get_option('woocommerce_currency')).' '.$product_data->get_price().'</li>';
                                            }
                                            wp_reset_postdata();
                                            // reset wp postdata
                                            ?>
        								</ul>
        							</div>
        						</div>
                                <?php
                            }
                        ?>  

					</div>
				</div>



			</section>
		</main>


<?php
    get_footer();
?>