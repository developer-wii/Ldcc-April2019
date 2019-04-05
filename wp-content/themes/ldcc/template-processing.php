<?php
/**
 * Template Name: Process
 */

error_reporting(E_ALL);
ini_set('display_errors', 1);

if(is_user_logged_in())
{
    wp_redirect(get_permalink(6));
    exit;
}
else
{
    require_once  TEMPLATEPATH .'/classes/customWooCheckout.php';
    $customWooCheckout = new customWooCheckout();
}

  get_header();
?>

<?php 
 $posted = $customWooCheckout->getPostedDataFromCheckout();
 if(!is_user_logged_in())
 {
     ?>

<section class="content page-standard" id="content" role="content">
    <div class="row">
        <div class="small-12 medium-12 large-12 processing-screen relative">            
            <h1>Processing, Please wait</h1>
            <div class="process-loader"><i class="fa fa-refresh fa-spin fa-3x fa-fw"></i></div>
            <p>Please wait while we redirect you to our order review screen.</p>
        </div>
    </div>
</section>

            <form name="redirectData" id="redirectData" method="post" action="<?php echo get_permalink(6); ?>" style="display:none;">
                <?php
                if ( !is_null($posted) ) {
                    foreach ($posted as $k => $v) {
                        echo '<input type="hidden" name="' . $k . '" value="' . $v . '"> ';
                    }
                }
                ?>
            </form>
            <script type="text/javascript">
                    //document.forms["redirectData"].submit();
               //document.getElementById('redirectData').submit();
               jQuery(document).ready(function(){
                    window.onload = function(){ 
                        window.setTimeout(document.redirectData.submit.bind(document.redirectData), 3000);
                    };
                });
            </script>
     <?php
 }
?>

<?php
  get_footer();
?>
