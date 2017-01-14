<?php
// if camera settings turned on, create widget for maps
if (get_option('eazy-photo-settings-maps') == "on") {
  // register and load the widget
  add_action( 'widgets_init', 'eazy_photo_load_widget_maps' );
  function eazy_photo_load_widget_maps() {
    register_widget( 'eazy_photography_widget_google_maps' );
  }
}


// Creating the widget 
class eazy_photography_widget_google_maps extends WP_Widget {
  function __construct() {
    parent::__construct(
    // Base ID of your widget
    'eazy_photography_widget_google_maps', 

    // Widget name will appear in UI
    __('Eazy Photo - Maps', 'eazy-photography'), 

    // Widget description
    array( 'description' => __( 'Options to display Google Maps widget in sidebar', 'eazy-photography' ), ) 
    );
  }

  // Widget front-end
  public function widget( $args, $instance ) {
    $map_type = eazy_photo_get_map_type();
    echo $args['before_widget'];
      if ($map_type == 'iframe') {
        eazy_photo_make_map_iframe();
      } elseif ($map_type == 'javascript') {
        eazy_photo_make_map();
      } else {}
    echo $args['after_widget'];

  }
      
  // Widget Backend 
  public function form( $instance ) { ?>
    <p><b>Display photo's location on a map.</b></p>
    <p>You can control the map settins on the photo's admin page. </p>
    <p>If using the javascript map, make sure you set your API key on the plugin settings page. </p>
    <?php
  }
  
  // Updating widget replacing old instances with new
  public function update( $new_instance, $old_instance ) {
    $instance = array();
    $instance['map'] = ( ! empty( $new_instance['map'] ) ) ? strip_tags( $new_instance['map'] ) : '';
    return $instance;
/*
    $instance = array();
    $instance['eazy_photo_iframe'] = $new_instance[ 'eazy_photo_iframe' ];
    return $instance;
*/
  }
} // end eazy_photography_widget_google_maps

