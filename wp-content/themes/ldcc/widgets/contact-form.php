<?php
/* WIDGET */
class Contact_Form_Widget extends WP_Widget {  
    function Contact_Form_Widget() {  
        parent::WP_Widget(false, 'Contact Form Widget');  
    }  
    function form($instance) {  
        echo '<p>This widget has no options.</p>';
    }  
    function update($new_instance, $old_instance) {  
        // processes widget options to be saved  
        return $new_instance;  
    }  
    function widget($args, $instance) { 
	
    global $responseContactForm;
?>
    <div class="widget sidebar-block">

        <h3>Contact Us</h3>
        
        <div class="flash-message"><?php echo $responseContactForm; ?></div>

        <form class="sidebar-form" action="<?php the_permalink(); ?>" method="post" id="form">

        <label>Full Name</label>
        <input class="field" type="text" name="message_name" value="<?php echo esc_attr($_POST['message_name']); ?>">
        <label>Telephone Number</label>
        <input class="field" type="text" name="message_telephone" value="<?php echo esc_attr($_POST['message_telephone']); ?>">
        <label>Email Address</label>
        <input class="field" type="text" name="message_email" value="<?php echo esc_attr($_POST['message_email']); ?>">
        <label>Your Message</label>
        <textarea type="text" name="message_text" class="field-area"><?php echo esc_textarea($_POST['message_text']); ?></textarea>

        <input type="text" name="message_human" value="" class="checkhuman">
        <input type="hidden" name="submitted" value="1">
        <input type="submit" value="Send Message" class="button submit-btn">
        </form>
        
    </div>

    <?php } 
}  
register_widget('Contact_Form_Widget');
?>
