<?php 
if ( !defined( 'WPINC' ) ) { die; }

if ('is_admin' ) {

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
    iso text NULL,
    camera text NULL,
    focal_length float NULL,
    map_type text NULL,
    latitude text NULL,
    longitude text NULL,
    iframe text NULL,
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
        array( 'id' => $id )
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
        array( 'id' => $id )
      );
  } else {
      $wpdb->insert( 
        $table_name, 
        $locationarray
      );
  }

}
}