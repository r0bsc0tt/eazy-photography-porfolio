<?php 
if ( !defined( 'WPINC' ) ) { die; }

// on activation install the table
register_activation_hook( EZ_PLUGIN_FILE_PATH, 'eazy_photo_install' );
//register_activation_hook( EZ_PLUGIN_FILE_PATH, 'eazy_photo_install_data' );

global $eazy_photo_db_version;
$eazy_photo_db_version = '1.0';

function eazy_photo_install() {
  global $wpdb;
  global $eazy_photo_db_version;

  $table_name = $wpdb->prefix . 'eazy_photos_meta';
  
  $charset_collate = $wpdb->get_charset_collate();

  $sql = "CREATE TABLE $table_name (
    id mediumint(9) unsigned NOT NULL AUTO_INCREMENT,
    aperture float NULL,
    shutter_speed float NULL,
    iso float NULL,
    camera text NULL,
    focal_length float NULL,
    latitude text NULL,
    longitude text NULL,
    PRIMARY KEY  (id)
  ) $charset_collate;";

  require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
  dbDelta( $sql );

  add_option( 'eazy_photo_db_version', $eazy_photo_db_version );
}




function get_photo_meta($id){
    global $wpdb;
    $table_name = $wpdb->prefix . 'eazy_photos_meta';

    $row_by_id = $wpdb->get_row(
        "SELECT * FROM $table_name WHERE id = $id", ARRAY_A
    );

    return $row_by_id;

}


function does_id_exist($id) {
    global $wpdb;
    $table_name = $wpdb->prefix . 'eazy_photos_meta';

    $row_by_id = $wpdb->get_row(
        "SELECT * FROM $table_name WHERE id = $id", ARRAY_A
    );

    if ($row_by_id['id'] == '') {
      return false;
    } else {
      return true;
    }
}


function eazy_photo_add_to_db($id, $expvalarray) {
  global $wpdb;
  $table_name = $wpdb->prefix . 'eazy_photos_meta';


    if (does_id_exist($id) == true) {
        $wpdb->update( 
        $table_name,  
        $expvalarray,  // string
        array( 'id' => $id ), 
        array( 
          '%d', // id
          '%f', // aperture
          '%f', // shutter speed
          '%f', // iso
          '%s', // camera
          '%f', // focal length
        ), 
        array( '%s' ) 
      );
    } else {
      $wpdb->insert( 
        $table_name, 
        $expvalarray
      );
    } 
}


function eazy_photo_add_location_to_db($id, $locationarray) {
  global $wpdb;
  $table_name = $wpdb->prefix . 'eazy_photos_meta';
  if (does_id_exist($id) == true) {
      $wpdb->update( 
        $table_name,  
        $locationarray,  // string
        array( 'id' => $id ), 
        array( 
          //'%d', // id
          '%s', // latitude
          '%s', // longitude
        ), 
        array( '%s' ) 
      );
  } else {
      $wpdb->insert( 
        $table_name, 
        $locationarray
      );
  }

  }