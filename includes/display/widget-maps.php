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
    echo $args['before_widget'];
    eazy_photo_make_map();
    echo $args['after_widget'];
  }
      
  // Widget Backend 
  public function form( $instance ) { ?>
    <p><?php __('Will display location settings you can add to each photo. You need to have your API key added to the settings page for it to work.', 'galleria'); ?></p>
    <?php
  }
  
  // Updating widget replacing old instances with new
  public function update( $new_instance, $old_instance ) {
    $instance = array();
    $instance['map'] = ( ! empty( $new_instance['map'] ) ) ? strip_tags( $new_instance['map'] ) : '';
    return $instance;
  }
} // end eazy_photography_widget_google_maps

