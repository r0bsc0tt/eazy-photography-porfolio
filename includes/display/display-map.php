<?php

//enqueue google maps js files with the api key from the settings page
function eazy_photo_enqueue_google_maps() {
  if (get_option('eazy-photo-settings-maps') == "on" ) {
    if (get_option('eazy-photo-settings-maps-api-key') != NULL) {
      
      $apikey = get_option('eazy-photo-settings-maps-api-key');
      wp_register_script( 'google-maps', 'https://maps.googleapis.com/maps/api/js?key='.$apikey, array(), '', true );
      wp_enqueue_script( 'google-maps' );
    
    } else {
      echo "You need to enter an API key on the settings page." . get_option('eazy-photo-settings-maps-api-key');
    }

  }
}


//eazy photo iframe map
function eazy_photo_make_map_iframe() {
  if ( eazy_photo_get_iframe() != NULL  ) {
    $iframe_url = eazy_photo_get_iframe();
    ?>
    <div id="map">
      <iframe src="<?php echo $iframe_url; ?>" width="400" height="250" frameborder="0" style="border:0"></iframe>
    </div>
    <?php
  }
}


//PROBABLY A BETTER WAY TO DO THIS
// checks for long & lat vals, puts them in an array for use as an if isset statement to check if both are set before dsplaying map.
function eazy_photo_make_map() {
  $lat_long_vals = array();
    if (eazy_photo_get_latitude() != NULL) { $lat_long_vals['latitude'] = get_photo_meta(get_the_ID())['latitude']; }
    if (eazy_photo_get_longitude() != NULL) { $lat_long_vals['longitude'] = get_photo_meta(get_the_ID())['longitude']; }

  if (isset($lat_long_vals['latitude']) && isset($lat_long_vals['longitude'])) {
    //add and enqueue google maps scripts using api key setting
    eazy_photo_enqueue_google_maps();

    // enque script to make map
    wp_register_script( 'eazy-make-map', EZ_PLUGIN_URL.'includes/js/make-map.js', array(), '', true );
    wp_enqueue_script( 'eazy-make-map');
    //localze lat & long
    $params = array(
      'latitude'  => $lat_long_vals['latitude'],
      'longitude' => $lat_long_vals['longitude'],
    );
    wp_localize_script( 'eazy-make-map', 'EazyPhotoMap', $params );
    //display map ?>
    <div id="map"></div>
    <?php 
  }
}

// defer loading of google maps scripts
add_filter('script_loader_tag', 'add_defer_attribute', 10, 2);
function add_defer_attribute($tag, $handle) {
   // add script handles to the array below
   $scripts_to_defer = array('google-maps', 'eazy-make-map');
   
   foreach($scripts_to_defer as $defer_script) {
      if ($defer_script === $handle) {
         return str_replace(' src', ' defer="defer" src', $tag);
      }
   }
   return $tag;
}


function eazy_photo_query( ) {

  $args = array(
    'post_type' => 'eazy-photo',
    'posts_per_page' => -1
  );

  // The Query
  $the_query = new WP_Query( $args );


  if ( $the_query->have_posts() ) {
    while ( $the_query->have_posts() ) {



      return $the_query->posts;
    }
    /* Restore original Post Data */
    wp_reset_postdata();
  } else {
    // no posts found
  }  
}



function eazy_photo_fullpage_photomap() {
// query eazy photos
$scriptparams = array();
$the_query = eazy_photo_query();

foreach ($the_query as $photo ) {

    if ( get_photo_meta($photo->ID)['latitude'] !== NULL && get_photo_meta($photo->ID)['longitude'] !== NULL) {

      $scriptparams[] = array(
          "id"        =>  $photo->ID,
          "title"     =>  $photo->post_title,
          "latitude"  =>  get_photo_meta($photo->ID)['latitude'], 
          "longitude" =>  get_photo_meta($photo->ID)['longitude'],
          "img"       =>  get_the_post_thumbnail_url($photo->ID, 'eazy-photo-thumb-m'),
          "link"      =>  $photo->guid
      );

    }

}



  eazy_photo_enqueue_google_maps();
  // enque script to make map
  wp_register_script( 'eazy-make-map', EZ_PLUGIN_URL.'includes/js/make-map.js', array(), '', true );
  wp_enqueue_script( 'eazy-make-map');

  wp_localize_script( 'eazy-make-map', 'EazyPhotoMap', $scriptparams );

if ( get_option('eazy-photo-settings-fullmap-center-lat') != ""  && get_option('eazy-photo-settings-fullmap-center-long') != "" ) {
  $maplocation = array(
    "latitude"  => get_option('eazy-photo-settings-fullmap-center-lat'), 
    "longitude" => get_option('eazy-photo-settings-fullmap-center-long')
    );
  wp_localize_script( 'eazy-make-map', 'EazyMapBG', $maplocation);
}

}