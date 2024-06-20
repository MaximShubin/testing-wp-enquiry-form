<?php
/**
 * HOEnquiry_Notice
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'HOEnquiry_Notice', false ) ) :

class HOEnquiry_Notice {
    
    /**
     * Notice success message
    */
    static public function success($message = '') {
        $class = 'notice notice-success is-dismissible';
        if(empty($message)) {
            $message = __( 'Your changes has been saved.', 'ho-enquiry' );
        }
	    printf( '<div class="%1$s"><p>%2$s</p></div>', esc_attr( $class ), esc_html( $message ) ); 
    }
    
    /**
     * Notice error message
    */
    static public function error() {
        $class = 'notice notice-error is-dismissible';
        $message = __( 'An error has occurred.', 'ho-enquiry' );
        printf( '<div class="%1$s"><p>%2$s</p></div>', esc_attr( $class ), esc_html( $message ) ); 
    }
    
    
}

endif;

return new HOEnquiry_Notice();
?>