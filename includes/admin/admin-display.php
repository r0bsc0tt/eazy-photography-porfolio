<?php 
if ( !defined( 'WPINC' ) ) { die; }

if ('is_admin' ) {

  //adds a column to the eazy photos admin archive page
  add_filter('manage_eazy-photo_posts_columns', 'eazy_photography_columns_head');
  function eazy_photography_columns_head($defaults) {
    $defaults['featured_image'] = 'Featured Image';
    return $defaults;
  }

  //gets featured image for photo
  function eazy_photography_admin_image($post_ID) {
    $post_thumbnail_id = get_post_thumbnail_id($post_ID);
    if ($post_thumbnail_id) {
        $post_thumbnail_img = wp_get_attachment_image_src($post_thumbnail_id, 'thumbnail');
        return $post_thumbnail_img[0];
    }
  }
   
  // adds featured image to the admin column 
  add_action('manage_posts_custom_column', 'eazy_photography_columns_content', 10, 2);
  function eazy_photography_columns_content($column_name, $post_ID) {
    if ($column_name == 'featured_image') {
      $post_featured_image = eazy_photography_admin_image($post_ID);
      if ($post_featured_image) {
        echo '<img src="' . $post_featured_image . '" />';
      }
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