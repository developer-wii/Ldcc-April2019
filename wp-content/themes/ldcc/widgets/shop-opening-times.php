<?php
/* WIDGET */
class Shop_Opening_Times_Widget extends WP_Widget {  
    function Shop_Opening_Times_Widget() {  
        parent::WP_Widget(false, 'Shop Opening & Collection/Delivery Widget');  
    }  
    function form($instance) {  
        echo '<p>This widget has no options.</p>';
    }  
    function update($new_instance, $old_instance) {  
        // processes widget options to be saved  
        return $new_instance;  
    }  
    function widget($args, $instance)
    { 
?>
    <div class="sidebar-opening-times">
        <div class="widget-contents">
            <?php 
            $openingHours = get_field('opening_hours', 22);
            $collectionDeliveryHours = get_field('collection_delivery_hours', 22);
            
            $openingHoursTitle = get_field('opening_hours_title', 22);
            $collectionDeliveryTitle = get_field('collection_&_delivery_title', 22);
            
            if(!empty($openingHours) && !empty($collectionDeliveryHours))
            {
                if(!empty($openingHoursTitle))
                {
                    echo '<h3 class="uppercase">'.$openingHoursTitle.'</h3>';
                }
                echo $openingHours;
                
                
                if(!empty($collectionDeliveryTitle))
                {
                    echo '<h3 class="uppercase">'.$collectionDeliveryTitle.'</h3>';
                }
                echo $collectionDeliveryHours;
            }
            ?>
        </div>
    </div>
    <?php 
    } 
}  
register_widget('Shop_Opening_Times_Widget');
?>
