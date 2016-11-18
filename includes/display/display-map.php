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
    <script type="text/javascript">console.log("Your photo location is not set.");</script>
    <?php 
  }
}