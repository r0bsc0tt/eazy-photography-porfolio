<?php
// if camera settings turned on, create widget for exposure settings
if (get_option('eazy-photo-settings-camera') == "on") {
  // register and load the widget
  add_action( 'widgets_init', 'eazy_photo_load_widget_camera_settings' );
  function eazy_photo_load_widget_camera_settings() {
    register_widget( 'eazy_photography_widget_exposure' );
  }
}


// Creating the widget 
class eazy_photography_widget_exposure extends WP_Widget {
  function __construct() {
    parent::__construct(
    // Base ID of your widget
    'eazy_photography_widget_exposure', 

    // Widget name will appear in UI
    __('Eazy Photo - Exposure Settings', 'eazy-photography'), 

    // Widget description
    array( 'description' => __( 'Options to display exposure settings in sidebar', 'eazy-photography' ), ) 
    );
  }

  // Widget front-end
  public function widget( $args, $instance ) {
    $display_lcdscreen = $instance[ 'eazy_photo_lcd' ] ? 'true' : 'false';
    echo $args['before_widget'];


      $aperture = eazy_photo_aperture();
      $shutter_speed = eazy_photo_shutter_speed();
      $iso = eazy_photo_iso();
      if ($instance['eazy_photo_lcd'] == 'on') {
        get_camera_lcd_exposure($aperture, $shutter_speed, $iso);
      }else {
        get_camera_non_lcd_exposure($aperture, $shutter_speed, $iso);
      }
      


    echo $args['after_widget'];
  }
      
  // Widget Backend 
  public function form( $instance ) {?>
    <p>
        <input class="checkbox" type="checkbox"  id="<?php echo $this->get_field_id( 'eazy_photo_lcd' ); ?>" name="<?php echo $this->get_field_name( 'eazy_photo_lcd' ); ?>" value="on" <?php checked('on', $instance['eazy_photo_lcd'], true); ?> /> 
        <label for="<?php echo $this->get_field_id( 'eazy_photo_lcd' ); ?>">On</label>
    </p>
    <p>If checked, exposure settings will display like a camera's top LCD screen, otherwise it will use icons.</p>
    <img src="<?php echo EZ_PLUGIN_URL . 'includes/css/sample_exp.png'; ?>"><?php

  }
  
  // Updating widget replacing old instances with new
  public function update( $new_instance, $old_instance ) {
    $instance = array();
    $instance['eazy_photo_lcd'] = $new_instance[ 'eazy_photo_lcd' ];
    return $instance;
  }
} // end eazy_photography_widget_exposure

