<?php 
if ( !defined( 'WPINC' ) ) { die; }

if ('is_admin' ) {

// Create admin column names 
add_filter( 'manage_eazy-photo_posts_columns', 'eazy_photo_admin_column_names' ) ;
function eazy_photo_admin_column_names( $columns ) {

  $columns = array(
    'cb' => '<input type="checkbox" />',
    'title' => __( 'Title' ),
    'image' => __( 'Image' ),
    'aperture' => __( 'AV' ),
    'shutter_speed' => __( 'TV' ),
    'iso' => __( 'ISO' ),
    'focal_length' => __( 'Focal Length' ),    
    'camera_model' => __( 'Camera Model' ),
    'date' => __( 'Date' )
  );

  return $columns;
}

//register image size for admin column
add_image_size( 'eazy-photo-col-img', 75, 75, false );

add_action( 'manage_eazy-photo_posts_custom_column', 'my_manage_eazy_photo_columns', 10, 2 );
function my_manage_eazy_photo_columns( $column, $post_id ) {
  global $post;

  switch( $column ) {

    /* If displaying the 'image' column. */
    case 'image' :

        $post_thumbnail_id = get_post_thumbnail_id($post_id);
        if ($post_thumbnail_id) {
            $post_thumbnail_img = wp_get_attachment_image_src($post_thumbnail_id, 'eazy-photo-col-img');
            echo '<img src="' . $post_thumbnail_img[0] . '" />';
        }

      break;

    /* If displaying the 'aperture' column. */
    case 'aperture' :
      /* Get the aperture value for the post. */
      $aperture = eazy_photo_aperture();
      if ($aperture != NULL) {
        echo "<div class='eazy-photo-cols eazy-photo-cols-exp' id='admin-exp-av'><i>f</i>/".$aperture."</div>";
      }
      break;
    /* If displaying the 'shutter speed' column. */
    case 'shutter_speed' :
      /* Get the aperture value for the post. */
      $shutter_speed = eazy_photo_shutter_speed();
      if ($shutter_speed != NULL) {
        echo "<div class='eazy-photo-cols eazy-photo-cols-exp' id='admin-exp-tv'>".clean_shutter_speed_value($shutter_speed)."</div>";
      }
      break;
    /* If displaying the 'iso' column. */
    case 'iso' :
      /* Get the aperture value for the post. */
      $iso = eazy_photo_iso();
      if ($iso != NULL) {
        echo "<div class='eazy-photo-cols eazy-photo-cols-exp' id='admin-exp-iso'>".$iso."</div>";
      }
      break;
    /* If displaying the 'focal length' column. */
    case 'focal_length' :
      /* Get the aperture value for the post. */
      $focal_length = eazy_photo_focal_length();
      if ($focal_length != NULL) {
        echo "<div class='eazy-photo-cols' id='admin-exp-cam'>".$focal_length."mm</div>";
      }
      break;
    /* If displaying the 'camera model' column. */
    case 'camera_model' :
      /* Get the aperture value for the post. */
      $camera_model = eazy_photo_camera_model();
      if ($camera_model != NULL) {
        echo "<div class='eazy-photo-cols' id='admin-exp-cam'>".$camera_model."</div>";
      }
      break;
    /* Just break out of the switch statement for everything else. */
    default :
      break;
  }
}


  //adds settings page to photos menu
  add_action("admin_menu", "add_photography_settings_menu");  
  function add_photography_settings_menu() {
    add_submenu_page('edit.php?post_type=eazy-photo', 'Eazy Photography Portfolio Settings', 'Settings', 'edit_posts', 'eazy-photography-settings-', 'eazy_photography_settings_page');
  } 

  // remove featured image and re-add it so that it is in the advanced position
  add_action('do_meta_boxes', 'eazy_photo_image_box');
  function eazy_photo_image_box() {
    remove_meta_box( 'postimagediv', 'eazy-photo', 'side' );
    add_meta_box('postimagediv', __('Featured Image', 'eazy-photography'), 'post_thumbnail_meta_box', 'eazy-photo', 'advanced', 'high');
  }

  // Move all "advanced" metaboxes above the default editor
  // maybe should be conditionally ran on eazy-photo cpt
  add_action('edit_form_after_title', function() {
      global $post, $wp_meta_boxes;
      do_meta_boxes(get_current_screen(), 'advanced', $post);
      unset($wp_meta_boxes[get_post_type($post)]['advanced']);
  });

}