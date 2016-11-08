<?php  
/*
 * Plugin Name:       Eazy Photography Portfolio
 * Plugin URI:        http://robjscott.com/wordpress/eazy-photography-portfolio
 * Description:       Creates custom post types for photos with taxonomy for categories and collections
 * Version:           2.0
 * Author:            Rob Scott, LLC
 * Author URI:        http://robjscott.com
 * Text Domain:       eazy-photography
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 */

//if this file is called directly, die.
if ( ! defined( 'WPINC' ) ) {
  die;
}
//defines constants
define( 'EZ_PLUGIN_PATH', plugin_dir_path(__FILE__) );
define( 'EZ_PLUGIN_FILE_PATH', __FILE__ );
define( 'EZ_PLUGIN_URL', str_replace('index.php','',plugins_url( 'index.php', __FILE__ )));

//requires functions to create the custom db table
//require_once(EZ_PLUGIN_PATH . 'includes/admin/admin-create-table.php');
//requires functions to create the photos custom post type and custom taxonomy terms
require_once(EZ_PLUGIN_PATH . 'includes/eazy-photography-portfolio-create-post-type.php');
//requires functions to create the admin settings for photos 
require_once(EZ_PLUGIN_PATH . 'includes/eazy-photography-portfolio-create-admin-section.php');
//requires functions to create the functions for displaying the portfolio
require_once(EZ_PLUGIN_PATH . 'includes/eazy-photography-portfolio-create-display-functions.php');