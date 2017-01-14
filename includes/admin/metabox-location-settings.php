<?php 
if ( !defined( 'WPINC' ) ) { die; }

if ('is_admin' ) {

	/**
	 * Calls the class on the post edit screen.
	 */
	function call_cameraLocation() {
	    new cameraLocation();
	}
	 
	if ( is_admin() ) {
	    add_action( 'load-post.php',     'call_cameraLocation' );
	    add_action( 'load-post-new.php', 'call_cameraLocation' );
	}
	 
	/**
	 * The Class.
	 */
	class cameraLocation {
	 
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
	                'eazy_camera_location',
	                __( 'Photo Location', 'eazy-photography' ),
	                array( $this, 'render_camera_location_meta_box' ),
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
	        if ( ! isset( $_POST['eazy_photography_inner_custom_box_location_location_nonce'] ) ) {
	            return $post_id;
	        }
	 
	        $nonce = $_POST['eazy_photography_inner_custom_box_location_location_nonce'];
	 
	        // Verify that the nonce is valid.
	        if ( ! wp_verify_nonce( $nonce, 'eazy_photography_inner_custom_box_location' ) ) {
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
	        $cameramaptype	 			= $_POST['eazy_camera_map_type'] ;
	        $cameralatitude 			= sanitize_text_field( $_POST['eazy_camera_latitude'] );
	        $cameralongitude 			= sanitize_text_field( $_POST['eazy_camera_longitude'] );
	        $cameralocationiframe = esc_url_raw( $_POST['eazy_camera_iframe'] );
	        // Update the meta field.
	        //update_post_meta( $post_id, '_eazy_camera_latitude', $cameralatitude );
	        //update_post_meta( $post_id, '_eazy_camera_longitude', $cameralongitude );
	        $locationarray = array();
	        
	        if ($cameramaptype != '') {
	        	$locationarray['map_type'] = $cameramaptype;
	        } else { 
	        	$locationarray['map_type'] = NULL; 
	        }
	        
	        if ($cameralatitude != NULL ) {
	        	$locationarray['latitude'] = $cameralatitude;
	        } else { 
	        	$locationarray['latitude'] = NULL; 
	        }

	        if ($cameralongitude != NULL ) {
	        	$locationarray['longitude'] = $cameralongitude;
	        } else { 
	        	$locationarray['longitude'] = NULL; 
	        }

	        if ($cameralocationiframe != NULL || '') {
	        	$locationarray['iframe'] = $cameralocationiframe;
	        } else { 
	        	$locationarray['iframe'] = NULL; 
	        }


	        eazy_photo_add_location_to_db($post_id, $locationarray);
	    }
	 
	 
	    /**
	     * Render Meta Box content.
	     *
	     * @param WP_Post $post The post object.
	     */
	    public function render_camera_location_meta_box( $post ) {
	        // Add a nonce field.
	        wp_nonce_field( 'eazy_photography_inner_custom_box_location', 'eazy_photography_inner_custom_box_location_location_nonce' );
	 

	        $thisphoto = get_photo_meta(get_the_ID());
	        // Use get_post_meta to retrieve an existing value from the database.
	        $cameramap_type 				= $thisphoto['map_type'];
	        $cameralatitude_value 	= $thisphoto['latitude'];
	        $cameralongitude_value 	= $thisphoto['longitude'];
	        $cameraiframe_value 		= $thisphoto['iframe'];

	        // Display the form, using the current value. ?>

	      	<div class="map-type-setting">
		        <label for="eazy_camera_map_type" class="camera-setting-label">
		            <?php _e( 'Map Type: ', 'eazy-photography' ); ?>
		        </label>
		        <select id="eazy_camera_map_type" name="eazy_camera_map_type">
						  <option value=""  ></option>
						  <option value="javascript" <?php selected( $cameramap_type, 'javascript' ); ?> >javascript</option>
						  <option value="iframe" <?php selected( $cameramap_type, 'iframe' ); ?> >iframe</option>
						</select>
	        </div>
	   			<div  id="javascript-settings">   		
		      	<div class="location-setting">
			        <label for="eazy_camera_latitude" class="camera-setting-label">
			            <?php _e( 'Latitude: ', 'eazy-photography' ); ?>
			        </label>
			        <input type="text" pattern="[-+]?[0-9]*[.][0-9]*"  id="eazy_camera_latitude" name="eazy_camera_latitude" value="<?php echo esc_attr( $cameralatitude_value ); ?>"  size="25"/>
		        </div>

		        <div class="location-setting">
			        <label for="eazy_camera_longitude" class="camera-setting-label">
			            <?php _e( 'Longitude: ', 'eazy-photography' ); ?>
			        </label>
			        <input type="text" pattern="[-+]?[0-9]*[.][0-9]*"  id="eazy_camera_longitude" name="eazy_camera_longitude" value="<?php echo esc_attr( $cameralongitude_value ); ?>" size="25" />
		        </div>

		        <p><?php _e( 'Format should be something like: ', 'eazy-photography' ); ?> <code>+###.######</code> <?php _e( 'or ', 'eazy-photography' ); ?> <code>-08.1234598</code></p>
		        <p><a href="https://support.google.com/maps/answer/18539?co=GENIE.Platform%3DDesktop&hl=en" target="_blank">Click Here</a> for more information.</p>
	    		</div>
	    		<div id="iframe-settings">   
		        <label for="eazy_camera_iframe" class="camera-setting-label">
		            <?php _e( 'iframe url: ', 'eazy-photography' ); ?>
		        </label>
		       	<input type="url" pattern="[@^(https?|ftp)://[^\s/$.?#].[^\s]*$@iS]" id="eazy_camera_iframe" name="eazy_camera_iframe" value="<?php echo esc_attr( $cameraiframe_value ); ?>"  />       	
	    		</div>
	        <?php
	    }
	}

}