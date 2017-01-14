<?php 
if ( !defined( 'WPINC' ) ) { die; }

if ('is_admin' ) {
  
  /**
   * Calls the class on the post edit screen.
   */
  function call_cameraSettings() {
      new cameraSettings();
  }
   
  if ( is_admin() ) {
      add_action( 'load-post.php',     'call_cameraSettings' );
      add_action( 'load-post-new.php', 'call_cameraSettings' );
  }
   
  /**
   * The Class.
   */
  class cameraSettings {
   
      /**
       * Hook into the appropriate actions when the class is constructed.
       */
      public function __construct() {
          add_action( 'add_meta_boxes', array( $this, 'add_meta_box' ) );
          add_action( 'save_post',      array( $this, 'save'         ) );
      }
   
      /**
       * Adds the meta box container.
       */
      public function add_meta_box( $post_type ) {
          // Limit meta box to certain post types.
          $post_types = array( 'eazy-photo' );
   
          if ( in_array( $post_type, $post_types ) ) {
              add_meta_box(
                  'eazy_camera_settings',
                  __( 'Camera Settings', 'eazy-photography' ),
                  array( $this, 'render_camera_settings_meta_box' ),
                  $post_type,
                  'advanced',
                  'high'
              );
          }
      }
   
      /**
       * Save the meta when the post is saved.
       *
       * @param int $post_id The ID of the post being saved.
       */
      public function save( $post_id ) {
   
          /*
           * We need to verify this came from the our screen and with proper authorization,
           * because save_post can be triggered at other times.
           */
   
          // Check if our nonce is set.
          if ( ! isset( $_POST['eazy_photography_inner_custom_box_nonce'] ) ) {
              return $post_id;
          }
   
          $nonce = $_POST['eazy_photography_inner_custom_box_nonce'];
   
          // Verify that the nonce is valid.
          if ( ! wp_verify_nonce( $nonce, 'eazy_photography_inner_custom_box' ) ) {
              return $post_id;
          }
   
          /*
           * If this is an autosave, our form has not been submitted,
           * so we don't want to do anything.
           */
          if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
              return $post_id;
          }
   
          // Check the user's permissions.
          if ( 'page' == $_POST['post_type'] ) {
              if ( ! current_user_can( 'edit_page', $post_id ) ) {
                  return $post_id;
              }
          } else {
              if ( ! current_user_can( 'edit_post', $post_id ) ) {
                  return $post_id;
              }
          }
   
          /* OK, it's safe for us to save the data now. */
          // Sanitize the user input.
          $aperture =  $_POST['eazy_camera_settings_aperture'] ;
          $shutter_speed =  $_POST['eazy_camera_settings_shutter_speed'] ;
          $iso =  sanitize_text_field( $_POST['eazy_camera_settings_iso'] );
          $cameramodel = sanitize_text_field( $_POST['eazy_camera_settings_camera'] );
          $focal_length = sanitize_text_field( $_POST['eazy_camera_settings_focal_length'] );

          // These come from metadata / exif
          $exif_av = eazy_photo_exif_info_get_value('aperture');
          $exif_tv = eazy_photo_exif_info_get_value('shutter_speed');
          $exif_iso = eazy_photo_exif_info_get_value('iso');
          $exif_camera = eazy_photo_exif_info_get_value('camera');
          $exif_focal_length = eazy_photo_exif_info_get_value('focal_length');

          // all shutter speeds to check if value is in these values
          $all_tv = all_shutter_speed_vals();

          //empty array to  put values in
          $expvalarray = array();

          //set ID to post ID
          $expvalarray['id'] = $post_id;

          //  if exif data has a aperture value, use that to set post meta, else use select option
          if ($exif_av != NULL ) {
            $expvalarray['aperture'] =  $exif_av; 
          }elseif ($aperture != NULL) {
            $expvalarray['aperture'] =  $aperture;
          }else {
            $expvalarray['aperture'] = NULL;
          }

          //   if exif data has a shuter speed value, use that to set post meta, else use select option
          if ($exif_tv != NULL ) {
            if ( in_array($exif_tv, $all_tv) ) {
              $expvalarray['shutter_speed'] =  $exif_tv; 
            } else {
              $updated_tv = get_closest_shutter_speed($exif_tv);
              $expvalarray['shutter_speed'] =  $updated_tv; 
            } 
          }elseif ($shutter_speed != NULL) {
            $expvalarray['shutter_speed'] =  $shutter_speed; 
          }else {
            $expvalarray['shutter_speed'] = NULL;
          }

          // if exif data has a ISO value, use that to set post meta, else use text field option
          if ($exif_iso != NULL) {
            $expvalarray['iso'] =  $exif_iso; 
          }elseif ($iso != NULL ) {
            $expvalarray['iso'] =  $iso; 
          }else {
            $expvalarray['iso'] = NULL;
          }

          // if exif data has a camera model, use that to set post meta, else use text field option
          if ($exif_camera != NULL) {
            $expvalarray['camera'] =  $exif_camera; 
          }elseif ($cameramodel != NULL) {
            $expvalarray['camera'] =  $cameramodel; 
          }else {
            $expvalarray['camera'] = NULL;
          }      

          // if exif data has a focal length, use that to set post meta, else use text field option
          if ($exif_focal_length != NULL) {
            $expvalarray['focal_length'] =  $exif_focal_length; 
          }elseif ($focal_length != NULL) {
            $expvalarray['focal_length'] =  $focal_length; 
          }else {
            $expvalarray['focal_length'] = NULL;
          }

          // add exposure properties and values to DB
          eazy_photo_add_to_db($post_id, $expvalarray);
      }
   
   
      /**
       * Render Meta Box content.
       *
       * @param WP_Post $post The post object.
       */
      public function render_camera_settings_meta_box( $post ) {
          // Add a nonce field.
          wp_nonce_field( 'eazy_photography_inner_custom_box', 'eazy_photography_inner_custom_box_nonce' );
   

          $thisphoto = get_photo_meta(get_the_ID());


          // Use get_post_meta to retrieve an existing value from the database.
          $aperture_value = $thisphoto['aperture'];
          $shutter_speed_value = $thisphoto['shutter_speed'];
          $iso_value = $thisphoto['iso'];
          $camera_value = $thisphoto['camera'];
          $focal_length_value = $thisphoto['focal_length'];
          
          // Display the form, using the current value. ?>
          <div class="camera-setting">
              <label for="eazy_camera_settings_aperture" class="camera-setting-label">
                  <?php _e( 'Aperture', 'eazy-photography' ); ?>
              </label>
              <?php get_aperture_values($aperture_value); ?>
          </div>
          <div class="camera-setting">
              <label for="eazy_camera_settings_shutter_speed" class="camera-setting-label">
                  <?php _e( 'Shutter speed', 'eazy-photography' ); ?>
              </label>
              <?php get_shutter_speed_values($shutter_speed_value); ?>
          </div>
          <div class="camera-setting">        
              <label for="eazy_camera_settings_iso" class="camera-setting-label">
                  <?php _e( 'ISO', 'eazy-photography' ); ?>
              </label>
              <input type="text" id="eazy_camera_settings_iso" name="eazy_camera_settings_iso" value="<?php if ($iso_value != NULL) { echo esc_attr( $iso_value ); } ?>" class="camera-setting-input" size="25" />
          </div>
          <div class="camera-setting">        
              <label for="eazy_camera_settings_camera" class="camera-setting-label">
                  <?php _e( 'Camera', 'eazy-photography' ); ?>
              </label>
              <input type="text" id="eazy_camera_settings_camera" name="eazy_camera_settings_camera" value="<?php if ($camera_value != NULL) { echo esc_attr( $camera_value ); } ?>" class="camera-setting-input" size="25" />
          </div>          
          <div class="camera-setting">        
              <label for="eazy_camera_settings_focal_length" class="camera-setting-label">
                  <?php _e( 'Focal Length', 'eazy-photography' ); ?>
              </label>
              <input type="text" id="eazy_camera_settings_focal_length" name="eazy_camera_settings_focal_length" value="<?php if ($focal_length_value != NULL) { echo esc_attr( $focal_length_value ); }?>" class="camera-setting-input" size="25" />
          </div>
          <p>These fields will auto-populate on publish if your featured image has exif metadata. </p> 
          <?php
      }
  }
}