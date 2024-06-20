<?php

/**

 * HOENQUIRY_Activation Class

 */

if ( ! defined( 'ABSPATH' ) ) {

    exit; 

}


class HOENQUIRY_Activation {

    /**

     * Check upgrade

     * Run default database queries 

     * Set default value of options 

     */

    public static function install() {

        if ( ! current_user_can( 'activate_plugins' ) ) return;

        include_once HO_ENQUIRY_ABSPATH . 'includes/db_install.php';

        self::set_options();

    }

    /**

     * Setting default value of options 

     */

    protected static function set_options() {


    }

    

}

