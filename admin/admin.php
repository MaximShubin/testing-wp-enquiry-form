<?php

/*

 * Class HOEnquiry_Admin

 */

// Disable direct access

if ( ! defined( 'ABSPATH' ) ) {

	exit;

}

// Check HOEnquiry_Admin class

if ( ! class_exists( 'HOEnquiry_Admin', false ) ) :


class HOEnquiry_Admin {

    /**

     * Load Menus and Initialize scripts

     */

     public function __construct() {
        add_action('admin_menu', array($this, 'add_admin_menu'));
        
    }

    
    public function add_admin_menu() {

        add_menu_page(
            __('HO Enquiry', 'hoenquiry-form'),
            __('HO Enquiry', 'hoenquiry-form'),
            'manage_options',
            'ho-enquiry-form'
        );
        

        $ho_enquiry_listing = add_submenu_page(
            'ho-enquiry-form',
            __('Contact Listing', 'hoenquiry-form'),
            __('Contact Listing', 'hoenquiry-form'),
            'manage_options',
            'ho-enquiry-form',
            array($this, 'ho_enquiry_forms')
        );

        $ho_enquiry_form = add_submenu_page(
            'ho-enquiry-form',
            __('Add New Form', 'hoenquiry-form'),
            __('Add New Form', 'hoenquiry-form'),
            'manage_options',
            'ho-enquiry',
            array($this, 'ho_add_enquiry')
        );

        $ho_enquiry_form = add_submenu_page(
            'ho-enquiry-form',
            __('Settings', 'hoenquiry-form'),
            __('Settings', 'hoenquiry-form'),
            'manage_options',
            'ho-settings',
            array($this, 'ho_enquiry_settings')
        );
    }

    /**
     * Dashboard page
     */
    public function ho_add_enquiry() {
        include_once HO_ENQUIRY_ABSPATH . 'admin/pages/dashboard.php';
    }

    public function ho_enquiry_forms() {
        include_once HO_ENQUIRY_ABSPATH . 'admin/pages/ho-enquiry-listing.php';
    }
    public function ho_enquiry_settings() {
        include_once HO_ENQUIRY_ABSPATH . 'admin/pages/ho-enquiry-settings.php';
    }
    

}

endif;

return new HOEnquiry_Admin();

?>