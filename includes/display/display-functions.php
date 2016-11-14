<?php 
if ( !defined( 'WPINC' ) ) { die; }

    /** 
    * Returns the current aperture value. Should be used inside of a loop.   
    * @return string Returns the current aperture value.
    **/
    function eazy_photo_aperture() {
      if ( get_photo_meta(get_the_ID())['aperture'] !== NULL || 0 ) {

        return get_photo_meta(get_the_ID())['aperture'];
      }
    }

    /** 
    * Returns the current shutter speed value. Should be used inside of a loop.   
    * @return string Returns the current shutter speed value.
    **/
    function eazy_photo_shutter_speed() {
      if ( get_photo_meta(get_the_ID())['shutter_speed'] !== NULL || 0 ) {
        return get_photo_meta(get_the_ID())['shutter_speed'];
      }   
    }

    /** 
    * Returns the current ISO value. Should be used inside of a loop.   
    * @return string Returns the current ISO value.
    **/    
    function eazy_photo_iso() {
      if ( get_photo_meta(get_the_ID())['iso'] !== NULL || 0 ) {
        return get_photo_meta(get_the_ID())['iso'];
      }   
    }

    /** 
    * Returns the current focal length value. Should be used inside of a loop.   
    * @return string Returns the current focal length value.
    **/    
    function eazy_photo_focal_length() {
      if ( get_photo_meta(get_the_ID())['focal_length'] !== NULL || 0 ) {
        return get_photo_meta(get_the_ID())['focal_length'];
      }   
    }

    /** 
    * Returns the current camera model value. Should be used inside of a loop.   
    * @return string Returns the current camera model value.
    **/    
    function eazy_photo_camera_model() {
      if ( get_photo_meta(get_the_ID())['camera'] !== NULL || 0 ) {
        return get_photo_meta(get_the_ID())['camera'];
      }   
    }    

    /** 
    * Returns the current latitude value. Should be used inside of a loop.   
    * @return string Returns the current ISO value.
    **/  
    function eazy_photo_get_latitude() {
      if ( get_photo_meta(get_the_ID())['latitude'] !== NULL || 0) {
        return get_photo_meta(get_the_ID())['latitude'];
      }
    }

    /** 
    * Returns the current longitude value. Should be used inside of a loop.   
    * @return string Returns the current longitude value.
    **/ 
    function eazy_photo_get_longitude() {
      if ( get_photo_meta(get_the_ID())['longitude'] !== NULL || 0) {
        return get_photo_meta(get_the_ID())['longitude'];
      }
    }

    /** 
    * Formats the shutter speed value from decimal notation to fractions of a second.  
    * 
    * @param string $ssval Takes in a value of the current shutter speed.
    *
    * @return string Returns the value for the shutter speed formatted as 1/xxx or x".
    **/
    function clean_shutter_speed_value( $ssval ) {
      $all_shutter_speed_vals = all_shutter_speed_vals();

      $newvals = array();
      foreach ($all_shutter_speed_vals as $tv_array => $value) {
        $floatval = floatval($value[0]);
        if ( $ssval >= $floatval ) {
          $newvals[] = $value;
        }
      }
      $new_tv = end($newvals);
      return $new_tv[1];
    }

    /** 
    * Returns an array with aperture value, shutterspeed value & ISO value. Should be used inside of a loop.
    * @return array Returns an array with aperture value, shutterspeed value & ISO value.
    **/   
    function eazy_photo_exposure_array() {
      if (get_option('eazy-photo-settings-camera') == "on") {
        $exposure_settings = array();
        if (eazy_photo_aperture() !== NULL) {
          $exposure_settings['aperture'] = eazy_photo_aperture();
        }
        if (eazy_photo_shutter_speed() !== NULL) {
          $exposure_settings['shutter_speed'] = clean_shutter_speed_value(eazy_photo_shutter_speed());
        }
        if (eazy_photo_iso() !== NULL) {
          $exposure_settings['iso'] = eazy_photo_iso();
        } 
        return $exposure_settings;
      }
    }

    /** 
    * Aperture, shutter speed and ISO wrapped in a div & h4 tags
    **/ 
    function eazy_photo_exposure_html() { 
      if (get_option('eazy-photo-settings-camera') == "on") { ?>
        <div class="eazy-photo-exposure-settings">
          <h4>Aperture: <span>f/<?php echo eazy_photo_aperture(); ?></span></h4>
          <h4>Shutterspeed: <span><?php echo clean_shutter_speed_value(eazy_photo_shutter_speed()); ?></span></h4>
          <h4>ISO: <span><?php echo eazy_photo_iso(); ?></span></h4>
        </div> <?php
      }
    }



    /** 
    * Get all of the current photos category terms as links and echo them wrapped in <li> elements.
    * 
    * @param object $post The current post/photo.
    *
    **/    
    function eazy_photo_get_all_categories_links($post) {
      $categories = get_the_terms( $post, 'photo-category' );
      if ($categories != '') {
        foreach( $categories as $category ) {
          $category_link = get_term_link( $category );
          // Define the query
          $args = array(
            'post_type' => 'eazy-photo',
            'photo-category' => $category->slug
          );
          $query = new WP_Query( $args );
          echo '<li class="eazy-photo-list eazy-photo-category-list"><a href="' . esc_url( $category_link ) . '">' . $category->name . '</a></li>';
        }
      } else {
        echo '<li class="eazy-photo-list eazy-photo-category-list">Sorry, this photo doesn\'t have any categories.</li>';
      }
    } 

    /** 
    * Get all of the current photos collection terms as links and echo them wrapped in <li> elements.
    * 
    * @param object $post The current post/photo.
    *
    **/
    function eazy_photo_get_all_collections_links($post) {
      $collections = get_the_terms( $post, 'photo-collection' );
      if ( $collections != '' ) {
        foreach( $collections as $collection ) {
          $collection_link = get_term_link( $collection );
          // Define the query
          $args = array(
            'post_type' => 'eazy-photo',
            'photo-collection' => $collection->slug
          );
          $query = new WP_Query( $args );
          echo '<li class="eazy-photo-list eazy-photo-collection-list"><a href="' . esc_url( $collection_link ) . '">' . $collection->name . '</a></li>';
        }
      } else {
        echo '<li class="eazy-photo-list eazy-photo-collection-list">Sorry, this photo isn\'t in a collection.</li>';
      }
    }



// display exposure settings as LCD screen
function get_camera_lcd_exposure($aperture, $shutter_speed, $iso) { 
  if ( $aperture != '' || $shutter_speed != '' || $iso != '') { ?>
    <div class="eazy-photo-exposure-settings">
      <div class="settings-top-row">
        <div class="exp-mode sansserif">M</div>
        <h4 class="tv sansserif">
            <span class="value"><?php if ($shutter_speed != '') {echo clean_shutter_speed_value($shutter_speed);} else{ echo "--"; }?></span>
          </h4>
        <div class="white-balance sansserif">AWB</div>
      </div>
      <div class="settings-middle-row">
        <h4 class="iso sansserif">
        <span class="label">ISO</span>
        <span class="value"><?php if ($iso != '') {echo $iso;} else{ echo "--"; } ?></span>
      </h4>
        <h4 class="av sansserif">
        <span class="value"><?php if ($aperture != '') {echo $aperture;} else{ echo "--"; } ?></span>
      </h4>
        <div class="raw sansserif">RAW</div>
        <div class="meter-type">
          <?php get_meter_type_svg(); ?>
        </div>
      </div>
      <div class="settings-bottom-row">
        <div class="exposure-scale">
          <?php get_exposure_scale_svg(); ?>
        </div>
        <div class="battery-icon">
          <?php get_battery_icon_svg(); ?>
        </div>
      </div>
    </div>
  <?php }
}

// display exposure settings with icons
function get_camera_non_lcd_exposure($aperture, $shutter_speed, $iso) { 
  if ( $aperture != '' || $shutter_speed != '' || $iso != '') { ?>
    <div class="eazy-photo-simple-exposure-settings">

        <div id="av" class="simple-exp-setting">
          <?php get_aperture_icon(); ?>
          <div id="aperture-value" class="value">
            <h4><?php 
            if ($aperture !== '') {?> 
              <i class="ligature">f</i>/<?php echo $aperture; ?>
            <?php }else { echo "---";}
             ?></h4>
            <h4></h4>
          </div>
        </div><div id="tv" class="simple-exp-setting">
          <?php get_shutter_speed_icon(); ?>
          <div id="shutter_speed-value" class="value">
            <h4><?php 
            if ($shutter_speed !== '') {
              echo clean_shutter_speed_value($shutter_speed);
            }else { echo "---";}
             ?></h4>
          </div>
        </div><div id="iso" class="simple-exp-setting">
          <?php get_iso_icon(); ?>
          <div id="iso-value" class="value">
            <h4><?php 
            if ($iso !== '') {
              echo $iso;
            }else { echo "---";}
             ?></h4>
          </div>
        </div> 

    </div>
  <?php }
}