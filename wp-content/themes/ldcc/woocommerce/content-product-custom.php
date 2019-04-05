<?php
/**
 * The template for displaying product content within loops.
 *
 * Override this template by copying it to yourtheme/woocommerce/content-product.php
 *
 * @author  WooThemes
 * @package WooCommerce/Templates
 * @version 1.6.4
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

global $product, $woocommerce_loop;

// Store loop count we're currently on
if ( empty( $woocommerce_loop['loop'] ) ) {
	$woocommerce_loop['loop'] = 0;
}

// Store column count for displaying the grid
if ( empty( $woocommerce_loop['columns'] ) ) {
	$woocommerce_loop['columns'] = apply_filters( 'loop_shop_columns', -1 );
}

// Ensure visibility
if ( ! $product || ! $product->is_visible() ) {
	return;
}

// Increase loop count
$woocommerce_loop['loop']++;

// Extra post classes
$classes = array();
if ( 0 == ( $woocommerce_loop['loop'] - 1 ) % $woocommerce_loop['columns'] || 1 == $woocommerce_loop['columns'] ) {
	$classes[] = 'first';
}
if ( 0 == $woocommerce_loop['loop'] % $woocommerce_loop['columns'] ) {
	$classes[] = 'last';
}

$classes[] = 'relative';
?>
<tr <?php post_class( $classes ); ?>>

	<?php //do_action( 'woocommerce_before_shop_loop_item' ); ?>

    <td><div class="product-titles"><?php the_title(); ?></div></td>
    <td><div class="product-prices"><?php do_action('add_custom_get_price'); ?></div></td>
    <td class="quantity-cell">
        <div class="product-quantities">
        <select id="qty" name="quantity" class="turnintodropdown qty">
            <?php 
            $maxTotalQty = 20;
            for($q=1;$q <= $maxTotalQty;$q++)
            {
                echo '<option value="'.$q.'">'.$q.'</option>';
            }
            ?>
        </select>
        </div>
      <?php
      /*
       if ( ! $product->is_sold_individually() )
       {
        woocommerce_quantity_input( array(
                'min_value' => apply_filters( 'woocommerce_quantity_input_min', 1, $product ),
                'max_value' => apply_filters( 'woocommerce_quantity_input_max', $product->backorders_allowed() ? '' : $product->get_stock_quantity(), $product )
        ) );
       }
       * 
       */
        ?>
    </td>
    <td class="add-basket-cell" align="center" verticle-align="middle">
        <div class="product-actions">
        <?php do_action('add_custom_add_basket'); ?>
        </div>
     </td>
</tr>
