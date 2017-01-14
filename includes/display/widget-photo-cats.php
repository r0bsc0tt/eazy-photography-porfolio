<?php

  // register and load the widget
  add_action( 'widgets_init', 'eazy_photo_load_widget_cats' );
  function eazy_photo_load_widget_cats() {
    register_widget( 'eazy_photography_widget_photo_cats' );
  }



// Creating the widget 
class eazy_photography_widget_photo_cats extends WP_Widget {
  function __construct() {
    parent::__construct(
    // Base ID of your widget
    'eazy_photography_widget_photo_cats', 

    // Widget name will appear in UI
    __('Eazy Photo - Categories', 'eazy-photography'), 

    // Widget description
    array( 'description' => __( 'Display photo categories as a list.', 'eazy-photography' ), ) 
    );
  }

  // Widget front-end
  public function widget( $args, $instance ) {
    $photo = get_post();
    echo $args['before_widget'];
      if ( get_the_terms( $photo, 'photo-category' ) != '') { ?>
          <p>Categories</p>
          <ul class="eazy-photo-links"><?php eazy_photo_get_all_categories_links( $photo ); ?></ul> 
      <?php }
    echo $args['after_widget'];

  }
      
  // Widget Backend 
  public function form( $instance ) { ?>
    <p><b>This widget displays a list of the photo's categories.</b></p>
    <?php
  }
  
  // Updating widget replacing old instances with new
  public function update( $new_instance, $old_instance ) {
    $instance = array();
    $instance['cats'] = ( ! empty( $new_instance['cats'] ) ) ? strip_tags( $new_instance['cats'] ) : '';
    return $instance;

  }
} // end eazy_photography_widget_photo_cats

