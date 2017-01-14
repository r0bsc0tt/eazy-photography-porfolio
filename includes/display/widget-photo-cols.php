<?php

  // register and load the widget
  add_action( 'widgets_init', 'eazy_photo_load_widget_cols' );
  function eazy_photo_load_widget_cols() {
    register_widget( 'eazy_photography_widget_photo_cols' );
  }



// Creating the widget 
class eazy_photography_widget_photo_cols extends WP_Widget {
  function __construct() {
    parent::__construct(
    // Base ID of your widget
    'eazy_photography_widget_photo_cols', 

    // Widget name will appear in UI
    __('Eazy Photo - Collections', 'eazy-photography'), 

    // Widget description
    array( 'description' => __( 'Display photo collections as a list.', 'eazy-photography' ), ) 
    );
  }

  // Widget front-end
  public function widget( $args, $instance ) {
    $photo = get_post();
    echo $args['before_widget'];
       if ( get_the_terms( $photo, 'photo-collection' ) != '') { ?>
          <p>Collections</p>
          <ul class="eazy-photo-links"><?php eazy_photo_get_all_collections_links( $photo ); ?></ul> 
      <?php }
    echo $args['after_widget'];

  }
      
  // Widget Backend 
  public function form( $instance ) { ?>
    <p><b>This widget displays a list of the photo's collections.</b></p>
    <?php
  }
  
  // Updating widget replacing old instances with new
  public function update( $new_instance, $old_instance ) {
    $instance = array();
    $instance['cols'] = ( ! empty( $new_instance['cols'] ) ) ? strip_tags( $new_instance['cols'] ) : '';
    return $instance;

  }
} // end eazy_photography_widget_photo_cols

