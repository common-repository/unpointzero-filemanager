<?php
/**
 * The WordPress Plugin Boilerplate.
 *
 * A foundation off of which to build well-documented WordPress plugins that also follow
 * WordPress coding standards and PHP best practices.
 *
 * @package   UPZ_FileManager
 * @author    UnPointZero Webagency <informations@unpointzero.com>
 * @link      http://www.unpointzero.com
 * @copyright 2014 Agence Web UnPointZero
 *
 * @wordpress-plugin
 * Plugin Name: UnPointZero FileManager
 * Plugin URI:  UPZFileManager
 * Description: Manage public & private file downloads, display single download on frontend or display the integrated filemanager.
 * Version:     0.1.5
 * Author:      UnPointZero
 * Author URI:  http://www.unpointzero.com
 * Text Domain: UPZFileManager
 * Domain Path: /lang
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

require_once( plugin_dir_path( __FILE__ ) . 'functions/config.php' );
require_once( plugin_dir_path( __FILE__ ) . 'class-UPZFileManager.php' );
define( 'UPZFILES_PLUGFULLDIR', plugin_dir_path( __FILE__ ) );

// Register hooks that are fired when the plugin is activated, deactivated, and uninstalled, respectively.
// TODO: replace Plugin_Name with the name of the plugin defined in `class-plugin-name.php`
register_activation_hook( __FILE__, array( 'UPZFileManager', 'activate' ) );
register_deactivation_hook( __FILE__, array( 'UPZFileManager', 'deactivate' ) );

// TODO: replace Plugin_Name with the name of the plugin defined in `class-plugin-name.php`
UPZFileManager::get_instance();