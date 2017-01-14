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

    //adds functions to create display elements
    require_once(EZ_PLUGIN_PATH . 'includes/display/display-photo-grid.php');

    //adds functions to create display elements
    require_once(EZ_PLUGIN_PATH . 'includes/display/display-single-photo.php');

    //add widgets for photo categoriess and collections
    require_once(EZ_PLUGIN_PATH . 'includes/display/widget-photo-cats.php');
    require_once(EZ_PLUGIN_PATH . 'includes/display/widget-photo-cols.php');

    //add homepage flexbox display  
    require_once(EZ_PLUGIN_PATH . 'includes/display/display-photo-homepage-flex.php');


  add_action( 'wp_enqueue_scripts', 'eazy_photography_styles_scripts' );    
  function eazy_photography_styles_scripts(){
    wp_register_style('eazy-photography-css', plugins_url( 'eazy-photography-portfolio/includes/css/style.css' ) );
    wp_enqueue_style('eazy-photography-css');
  
    wp_register_script( 'eazy-photo-scripts', plugins_url( 'eazy-photography-portfolio/includes/js/scripts.js' ), array(), '', true );
    wp_enqueue_script( 'eazy-photo-scripts' );
  
  } 

  //add image sizes
  add_image_size( 'eazy-photo-thumb-l', 825, 825, false );
  add_image_size( 'eazy-photo-thumb-m', 375, 375, false );
  add_image_size( 'eazy-photo-thumb-s', 150, 150, false );