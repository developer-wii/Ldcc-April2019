<?php
/* WIDGET */

class Sidebar_Basket_Widget extends WP_Widget {

    function Sidebar_Basket_Widget() {
        parent::WP_Widget(false, 'Sidebar Basket Widget');
    }

    function form($instance) {
        echo '<p>This widget has no options.</p>';
    }

    function update($new_instance, $old_instance) {
        // processes widget options to be saved  
        return $new_instance;
    }

    function widget($args, $instance) {

        if (is_cart() || is_checkout()) {
            return;
        }
        ?>
        <div class="basket-block">

            <h3>Basket</h3>

            <div id="sidebar-cart" class="relative"></div>
            <div class="clearfix"></div>
        </div>

    <?php
    }

}

register_widget('Sidebar_Basket_Widget');
?>
