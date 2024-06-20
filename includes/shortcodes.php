<?php

/**

 * Shortcodes 

 */

 if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
class HOEnquiry_Shortcode {
    public function __construct() {
        // Add any necessary actions or hooks here
        add_shortcode( 'Heypa-Form', array($this,'heypa_contacts_shortcode_function' ));
    }

    public function heypa_contacts_shortcode_function($atts) {
        ob_start();
        // Extract shortcode attributes
        $atts = shortcode_atts(array(
            'id' => '',
        ), $atts);
        
        global $wpdb;
        $error_msg = "";
        // Table name
        $table_name = $wpdb->prefix . 'heypa_contacts';
        $id =$atts['id'];
    
        // Query to fetch data from the table
        $sql = "SELECT * FROM $table_name  WHERE `id` = $id ";
    
        // Fetch data from the database
        $results = (array) $wpdb->get_row($sql);
        $get_source = get_option('enquiry_source');
        $get_api_key = base64_encode(get_option('ho_api_key'));

        if(!empty($get_source)){
        ?>
            
                <div id="form-wrapper"></div>
                <form method="post" id="ho-enquiry-form">
                    <?php 
                    $data = stripslashes($results['form_content']);
                    echo $this->parse_enquiry_form_shortcode($data);
                    
                    ?>
                    <input type="hidden" id="g-recaptcha-response" name="g-recaptcha-response">
                    <input type="hidden" name="enquiry_source" value="<?php echo $get_source;?>">
                    <?php
                        $_wpnonce = wp_create_nonce('ho-enquiry-form');
                        echo '<input id="submit_btn_nonce" type="hidden" name="_wpnonce" value="'.$_wpnonce.'">';
                    ?>
                    <button type="submit" id="submit_btn" name='ho_enquiry' class="submit-button" value="Submit">
                    <?php echo $results['submit_button_title'];?>
                    </button>
                </form>

            <script src="https://www.google.com/recaptcha/api.js?render=<?php echo get_option( 'ho_captcha_site_key' );?>"></script>	
            <script type="text/javascript">

                grecaptcha.ready(function() {
                        grecaptcha.execute('<?php echo get_option( "ho_captcha_site_key" ); ?>', {action:'validate_captcha'})
                                .then(function(token) {
                            document.getElementById('g-recaptcha-response').value = token;
                        });
                        
                });

                function resetForm() 
                {
                    jQuery('#ho-enquiry-form')[0].reset();
                }
                jQuery( document ).ready(function() {
                    var ho_token = '';
                    jQuery.ajax({
                        type : "POST",
                        dataType : "json",
                        url : '<?php echo admin_url( 'admin-ajax.php' ) ?>',
                        data: {	
                            action: 'ho_enquiry_form_token_ajax',
                            
                        },

                        success: function(data) {
                            var obj = data.response;
                            if(obj.length > 1 ){
                                var obj = JSON.parse(data.response);
                                 ho_token = obj.data.ho_token;
                            }
                            
                        },
                    });
                    jQuery("#ho-enquiry-form").submit(function(event){
                        
                        event.preventDefault();

                        var _this = jQuery(this);

                        const formData = Object.fromEntries(new FormData(event.target).entries());

                        var customer_name = jQuery('#customer_name').val();
                        var enquiry_information = jQuery('#enquiry_information').val();
                        var email = jQuery('#email').val();

                        jQuery(".error").remove();

                        if (customer_name.length < 1) {
                            jQuery('#customer_name').after('<span class="error">This field is required</span>');
                        }

                        if (enquiry_information.length < 1) {
                            jQuery('#enquiry_information').after('<span class="error">This field is required</span>');
                        }

                        if(customer_name != '' && enquiry_information != ''){
                            jQuery.ajax({
                                type : "POST",
                                dataType : "json",
                                url : '<?php echo admin_url( 'admin-ajax.php' ) ?>',
                                data: {	
                                    action: 'ho_enquiry_form_ajax',
                                    formData: formData,
                                    ho_token:ho_token,
                                    
                                },
                                beforeSend: function(){			
                                    var loadingText = '<i class="fa fa-circle-o-notch fa-spin"></i> Processing...';
                                    jQuery('#submit_btn').html(loadingText);
                                    jQuery('#submit_btn').attr('disabled', true);
                                },
                                success: function(data) {
                                    console.log(data);
                                    var response = JSON.parse(data.response);
                                    if(response.error === true){
                                        
                                        jQuery('#form-wrapper').append('<p class="form-error-data">'+ response.message +'</p>');

                                    }else{
                                        jQuery('#form-wrapper').append('<p class="form-success-data">'+ response.data.message +'</p>');
                                    }
                                    resetForm();
                                    jQuery('#submit_btn').html('Submit');
                                    jQuery('#submit_btn').attr('disabled', false);
                                    
                                },
                            });
                        }
                    });
                
                });

            </script>
        <?php
        }
        return ob_get_clean();
    
    }

    public function parse_enquiry_form_shortcode($content) {
        
        $pattern = '/\[([a-zA-Z0-9_]+)([^\]]*)\]/';
        
        // Replace the shortcode with HTML input elements
        $content = preg_replace_callback($pattern, function($matches) {
            // Check if shortcode matches one of the supported fields
            switch ($matches[1]) {
                case 'customer_name':
                    $placeholder = $required = '';
                    // Extract the placeholder and required attributes if present
                    preg_match_all('/\s(\w+)=["\']([^"\']*)["\']/', $matches[2], $attributes, PREG_SET_ORDER);
                    foreach ($attributes as $attribute) {
                        if ($attribute[1] == 'placeholder') {
                            $placeholder = $attribute[2];
                        } elseif ($attribute[1] == 'required') {
                            $required = 'required';
                        }
                    }
                    // Return the HTML input element for customer name
                    return "<input type=\"text\" name=\"customer_name\" id=\"customer_name\" placeholder=\"$placeholder\" $required>";
    
                case 'email':
                    $placeholder = $required = '';
                    // Extract the placeholder and required attributes if present
                    preg_match_all('/\s(\w+)=["\']([^"\']*)["\']/', $matches[2], $attributes, PREG_SET_ORDER);
                    foreach ($attributes as $attribute) {
                        if ($attribute[1] == 'placeholder') {
                            $placeholder = $attribute[2];
                        } elseif ($attribute[1] == 'required') {
                            $required = 'required';
                        }
                    }
                    // Return the HTML input element for customer name
                    return "<input type=\"email\" name=\"email\" id=\"email\" placeholder=\"$placeholder\" $required>";
    
                    case 'phone':
                        $placeholder = $required = '';
                        // Extract the placeholder and required attributes if present
                        preg_match_all('/\s(\w+)=["\']([^"\']*)["\']/', $matches[2], $attributes, PREG_SET_ORDER);
                        foreach ($attributes as $attribute) {
                            if ($attribute[1] == 'placeholder') {
                                $placeholder = $attribute[2];
                            } elseif ($attribute[1] == 'required') {
                                $required = 'required';
                            }
                        }
                        // Return the HTML input element for customer name
                        return "<input type=\"text\" name=\"phone\" id=\"phone\" placeholder=\"$placeholder\" $required>";
    
                    case 'enquiry_information':
                        $placeholder = $required = '';
                        // Extract the placeholder and required attributes if present
                        preg_match_all('/\s(\w+)=["\']([^"\']*)["\']/', $matches[2], $attributes, PREG_SET_ORDER);
                        foreach ($attributes as $attribute) {
                            if ($attribute[1] == 'placeholder') {
                                $placeholder = $attribute[2];
                            } elseif ($attribute[1] == 'required') {
                                $required = 'required';
                            }
                        }
                        // Return the HTML input element for customer name
                        return "<textarea name=\"enquiry_information\" id=\"enquiry_information\" placeholder=\"$placeholder\" $required></textarea>";

                    case 'company_name':
                        $placeholder = $required = '';
                        // Extract the placeholder and required attributes if present
                        preg_match_all('/\s(\w+)=["\']([^"\']*)["\']/', $matches[2], $attributes, PREG_SET_ORDER);
                        foreach ($attributes as $attribute) {
                            if ($attribute[1] == 'placeholder') {
                                $placeholder = $attribute[2];
                            } elseif ($attribute[1] == 'required') {
                                $required = 'required';
                            }
                        }
                        // Return the HTML input element for customer name
                        return "<input type=\"text\" name=\"company_name\" id=\"company_name\" placeholder=\"$placeholder\" $required>";

                    case 'street_address':
                        $placeholder = $required = '';
                        // Extract the placeholder and required attributes if present
                        preg_match_all('/\s(\w+)=["\']([^"\']*)["\']/', $matches[2], $attributes, PREG_SET_ORDER);
                        foreach ($attributes as $attribute) {
                            if ($attribute[1] == 'placeholder') {
                                $placeholder = $attribute[2];
                            } elseif ($attribute[1] == 'required') {
                                $required = 'required';
                            }
                        }
                        // Return the HTML input element for customer name
                        return "<input type=\"text\" name=\"street_address\" id=\"street_address\" placeholder=\"$placeholder\" $required>";

                    case 'suburb':
                        $placeholder = $required = '';
                        // Extract the placeholder and required attributes if present
                        preg_match_all('/\s(\w+)=["\']([^"\']*)["\']/', $matches[2], $attributes, PREG_SET_ORDER);
                        foreach ($attributes as $attribute) {
                            if ($attribute[1] == 'placeholder') {
                                $placeholder = $attribute[2];
                            } elseif ($attribute[1] == 'required') {
                                $required = 'required';
                            }
                        }
                        // Return the HTML input element for customer name
                        return "<input type=\"text\" name=\"suburb\" id=\"suburb\" placeholder=\"$placeholder\" $required>";

                    case 'postal_code':
                        $placeholder = $required = '';
                        // Extract the placeholder and required attributes if present
                        preg_match_all('/\s(\w+)=["\']([^"\']*)["\']/', $matches[2], $attributes, PREG_SET_ORDER);
                        foreach ($attributes as $attribute) {
                            if ($attribute[1] == 'placeholder') {
                                $placeholder = $attribute[2];
                            } elseif ($attribute[1] == 'required') {
                                $required = 'required';
                            }
                        }
                        // Return the HTML input element for customer name
                        return "<input type=\"text\" name=\"postal_code\" id=\"postal_code\" placeholder=\"$placeholder\" $required>";

                    case 'state':
                        $placeholder = $required = '';
                        // Extract the placeholder and required attributes if present
                        preg_match_all('/\s(\w+)=["\']([^"\']*)["\']/', $matches[2], $attributes, PREG_SET_ORDER);
                        foreach ($attributes as $attribute) {
                            if ($attribute[1] == 'placeholder') {
                                $placeholder = $attribute[2];
                            } elseif ($attribute[1] == 'required') {
                                $required = 'required';
                            }
                        }
                        // Return the HTML input element for customer name
                        return "<input type=\"text\" name=\"state\" id=\"state\" placeholder=\"$placeholder\" $required>";

                    case 'website':
                        $placeholder = $required = '';
                        // Extract the placeholder and required attributes if present
                        preg_match_all('/\s(\w+)=["\']([^"\']*)["\']/', $matches[2], $attributes, PREG_SET_ORDER);
                        foreach ($attributes as $attribute) {
                            if ($attribute[1] == 'placeholder') {
                                $placeholder = $attribute[2];
                            } elseif ($attribute[1] == 'required') {
                                $required = 'required';
                            }
                        }
                        // Return the HTML input element for customer name
                        return "<input type=\"text\" name=\"website\" id=\"website\" placeholder=\"$placeholder\" $required>";
                default:
                    // Return the shortcode as is if it's not one of the supported fields
                    return $matches[0];
            }
        }, $content);
    
        return $content;
    }

    
}

new HOEnquiry_Shortcode();