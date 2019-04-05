<?php
/**
 * Email Footer
 *
 * @author 		WooThemes
 * @package 	WooCommerce/Templates/Emails
 * @version     2.3.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

?>
															</div>
														</td>
                                                    </tr>
                                                </table>
                                                <!-- End Content -->
                                            </td>
                                        </tr>
                                    </table>
                                    <!-- End Body -->
                                </td>
                            </tr>
                        	<tr>
                            	<td align="center" valign="top">
                                    <!-- Footer -->
                                	<table border="0" cellpadding="10" cellspacing="0" width="600" id="template_footer">
                                    	<tr>
                                             <td valign="top">
                                                <table border="0" cellpadding="0" cellspacing="0" width="100%">
                                                    <tr>
                                                        <td colspan="2" valign="middle" style="padding:20px;text-align:center;" id="email-footer-section">
                                       
                                                            <p style="font-family: Calibri, Verdana, 'Times New Roman', sans-serif;">If you require assistance with your order, please visit our <a href="<?php echo get_permalink(18); ?>" target="_blank">Help page</a> for more information, or call us on <a href="tel:02087747569">0208 774 7569</a>.</p>
                                                            
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td width="100%">
                                                            
                                                            <table width="65%" cellspacing="0" cellpadding="0" border="0" align="center">
                                                                <tr>
                                                                    <td id="share-social">
                                                                        
                                                            <h3>Share Your London Dry Cleaning Experience</h3>
                                                            
                                                            <?php
                                                            if(has_sub_field('email_social_buttons', 'options'))
                                                            {
                                                                $socialIconsEmail = get_field('email_social_buttons', 'options');
                                                                //var_dump($socialIconsEmail);
                                                                foreach((array)$socialIconsEmail as $socialIcon)
                                                                {
                                                                    echo '<a href="'.$socialIcon['social_target_location'].'"><img src="'.$socialIcon['social_icon'].'" /></a>';
                                                                }
                                                            }
                                                            ?>
                                                                        
                                                                    </td>
                                                                </tr>
            
                                                        </td>
                                                    </tr>
                                                </table>
                                            </td>
                                        </tr>
                                    </table>
                                    <!-- End Footer -->
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </table>
        </div>
    </body>
</html>
