<?php 
if ( !defined( 'WPINC' ) ) { die; }

if ('is_admin' ) {
  
  //adds the admin settings
  require_once(EZ_PLUGIN_PATH . 'includes/admin/admin-settings.php');
  //adds functions to create the display for the eazy-photo admin
  require_once(EZ_PLUGIN_PATH . 'includes/admin/admin-display.php');
  //adds image to photo category
  require_once(EZ_PLUGIN_PATH . 'includes/admin/admin-category-image.php');
  // when the exposure settings option is set to on include the settings
  if (get_option('eazy-photo-settings-camera') == "on") {
    //adds the exposure settings metabox to photo edit screen
    require_once(EZ_PLUGIN_PATH . 'includes/admin/metabox-exposure-settings.php');
    require_once(EZ_PLUGIN_PATH . 'includes/admin/metabox-exposure-html.php');
  }
  // when the google maps option is set to on include the settings
  if (get_option('eazy-photo-settings-maps') == "on") {
    //adds the location settings metabox to photo edit screen
    require_once(EZ_PLUGIN_PATH . 'includes/admin/metabox-location-settings.php');
    require_once(EZ_PLUGIN_PATH . 'includes/admin/metabox-exposure-html.php');
  }



  add_action( 'admin_enqueue_scripts', 'eazy_photography_admin_styles_scripts');
  function eazy_photography_admin_styles_scripts( $hook_suffix ){
      $cpt = 'eazy-photo';

      if( in_array($hook_suffix, array('post.php', 'post-new.php', 'edit.php') ) ){
          $screen = get_current_screen();

          if( is_object( $screen ) && $cpt == $screen->post_type ){

              //add admin scripts
              wp_register_script('eazy-photography-admin-js', plugins_url( 'js/admin-scripts.js', __FILE__ ), array('jquery'), '', true   );
              wp_enqueue_script('eazy-photography-admin-js');

              //add admin styles
              wp_register_style('eazy-photography-admin-css', plugins_url( 'css/admin-style.css', __FILE__ ), false, '1.0', false );
              wp_enqueue_style('eazy-photography-admin-css');

          }
      }
  }

  

}