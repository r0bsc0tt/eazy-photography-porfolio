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
          $aperture = sanitize_text_field( $_POST['eazy_camera_settings_aperture'] );
          $shutter_speed = sanitize_text_field( $_POST['eazy_camera_settings_shutter_speed'] );
          $iso = sanitize_text_field( $_POST['eazy_camera_settings_iso'] );
          $cameramodel = sanitize_text_field( $_POST['eazy_camera_settings_camera'] );
          $focal_length = sanitize_text_field( $_POST['eazy_camera_settings_focal_length'] );

          $exif_av = eazy_photo_exif_info_get_value('aperture');
          $exif_tv = eazy_photo_exif_info_get_value('shutter_speed');
          $exif_iso = eazy_photo_exif_info_get_value('iso');
          $exif_camera = eazy_photo_exif_info_get_value('camera');
          $exif_focal_length = eazy_photo_exif_info_get_value('focal_length');

          $all_tv = all_shutter_speed_vals();

          //  if exif data has a aperture value, use that to set post meta, else use select option
          if ($exif_av !== NULL ) {
              update_post_meta( $post_id, '_eazy_camera_settings_aperture', $exif_av );
          }else {
              update_post_meta( $post_id, '_eazy_camera_settings_aperture', $aperture );
          }

          //   if exif data has a shuter speed value, use that to set post meta, else use select option
          if ($exif_tv !== NULL ) {
            if ( in_array($exif_tv, $all_tv) ) {
              update_post_meta( $post_id, '_eazy_camera_settings_shutter_speed', $exif_tv );
            } else {
              $updated_tv = get_closest_shutter_speed($exif_tv);
              update_post_meta( $post_id, '_eazy_camera_settings_shutter_speed', $updated_tv);
            } 
          }else {
             update_post_meta( $post_id, '_eazy_camera_settings_shutter_speed', $shutter_speed );
          }

          // if exif data has a ISO value, use that to set post meta, else use text field option
          if ($exif_iso != NULL) {
             update_post_meta( $post_id, '_eazy_camera_settings_iso', $exif_iso );
          }else {
              update_post_meta( $post_id, '_eazy_camera_settings_iso', $iso );
          }

          // if exif data has a camera model, use that to set post meta, else use text field option
          if ($exif_camera != NULL) {
             update_post_meta( $post_id, '_eazy_camera_settings_camera', $exif_camera );
          }else {
              update_post_meta( $post_id, '_eazy_camera_settings_camera', $cameramodel );
          }      

          // if exif data has a focal length, use that to set post meta, else use text field option
          if ($exif_focal_length != NULL) {
             update_post_meta( $post_id, '_eazy_camera_settings_focal_length', $exif_focal_length );
          }else {
              update_post_meta( $post_id, '_eazy_camera_settings_focal_length', $focal_length );
          }    
      }
   
   
      /**
       * Render Meta Box content.
       *
       * @param WP_Post $post The post object.
       */
      public function render_camera_settings_meta_box( $post ) {
   
          // Add a nonce field.
          wp_nonce_field( 'eazy_photography_inner_custom_box', 'eazy_photography_inner_custom_box_nonce' );
   
          // Use get_post_meta to retrieve an existing value from the database.
          $aperture_value = get_post_meta( $post->ID, '_eazy_camera_settings_aperture', true );
          $shutter_speed_value = get_post_meta( $post->ID, '_eazy_camera_settings_shutter_speed', true );
          $iso_value = get_post_meta( $post->ID, '_eazy_camera_settings_iso', true );
          $camera_value = get_post_meta( $post->ID, '_eazy_camera_settings_camera', true );
          $focal_length_value = get_post_meta( $post->ID, '_eazy_camera_settings_focal_length', true );
          
          // Display the form, using the current value. ?>
          <div class="camera-setting">
              <label for="eazy_camera_settings_aperture" class="camera-setting-label">
                  <?php _e( 'Aperture', 'textdomain' ); ?>
              </label>
              <?php get_aperture_values($aperture_value); ?>
          </div>
          <div class="camera-setting">
              <label for="eazy_camera_settings_shutter_speed" class="camera-setting-label">
                  <?php _e( 'Shutter speed', 'textdomain' ); ?>
              </label>
              <?php get_shutter_speed_values($shutter_speed_value); ?>
          </div>
          <div class="camera-setting">        
              <label for="eazy_camera_settings_iso" style="<?php echo $iso_value; ?>" class="camera-setting-label">
                  <?php _e( 'ISO', 'textdomain' ); ?>
              </label>
              <input type="text" id="eazy_camera_settings_iso" name="eazy_camera_settings_iso" value="<?php echo esc_attr( $iso_value ); ?>" class="camera-setting-input" size="25" />
          </div>
          <div class="camera-setting">        
              <label for="eazy_camera_settings_camera" style="<?php echo $camera_value; ?>" class="camera-setting-label">
                  <?php _e( 'Camera', 'textdomain' ); ?>
              </label>
              <input type="text" id="eazy_camera_settings_camera" name="eazy_camera_settings_camera" value="<?php echo esc_attr( $camera_value ); ?>" class="camera-setting-input" size="25" />
          </div>          
          <div class="camera-setting">        
              <label for="eazy_camera_settings_focal_length" style="<?php echo $focal_length_value; ?>" class="camera-setting-label">
                  <?php _e( 'Focal Length', 'textdomain' ); ?>
              </label>
              <input type="text" id="eazy_camera_settings_focal_length" name="eazy_camera_settings_focal_length" value="<?php echo esc_attr( $focal_length_value ); ?>" class="camera-setting-input" size="25" />
          </div>
          <p>These fields will auto-populate on publish if your featured image has exif metadata. </p> 
          <?php
      }
  }
}