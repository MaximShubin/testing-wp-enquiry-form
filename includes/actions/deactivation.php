<?php

/**

 * HOENQUIRY_Deactivation Class

 */


if ( ! defined( 'ABSPATH' ) ) {

	exit; 

}

class HOENQUIRY_Deactivation {

    
    /**

     * Keep database, only disable necessary options 

     */

    public static function soft_uninstall() {

        
        global $wpdb;

       // delete_option( 'option_name' );

    }

    

}

