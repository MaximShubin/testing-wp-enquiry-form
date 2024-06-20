<?php
/*
Plugin Name: Heypa Online Enquiry Form
Description: A plugin to manage HeyPa contacts settings.
Version: 1.0
Author: Heypa Online
*/


if ( ! defined( 'ABSPATH' ) ) {
    exit; 
}

/**
 *  Define plugin directory file
 */
if ( ! defined( 'HO_ENQUIRY_PLUGIN_FILE' ) ) {
	define( 'HO_ENQUIRY_PLUGIN_FILE', __FILE__);
}

/**  
 * Define plugin directory path
 */
if ( ! defined( 'HO_ENQUIRY_PLUGIN_DIR' ) ) {
	define( 'HO_ENQUIRY_PLUGIN_DIR', dirname( __FILE__ ));
}

/**
 * Define plugin url
 */
if ( ! defined( 'HO_ENQUIRY_PLUGIN_URL' ) ) {
	define( 'HO_ENQUIRY_PLUGIN_URL', plugins_url(null, __FILE__) . '/');
}

function ho_register_plugin_styles() {
	//add css
	wp_register_style( 'style-ui','https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css');
	wp_enqueue_style( 'style-ui');

	wp_register_style('hoenquiry-styles', HO_ENQUIRY_PLUGIN_URL . 'assets/css/style.css'); 
	wp_enqueue_style('hoenquiry-styles'); 

	wp_enqueue_script('jquery');
	 
 }
 
 add_action( 'wp_enqueue_scripts', 'ho_register_plugin_styles' );

//load admin script
add_action( 'admin_enqueue_scripts', 'HO_Enquiry_admin_script' );
function HO_Enquiry_admin_script() {

	wp_enqueue_script( 'ohenquiry-jquery-ui-script', 'https://code.jquery.com/jquery-3.6.0.min.js', array('jquery'), '1.0.0', true );
	wp_enqueue_script( 'ohenquiry-jquery-ui-min-script', 'https://code.jquery.com/ui/1.12.1/jquery-ui.min.js', array(), '1.0.0', true );
	wp_register_style('ohenquiry-form-styles-admin', HO_ENQUIRY_PLUGIN_URL . 'assets/css/admin.css'); 
	wp_enqueue_style('ohenquiry-form-styles-admin'); 
	wp_enqueue_style('ohenquiry-form-ui-styles-admin','https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css'); 
}

function ho_enquiry_activation_hook() {
	$site_title = get_bloginfo('name');
    // Check if the option already exists
    if (false === get_option('enquiry_source')) {
        add_option('enquiry_source', $site_title);
    }
}
// Hook into the activation process for your plugin
register_activation_hook(__FILE__, 'ho_enquiry_activation_hook');

/**

 * Include main class to set plugins

 */

 if ( ! class_exists( 'OHENQUIRY ' ) ) {

    include_once HO_ENQUIRY_PLUGIN_DIR . '/includes/ho-enquiry-form.php';

}
/**

 * Load plugin install

 */

 $hoEnquiry = HOEnquiry::get_instance();

