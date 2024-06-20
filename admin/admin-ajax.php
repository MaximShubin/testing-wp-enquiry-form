<?php
add_action( 'wp_ajax_ho_enquiry_form_token_ajax', 'ho_enquiry_form_token_ajax' );
add_action( 'wp_ajax_nopriv_ho_enquiry_form_token_ajax', 'ho_enquiry_form_token_ajax' );

function ho_enquiry_form_token_ajax() {
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, 'https://dev-api.heypa.com.au/api-documentation/generate-site-token');
    curl_setopt($ch, CURLOPT_HTTPHEADER, array(
        'Content-Type: application/json',
        'X-SOURCE: scriptevolve.com',
        'User-Agent: '.$_SERVER['HTTP_USER_AGENT']
    ));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    // Execute cURL request
    $response = curl_exec($ch);

    // Check for errors
    if (curl_errno($ch)) {
        $error_message = curl_error($ch);
        // You may log or display the error message
        echo json_encode(array('error' => $error_message));
    } else {
        echo json_encode(array('response' => $response));
    }

    // Close cURL session
    curl_close($ch);
    wp_die();
}


add_action( 'wp_ajax_ho_enquiry_form_ajax', 'ho_enquiry_form_ajax' );
add_action( 'wp_ajax_nopriv_ho_enquiry_form_ajax', 'ho_enquiry_form_ajax' );
function ho_enquiry_form_ajax() {
    $formData = $_POST['formData'];
    $hoToken = $_POST['ho_token'];
	try{
		$get_source = get_option('enquiry_source');
        $get_api_key = get_option('ho_api_key');
      
		if($formData['_wpnonce']){
            $nonce = wp_verify_nonce($formData['_wpnonce'], 'ho-enquiry-form' );
        
            if($nonce){
                    
                        $data =array(
                            "customer_name" => $formData['customer_name'],
                            "phone" =>$formData['phone'] ? $formData['phone']: '',
                            "street_address" => $formData['street_address'] ? $formData['street_address']: '',
                            "suburb" => $formData['suburb'] ? $formData['suburb']: '',
                            "postal_code" => $formData['postal_code'] ? $formData['postal_code']: '',
                            "state" => $formData['state'] ? $formData['state']: '',
                            "company_name" => $formData['company_name'] ? $formData['company_name']: '',
                            "email" => $formData['email'] ? $formData['email']: '',
                            "website" => $formData['website'] ? $formData['website']: '',
                            "enquiry_information" => $formData['enquiry_information'] ? $formData['enquiry_information']: '',
                            "enquiry_source" => $get_source,
                        );

                        $json_data = json_encode($data);
						
                        $ch = curl_init();
                        curl_setopt($ch, CURLOPT_URL, 'https://dev-api.heypa.com.au/api-documentation/contact');
                        curl_setopt($ch, CURLOPT_POST, 1);
                        curl_setopt($ch, CURLOPT_POSTFIELDS, $json_data);
                        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                            'Content-Type: application/json',
                            'AUTHORIZATION:'.$get_api_key, 
                            'X-SOURCE: scriptevolve.com',
                            'User-Agent: '.$_SERVER['HTTP_USER_AGENT'],
                            'HO-TOKEN: '.$hoToken,
                        ));
                        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                    
                        // Execute cURL request
                        $response = curl_exec($ch);
                    
                        // Check for errors
                        if (curl_errno($ch)) {
                            $error_message = curl_error($ch);
                            // You may log or display the error message
                            echo json_encode(array('error' => $error_message));
                        } else {
                            echo json_encode(array('response' => $response));
                        }
                    
                        // Close cURL session
                        curl_close($ch);
                        wp_die();
                       
            }

        }
    }catch(Exception $e){
        echo 'Message: ' .$e->getMessage();
        die();
    }
	
}
?>