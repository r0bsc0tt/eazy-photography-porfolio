<?php 
if ( !defined( 'WPINC' ) ) { die; }

  //adds functions to create display elements
  require_once(EZ_PLUGIN_PATH . 'includes/display/display-functions.php');

  // when the exposure settings option is set to on include the widget
  if (get_option('eazy-photo-settings-camera') == "on") {
    //adds exposure settings widget
    require_once(EZ_PLUGIN_PATH . 'includes/display/widget-exposure-settings.php');
    //adds functions to create display icons & svgs for exposure settings
    require_once(EZ_PLUGIN_PATH . 'includes/display/display-icons.php');
  }
  // when the exposure settings option is set to on include the widget
  if (get_option('eazy-photo-settings-maps') == "on") {
    //adds maps functions &  widget
    require_once(EZ_PLUGIN_PATH . 'includes/display/display-map.php');
    require_once(EZ_PLUGIN_PATH . 'includes/display/widget-maps.php');
  }


  add_action( 'wp_enqueue_scripts', 'eazy_photography_styles_scripts' );    
  function eazy_photography_styles_scripts(){
    wp_register_style('eazy-photography-css', plugins_url( 'eazy-photography-portfolio/includes/css/style.css' ) );
    wp_enqueue_style('eazy-photography-css');
  } 
