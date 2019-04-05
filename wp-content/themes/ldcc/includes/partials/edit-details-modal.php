<div id="editdetailsModal" class="reveal-modal" data-reveal aria-labelledby="modalTitle" aria-hidden="true" role="dialog">

     <a class="close-reveal-modal" aria-label="Close">&#215;</a>

<?php
if(is_user_logged_in())
{
    $user = wp_get_current_user();
    $currCustomer = customWooGetUserDetails($user->data->ID);
    //$customerContacts = customWooGetContactDetails($user->data->ID);
    //var_dump($currCustomer);
    
    $customerMetas = get_user_meta($user->data->ID);
    //echo '<pre>';
    //var_dump($customerMetas);
    //echo '</pre>';
?>
    <section id="editdetailsModelSection" class="editdetails-section">
            <form style="" class="edit-details-form" method="post" action="<?php echo get_permalink(6); ?>">
                <h2>Edit Your Details</h2>
                    <div class="row">
                        <div class="small-12 columns">
                            <div class="row">
                                <div class="small-4 columns details-label">
                                    <label for="right-label" class="right inline">Your First Name <i>*</i></label>
                                </div>
                                <div class="small-8 columns details-field">
                                    <input name="billing_first_name" type="text" id="right-label" placeholder="Your Full Name" value="<?php echo $currCustomer['shipping_first_name']; ?>">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="small-12 columns">
                            <div class="row">
                                <div class="small-4 columns details-label">
                                    <label for="right-label" class="right inline">Your Last Name <i>*</i></label>
                                </div>
                                <div class="small-8 columns details-field">
                                    <input name="billing_last_name" type="text" id="right-label" placeholder="Your Last Name" value="<?php echo $currCustomer['shipping_last_name']; ?>">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="small-12 columns">
                            <div class="row">
                                <div class="small-4 columns details-label">
                                    <label for="right-label" class="right inline">Your Email Address <i>*</i></label>
                                </div>
                                <div class="small-8 columns details-field">
                                    <input name="billing_email" type="text" id="right-label" placeholder="Your Email Address" value="<?php echo $customerMetas['billing_email'][0]; ?>">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="small-12 columns">
                            <div class="row">
                                <div class="small-4 columns details-label">
                                    <label for="right-label" class="right inline">Your Phone Number <i>*</i></label>
                                </div>
                                <div class="small-8 columns details-field">
                                    <input name="billing_phone" type="text" id="right-label" placeholder="Your Phone Number" value="<?php echo $customerMetas['billing_phone'][0]; ?>">
                                </div>
                            </div>
                        </div>
                    </div>
                
                    <div class="row">
                        <div class="small-12 columns">
                            <div class="row">
                                <div class="small-4 columns details-label">
                                    <label for="right-label" class="right inline">Your Address <i>*</i></label>
                                </div>
                                <div class="small-8 columns details-field">
                                    <input name="billing_address_1" type="text" id="right-label" placeholder="Your Address" value="<?php echo $currCustomer['shipping_address_1']; ?>">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="small-12 columns">
                            <div class="row">
                                <div class="small-4 columns details-label">
                                    <label for="right-label" class="right inline">Address Line 2 <span>optional</span></label>
                                </div>
                                <div class="small-8 columns details-field">
                                    <input name="billing_address_2" type="text" id="right-label" placeholder="Address Line 2" value="<?php echo $currCustomer['shipping_address_2']; ?>">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="small-12 columns">
                            <div class="row">
                                <div class="small-4 columns details-label">
                                    <label for="right-label" class="right inline">Your Town/City <i>*</i></label>
                                </div>
                                <div class="small-8 columns details-field">
                                    <input name="billing_city" type="text" id="right-label" placeholder="Your Town/City" value="<?php echo $currCustomer['shipping_city']; ?>">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="small-12 columns">
                            <div class="row">
                                <div class="small-4 columns details-label">
                                    <label for="right-label" class="right inline">Your Postcode <i>*</i></label>
                                </div>
                                <div class="small-8 columns details-field">
                                    <input name="billing_postcode" type="text" id="right-label" placeholder="Your Postcode" value="<?php echo $currCustomer['shipping_postcode']; ?>">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="small-12 columns"> 
                            <div class="row">
                                <div class="small-4 columns details-label">
                                    <label for="right-label" class="right inline">Additional Notes <span>optional - for example, delivery instructions</span></label>
                                </div>
                                <div class="small-8 columns details-field">
                                    <textarea name="order_comments" id="right-label" placeholder="Additional Notes"><?php echo $_POST['order_comments']; ?></textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                <button type="submit" value="" name="save-user-details" class="button save-details">Confirm Changes</button>
            </form>
    </section>

</div>
     
<?php
}
else
{
    //var_dump($_POST);
?>
    <section id="editdetailsModelSection" class="editdetails-section">
            <form style="" class="edit-details-form" method="post" action="<?php echo get_permalink(6); ?>">
                <h2>Edit Your Details</h2>
                    <div class="row">
                        <div class="small-12 columns">
                            <div class="row">
                                <div class="small-4 columns details-label">
                                    <label for="right-label" class="right inline">Your First Name <i>*</i></label>
                                </div>
                                <div class="small-8 columns details-field">
                                    <input name="billing_first_name" type="text" id="right-label" placeholder="Your Full Name" value="<?php echo $_POST['billing_first_name']; ?>">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="small-12 columns">
                            <div class="row">
                                <div class="small-4 columns details-label">
                                    <label for="right-label" class="right inline">Your Last Name <i>*</i></label>
                                </div>
                                <div class="small-8 columns details-field">
                                    <input name="billing_last_name" type="text" id="right-label" placeholder="Your Last Name" value="<?php echo $_POST['billing_last_name']; ?>">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="small-12 columns">
                            <div class="row">
                                <div class="small-4 columns details-label">
                                    <label for="right-label" class="right inline">Your Email Address <i>*</i></label>
                                </div>
                                <div class="small-8 columns details-field">
                                    <input name="billing_email" type="text" id="right-label" placeholder="Your Email Address" value="<?php echo $_POST['billing_email']; ?>">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="small-12 columns">
                            <div class="row">
                                <div class="small-4 columns details-label">
                                    <label for="right-label" class="right inline">Your Phone Number <i>*</i></label>
                                </div>
                                <div class="small-8 columns details-field">
                                    <input name="billing_phone" type="text" id="right-label" placeholder="Your Phone Number" value="<?php echo $_POST['billing_phone']; ?>">
                                </div>
                            </div>
                        </div>
                    </div>
                
                    <div class="row">
                        <div class="small-12 columns">
                            <div class="row">
                                <div class="small-4 columns details-label">
                                    <label for="right-label" class="right inline">Your Address <i>*</i></label>
                                </div>
                                <div class="small-8 columns details-field">
                                    <input name="billing_address_1" type="text" id="right-label" placeholder="Your Address" value="<?php echo $_POST['billing_address_1']; ?>">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="small-12 columns">
                            <div class="row">
                                <div class="small-4 columns details-label">
                                    <label for="right-label" class="right inline">Address Line 2 <span>optional</span></label>
                                </div>
                                <div class="small-8 columns details-field">
                                    <input name="billing_address_2" type="text" id="right-label" placeholder="Address Line 2" value="<?php echo $_POST['billing_address_2']; ?>">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="small-12 columns">
                            <div class="row">
                                <div class="small-4 columns details-label">
                                    <label for="right-label" class="right inline">Your Town/City <i>*</i></label>
                                </div>
                                <div class="small-8 columns details-field">
                                    <input name="billing_city" type="text" id="right-label" placeholder="Your Town/City" value="<?php echo $_POST['billing_city']; ?>">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="small-12 columns">
                            <div class="row">
                                <div class="small-4 columns details-label">
                                    <label for="right-label" class="right inline">Your Postcode <i>*</i></label>
                                </div>
                                <div class="small-8 columns details-field">
                                    <input name="billing_postcode" type="text" id="right-label" placeholder="Your Postcode" value="<?php echo $_POST['billing_postcode']; ?>">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="small-12 columns">
                            <div class="row">
                                <div class="small-4 columns details-label">
                                    <label for="right-label" class="right inline">Additional Notes <span>optional - for example, delivery instructions</span></label>
                                </div>
                                <div class="small-8 columns details-field">
                                    <textarea name="order_comments" id="right-label" placeholder="Additional Notes"><?php echo $_POST['order_comments']; ?></textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                <button type="submit" value="" name="save-details" class="button save-details">Confirm Changes</button>
            </form>
    </section>

</div>

<?php
}
?>