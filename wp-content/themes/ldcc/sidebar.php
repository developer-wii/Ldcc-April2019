<?php
  /**
  * only shown if widget sidebar not enabled
  */
?>

<?php if ( is_active_sidebar( 'general' ) ) : ?>
<?php dynamic_sidebar( 'general' ); ?>
<?php endif; ?>