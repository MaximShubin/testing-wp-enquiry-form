<?php
/**
 * Class HOEnquiry
*/

if ( ! defined( 'ABSPATH' ) ) {
	exit; 
}

class HOEnquiry {
    
    static $instance;
    
    /**
     * Register constants
     * Include files 
     * Define hooks  
    */
    public function __construct() { //doubt
        $this->init_constants();
        $this->include_files();
        $this->init_hooks();
    }

    /**
     * Get Instance
     * Get OPContact instance
     * 
    */
    public static function get_instance() {
        if ( ! isset( self::$instance ) ) {
                self::$instance = new self();
        }

        return self::$instance;
    }

    /**
     * Define plugin constants
    */
    public function init_constants() {
        global $wpdb;
        if (!defined('HO_ENQUIRY_ABSPATH')) define( 'HO_ENQUIRY_ABSPATH',  HO_ENQUIRY_PLUGIN_DIR  . '/' );

        // Define table names
        $table_prefix = $wpdb->prefix . 'heypa_contacts_';
        if (!defined('HO_ENQUIRY_TABLE_FORM')) define('HO_ENQUIRY_TABLE_FORM', $table_prefix . 'heypa_contacts');

    }

    /**
     * Include all require files
    */
    public function include_files() {

        include_once HO_ENQUIRY_ABSPATH . 'includes/actions/activation.php';

        include_once HO_ENQUIRY_ABSPATH . 'includes/actions/deactivation.php';
        
        include_once HO_ENQUIRY_ABSPATH . 'admin/admin.php';
        
        include_once HO_ENQUIRY_ABSPATH . 'admin/admin-ajax.php';
        
        include_once HO_ENQUIRY_ABSPATH . 'includes/shortcodes.php';
        
        include_once HO_ENQUIRY_ABSPATH . 'includes/helpers/notices.php';

    }

    /**

     * Register activitation hook

     * Register deactivation hook

    */

    public function init_hooks() {

        register_activation_hook( HO_ENQUIRY_PLUGIN_FILE, array( 'HOENQUIRY_Activation', 'install' ) );

        register_deactivation_hook( HO_ENQUIRY_PLUGIN_FILE, array( 'HOENQUIRY_Deactivation', 'soft_uninstall' ) );

    }
    


}
?>