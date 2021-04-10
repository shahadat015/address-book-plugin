<div class="wrap">
   <?php settings_errors(); ?>

   <form method="post" action="options.php">
       <?php 
            settings_fields( 'ab_settings' ); 
            do_settings_sections( 'address-book' );
            submit_button();
        ?>
   </form>
</div>