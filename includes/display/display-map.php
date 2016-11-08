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

//create inline script to display the map
function eazy_photo_inline_script() { 
  $latitude = eazy_photo_get_latitude();
  $longitude = eazy_photo_get_longitude();
  ?>
  <script type="text/javascript"> 
    var map = new google.maps.Map(document.getElementById("map"), {
      center: {
        lat: <?php echo $latitude;?>,
        lng: <?php echo $longitude;?>
      },
      zoom: 15
    });

    var marker = new google.maps.Marker({
      position: {lat: <?php echo $latitude;?>, lng: <?php echo $longitude;?>},
      map: map
    });

    var infowindow = new google.maps.InfoWindow();
  </script>
  <?php
}

// if camera latitude & longitude is set, add the action to put inline script in footer
function eazy_photo_make_map() {
  if ( get_post_meta( get_the_ID(), '_eazy_camera_longitude', true ) != NULL
       && get_post_meta( get_the_ID(), '_eazy_camera_latitude', true ) != NULL) {
    //add and enqueue google maps scripts using api key setting
    eazy_photo_enqueue_google_maps();
    //add inline script to footer
    add_action( 'wp_footer', 'eazy_photo_inline_script', 50 );
    //display map
    ?><div id="map"></div><?php
  } else { ?>
    <script type="text/javascript">console.log("Your photo location is not set.");</script>
  <?php }
}

