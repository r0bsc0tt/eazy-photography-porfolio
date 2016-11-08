<?php 
if ( !defined( 'WPINC' ) ) { die; }

// on activation install the table
register_activation_hook( EZ_PLUGIN_FILE_PATH, 'eazy_photo_install' );
register_activation_hook( EZ_PLUGIN_FILE_PATH, 'eazy_photo_install_data' );

global $eazy_photo_db_version;
$eazy_photo_db_version = '1.0';

function eazy_photo_install() {
  global $wpdb;
  global $eazy_photo_db_version;

  $table_name = $wpdb->prefix . 'eazy_photos_meta';
  
  $charset_collate = $wpdb->get_charset_collate();

  $sql = "CREATE TABLE $table_name (
    id mediumint(9) unsigned NOT NULL AUTO_INCREMENT,
    time datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
    name tinytext NOT NULL,
    text text NOT NULL,
    aperture float NOT NULL,
    shutter_speed float NOT NULL,
    iso float NOT NULL,
    camera tinytext NOT NULL,
    focal_length float NOT NULL,
    latitude float NOT NULL,
    longitude float NOT NULL,
    PRIMARY KEY  (id)
  ) $charset_collate;";

  require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
  dbDelta( $sql );

  add_option( 'eazy_photo_db_version', $eazy_photo_db_version );
}

function eazy_photo_install_data() {
  global $wpdb;
  
  $welcome_name = 'Mr. WordPress';
  $welcome_text = 'Congratulations, you just completed the installation!';
  $photo_id = get_the_id();

  $table_name = $wpdb->prefix . 'eazy_photos_meta';
  
  $wpdb->insert( 
    $table_name, 
    array( 
      'id'   => $photo_id,
      'time' => current_time( 'mysql' ), 
      'name' => $welcome_name, 
      'text' => $welcome_text,
    ) 
  );
}


