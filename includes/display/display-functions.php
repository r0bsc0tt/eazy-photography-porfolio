<?php 
if ( !defined( 'WPINC' ) ) { die; }

    /** 
    * Returns the current aperture value. Should be used inside of a loop.   
    * @return string Returns the current aperture value.
    **/
    function eazy_photo_aperture() {
      if ( get_photo_meta(get_the_ID())['aperture'] !== NULL ) {
        return get_photo_meta(get_the_ID())['aperture'];
      }
    }

    /** 
    * Returns the current shutter speed value. Should be used inside of a loop.   
    * @return string Returns the current shutter speed value.
    **/
    function eazy_photo_shutter_speed() {
      if ( get_photo_meta(get_the_ID())['shutter_speed'] !== NULL ) {
        return get_photo_meta(get_the_ID())['shutter_speed'];
      }   
    }

    /** 
    * Returns the current ISO value. Should be used inside of a loop.   
    * @return string Returns the current ISO value.
    **/    
    function eazy_photo_iso() {
      if ( get_photo_meta(get_the_ID())['iso'] !== NULL ) {
        return get_photo_meta(get_the_ID())['iso'];
      }   
    }

    /** 
    * Returns the current focal length value. Should be used inside of a loop.   
    * @return string Returns the current focal length value.
    **/    
    function eazy_photo_focal_length() {
      if ( get_photo_meta(get_the_ID())['focal_length'] !== NULL  ) {
        return get_photo_meta(get_the_ID())['focal_length'];
      }   
    }

    /** 
    * Returns the current camera model value. Should be used inside of a loop.   
    * @return string Returns the current camera model value.
    **/    
    function eazy_photo_camera_model() {
      if ( get_photo_meta(get_the_ID())['camera'] !== NULL  ) {
        return get_photo_meta(get_the_ID())['camera'];
      }   
    }    

    /** 
    * Returns the map type. Should be used inside of a loop.   
    * @return string Returns the current map type.
    **/ 
    function eazy_photo_get_map_type() {
      if ( get_photo_meta(get_the_ID())['map_type'] !== NULL ) {
        return get_photo_meta(get_the_ID())['map_type'];
      }
    }

    /** 
    * Returns the current latitude value. Should be used inside of a loop.   
    * @return string Returns the current ISO value.
    **/  
    function eazy_photo_get_latitude() {
      if ( get_photo_meta(get_the_ID())['latitude'] !== NULL ) {
        return get_photo_meta(get_the_ID())['latitude'];
      }
    }

    /** 
    * Returns the current longitude value. Should be used inside of a loop.   
    * @return string Returns the current longitude value.
    **/ 
    function eazy_photo_get_longitude() {
      if ( get_photo_meta(get_the_ID())['longitude'] !== NULL ) {
        return get_photo_meta(get_the_ID())['longitude'];
      }
    }

    /** 
    * Returns the iframe url. Should be used inside of a loop.   
    * @return string Returns the current iframe url.
    **/ 
    function eazy_photo_get_iframe() {
      if ( get_photo_meta(get_the_ID())['iframe'] !== NULL ) {
        return get_photo_meta(get_the_ID())['iframe'];
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
        if (eazy_photo_aperture() !== NULL ) {
          $exposure_settings['aperture'] = eazy_photo_aperture();
        }
        if (eazy_photo_shutter_speed() !== NULL ) {
          $exposure_settings['shutter_speed'] = clean_shutter_speed_value(eazy_photo_shutter_speed());
        }
        if (eazy_photo_iso() !== NULL ) {
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


function get_eazy_photo_filters($tax_type, $current_term = 'all') {

  if ($current_term != 'all') {
    $filters = get_photos_from_oppo_tax( $tax_type, $current_term );
    //print_r($filters);
    foreach ($filters as $filter ) { ?>
      <button class="eazy-photo-filter-button button" data-filter=".<?php echo get_oppo_tax_terms($tax_type).'-'. $filter->slug; ?>"><?php echo $filter->name; ?></button>
      <?php
    }
      ?><button class="eazy-photo-filter-button button" data-filter="*">Show All</button> <?php
  }else {  
      $terms = get_terms($tax_type);
      $all_terms = array();
      foreach ($terms as $term) {
        $all_terms[$term->term_taxonomy_id] =  $term; ?>
        <button class="eazy-photo-filter-button button" data-filter=".<?php echo $tax_type.'-'. $term->slug; ?>"><?php echo $term->name; ?></button>
        <?php 
      }
    ?><button class="eazy-photo-filter-button button" data-filter="*">Show All</button> <?php
  } 
  
}


//get the opposite taxonomy from $tax_type 
function get_oppo_tax_terms($tax_type) {
  if ($tax_type == 'photo-category') {
    return 'photo-collection';
  } elseif ($tax_type == 'photo-collection') {
    return 'photo-category';
  }
}

function get_this_photos_terms($tax_type) {
      $collections = get_the_terms( get_the_id(), $tax_type );
      $empty_array = array();
      if ( $collections != '' ) {
        foreach( $collections as $collection ) {
          $empty_array[$collection->name] = $collection;
        }
      }
      return $empty_array;
}


function get_this_photos_terms_as_tags($tax_type) {
  $terms = get_this_photos_terms($tax_type);
  foreach ($terms as $term ) {
    echo $tax_type."-".$term->slug." ";
  }
}

// FILTERS FOR FILTERING PORTFOLIO
function get_photos_from_oppo_tax( $tax_type, $current_term = 'all' ) {

  // WP_Query arguments
  if ($current_term != 'all') {

    $args = array(
      'post_type'   => array( 'eazy-photo' ),
        'tax_query' => array(
          array(
            'taxonomy' => $tax_type,
            'field'    => 'slug',
            'terms'    => $current_term,
          ),
        ),
    );



  // matching_terms that are in the term to match
    $matching_terms = array();

    // The Query
    $photo_query = new WP_Query( $args );

    // The Loop
    if ( $photo_query->have_posts() ) {
      while ( $photo_query->have_posts() ) {
        $photo_query->the_post();

        //set opposite terms as array

        $terms = get_the_terms( get_the_id(), get_oppo_tax_terms($tax_type) );
       

        // if terms is not empty, put them in the matching terms array
        if ($terms != '') {
          foreach ($terms as $term ) {
            $matching_terms[$term->term_taxonomy_id] =  $term;
          }
        }

      }

      return $matching_terms;

    } else {}

    // Restore original Post Data
    wp_reset_postdata();

  } 

}


// get terms as list of links
function get_photo_terms($currentterm) {
  $args = array( 'hide_empty=0' );
  $terms = get_terms( $currentterm, $args );
  if ( ! empty( $terms ) && ! is_wp_error( $terms ) ) {
      $count = count( $terms );
      $i = 0;
      $term_list = '<p class="'.$currentterm.'-archive">';
      foreach ( $terms as $term ) {
          $i++;
          $term_list .= '<a href="' . esc_url( get_term_link( $term ) ) . '" alt="' . esc_attr( sprintf( __( 'View all post filed under %s', 'my_localization_domain' ), $term->name ) ) . '">' . $term->name . '</a>';
          if ( $count != $i ) {
              $term_list .= ' &middot; ';
          }
          else {
              $term_list .= '</p>';
          }
      }
      echo $term_list;
  }
  wp_reset_query();
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
  if ( $aperture != NULL || $shutter_speed != NULL || $iso != NULL ) { ?>
    <div class="eazy-photo-simple-exposure-settings">

        <div id="av" class="simple-exp-setting">
          <?php get_aperture_icon(); ?>
          <div id="aperture-value" class="value">
            <h4><?php 
            if ($aperture !== NULL) {?> 
              <i class="ligature">f</i>/<?php echo $aperture; ?>
            <?php }else { echo "---";}
             ?></h4>
            <h4></h4>
          </div>
        </div><div id="tv" class="simple-exp-setting">
          <?php get_shutter_speed_icon(); ?>
          <div id="shutter_speed-value" class="value">
            <h4><?php 
            if ($shutter_speed !== NULL) {
              echo clean_shutter_speed_value($shutter_speed);
            }else { echo "---";}
             ?></h4>
          </div>
        </div><div id="iso" class="simple-exp-setting">
          <?php get_iso_icon(); ?>
          <div id="iso-value" class="value">
            <h4><?php 
            if ($iso !== NULL) {
              echo $iso;
            }else { echo "---";}
             ?></h4>
          </div>
        </div> 
    </div>
  <?php }
}