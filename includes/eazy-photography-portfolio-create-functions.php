<?php 
if ( !defined( 'WPINC' ) ) { die; }

if ( post_type_exists( 'eazy-photo' ) ) {

  function clean_shutter_speed_value( $ssval) {
    //$ssval = eazy_photo_exif_info_get_value('shutter_speed');
    if ($ssval <= 1) {
      return "1/".ceil(1 / $ssval) ;
    }else {
      return $ssval . '"';
    }
  }

}