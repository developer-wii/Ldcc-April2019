<?php
global $validationResponse;
?>
</main>

<footer id="footer" role="footer" class="footer">
    <section class="row footer-social-bar">
        <div class="large-3 columns footer-logo-left">
                <?php
                $logoId = get_field('logo','options');
                $logoImage = wp_get_attachment_image_src($logoId, 'logo');
                ?>
                            <a href="<?php echo bloginfo('url'); ?>"><img src="<?php echo $logoImage[0]; ?>" class="logo-footer" alt="London Dry Cleaning Company" /></a>
        </div>
        <div class="large-7 large-offset-2 columns footer-social-cont">
            <div class="social-buttons">
                <?php
                $twitterLink = get_field('twitter_link', 'options');
                $facebookLink = get_field('facebook_link', 'options');
                $instagramLink = get_field('instagram_link', 'options');
                $pinterestLink = get_field('pinterest_link', 'options');
                ?>
                <div class="row">
                    
                    <?php
                    /*
                    
                    <div class="icon columns large-3">
                        <i class="fa fa-facebook"></i><a class="uppercase" <?php echo ((!empty($twitterLink))) ? 'href="'.$twitterLink.'"' : ''; ?>>Facebook</a>
                    </div>
                    <div class="icon columns large-3">
                        <i class="fa fa-twitter"></i><a class="uppercase"<?php echo ((!empty($facebookLink))) ? 'href="'.$facebookLink.'"' : ''; ?>>Twitter</a>
                    </div>
                    <div class="icon columns large-3">
                        <i class="fa fa-instagram"></i><a class="uppercase"<?php echo ((!empty($instagramLink))) ? 'href="'.$instagramLink.'"' : ''; ?>>Instagram</a>
                    </div>
                    <div class="icon columns large-3 last">
                        <i class="fa fa-pinterest-p"></i><a class="uppercase"<?php echo ((!empty($pinterestLink))) ? 'href="'.$pinterestLink.'"' : ''; ?>>Pinterest</a>
                    </div> 
                    */
                    ?>
                </div>
            </div>
        </div>
    </section>
    <section class="row footer-contacts">
        <div class="large-3 columns text-left">
            
            <?php
            $footerTelephones = get_field('telephone_number', 'options');
            $footerTelephonesNoSpace = str_replace(" ", "", $footerTelephones);
            
            if(!empty($footerTelephones))
            {
                echo '<a href="tel:'.$footerTelephonesNoSpace.'">'.$footerTelephones.'</a>';
            }
            ?>
        </div>
        <div class="large-5 columns text-center">
            <address>
                <?php
                $busAddress = get_field('business_address', 'options');
                if(!empty($busAddress))
                {
                    echo $busAddress;
                }
                ?>
            </address>
        </div>
        <div class="large-4 columns text-right">
            
            <?php 
            $footerEmail = get_field('email_address', 'options');
            if(!empty($footerEmail))
            {
                echo '<a href="mailto:'.$footerEmail.'?subject=Enquiry from London Dry Cleaning Company Website">info@londondrycleaningcompany.com</a>';
            }
            ?>
            
        </div>
    </section>
    
    <?php 
    /*
    <section class="row footer-our-location show-for-small-only">
        <?php 
        $ourLocationsList = get_field('website_links', 'options');
        if(count($ourLocationsList))
        {
            ?>
        <div class="our-locations small-12">
            <div class="view-our-locations">
            <i class="fa fa-bars"></i>
            <span>Our Locations</span>
                <ul>
                    <?php
                    foreach((array)$ourLocationsList as $ourLocation)
                    {
                        echo '<li><a href="'.get_permalink($ourLocation->ID).'">'.$ourLocation->post_title.'</a></li>';
                    }
                    ?>
                </ul>
            </div>
        </div>
            <?php
        }
        ?>
    </section>
    
    <section class="row footer-website-links hide-for-small-only">
        <h3 class="uppercase pp22ugroundbook">The London Dry Cleaning Company</h3>
        <?php 
        $websiteLinks = get_field('website_links', 'options');
        if(count($websiteLinks))
        {
            $chunkedLinks = columnize($websiteLinks, 3, 2);
            ?>
        <div class="row">
            <div class="small-12 medium-4 large-4 columns left first-column">
                <ul>
                    <?php
                    foreach((array)$chunkedLinks[0] as $linksOne)
                    {
                        echo '<li><a href="'.get_permalink($linksOne->ID).'">'.$linksOne->post_title.'</a></li>';
                    }
                    ?>
                </ul>
            </div>
            <div class="small-12 medium-4 large-4 columns left second-column">
                <ul>
                    <?php
                    foreach((array)$chunkedLinks[1] as $linksTwo)
                    {
                        echo '<li><a href="'.get_permalink($linksTwo->ID).'">'.$linksTwo->post_title.'</a></li>';
                    }
                    ?>
                </ul>
            </div>
            <div class="small-12 medium-4 large-4 columns left third-column">
                <ul>
                    <?php
                    foreach((array)$chunkedLinks[2] as $linksThree)
                    {
                        echo '<li><a href="'.get_permalink($linksThree->ID).'">'.$linksThree->post_title.'</a></li>';
                    }
                    ?>
                </ul>
            </div>
        </div>
            <?php
        }
        ?>
    </section>

     */
    ?>
    <section class="row footer-sitemap">
        <?php wp_nav_menu( array('menu' => 'Footer Sitemap' )); ?>
    </section>
    <section class="row footer-bottom">
        <div class="large-3 columns text-left">
            <p class="purplefont">&copy; <?php echo date("Y"); ?> <a href="<?php bloginfo('url'); ?>">London Dry Cleaning Company</a></p>
        </div>
        <div class="large-5 columns text-center">
            <ul class="menu-bottom-links">
                <li><a href="<?php echo get_permalink(28); ?>">Site Map</a></li>
                <li><a href="<?php echo get_permalink(30); ?>">Privacy Policy</a></li>
                <li><a href="<?php echo get_permalink(32); ?>">Disclaimer</a></li>
            </ul>
        </div>
        <div class="large-4 columns text-right">
            <p><a target="_blank" href="http://www.futuraservices.co.uk/">Web Design London</a> By Futura</p>
        </div>
    </section>
</footer>

</div><!--wrap-->

<?php wp_footer(); ?>

<script src="<?php bloginfo('template_url'); ?>/bower_components/jquery/dist/jquery.min.js" type="text/javascript"></script>
<script src="<?php bloginfo('template_url'); ?>/js/select.js" type="text/javascript"></script>
<script src="<?php bloginfo('template_url'); ?>/bower_components/foundation/js/foundation.js" type="text/javascript"></script>
<script src="<?php bloginfo('template_url'); ?>/bower_components/foundation/js/foundation/foundation.reveal.js"></script>

<script>
    $(document).foundation();
</script>

<script type="text/javascript" src="<?php bloginfo('template_url'); ?>/js/ldcc.js"></script>
<script src="<?php bloginfo('template_url'); ?>/js/wow.js" type="text/javascript"></script>

<?php
if(!is_user_logged_in())
{
    require_once TEMPLATEPATH .'/includes/partials/login-section-modal.php';
}
?>

<?php
if(isset($_GET['collection-date']) || isset($_GET['dropoff-date']))
{
    foreach ($_GET as $key => $value) 
    {
        switch($key)
        {
            case 'collection-date':
                $targetAnchor = 'collection';
            break;
            case 'dropoff-date':
                $targetAnchor = 'dropoff';
            break;
        }
    }
    
    if(!empty($targetAnchor))
    {
        echo '<div id="'.$targetAnchor.'-scroll"></div>';
        ?>
        <script type="text/javascript">   
            $(window).bind("load", function()
            {
                $('#<?php echo $targetAnchor; ?>-scroll').trigger('click');     
             });
        </script>
        <?php
    }
}
?>
        
<?php
if($validationResponse == true)
{
    echo '<div id="validation-scroll"></div>';
    ?>
        <script type="text/javascript">   
            $(window).bind("load", function()
            {
                $('#validation-scroll').trigger('click');     
             });
        </script>
    <?php
}
?>
        
</body>
</html>