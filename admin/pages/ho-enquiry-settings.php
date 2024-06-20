<?php

/**

 * Settings:  General

 */

if ( ! defined( 'ABSPATH' ) ) {

    exit;

}

if ( isset( $_POST['ho_enquiry_general_wpnonce'] ) && wp_verify_nonce( $_POST['ho_enquiry_general_wpnonce'], 'ho_enquiry_general_settings' )) {

        update_option('ho_api_key', $_POST['form_api_key']);

        update_option('ho_captcha_site_key', $_POST['captcha_site_key']);

        update_option('ho_captcha_secret_key', $_POST['captcha_secret_key']);

      if(isset($_POST['enquiry_source']) && $_POST['enquiry_source']){
            update_option('enquiry_source', $_POST['enquiry_source']);
      }

      HOEnquiry_Notice::success('Changes Data Successfully');

}

add_option('ho_api_key','');

add_option('ho_captcha_site_key','');

add_option('ho_captcha_site_key','');

?>



<div class="wrap">
    <h1 class="wp-heading-inline"><?php echo _e('Keys Settings', 'ho-enquiry'); ?></h1>
<div class="card">
    <div class="inside">
        <p>The Constant Contact integration module seamlessly connects your contact forms with the Constant Contact API, enabling effortless transmission of collected contact data. With just a few simple steps, you can establish dependable email subscription services, ensuring efficient management of subscriber information and seamless communication with your audience.</p>
        <form name='ho-enquiry-general-settings' method="post" class="white-bg ho-enquiry-general-settings">

            <div class='ho-enquiry_container' >

                <table class='form-table'>

                    <tbody>

                    <tr>

                        <th scope="row">

                            <label for="from_api_key" >

                                <?php echo _e('Api Key', 'ho-enquiry'); ?>

                            </label>

                        </th>

                        <td>

                        <input type="text" name="form_api_key" id="form_api_key" value="<?php echo get_option('ho_api_key'); ?>" style='width:300px' />

                        </td>

                    </tr>

                    <tr>

                        <th scope="row">

                            <label for="general[captcha_site_key]" >

                                <?php echo _e('Site Key', 'ho-enquiry'); ?>

                            </label>

                        </th>

                        <td>

                        <input type="text" name="captcha_site_key" id="captcha_site_key" value="<?php echo get_option('ho_captcha_site_key'); ?>" style='width:300px' />

                        </td>

                    </tr>

                    <tr>

                        <th scope="row">

                            <label for="general[captcha_secret_key]" >

                                <?php echo _e('Secret Key', 'ho-enquiry'); ?>

                            </label>

                        </th>

                        <td>

                        <input type="text" name="captcha_secret_key" id="captcha_secret_key" value="<?php echo get_option('ho_captcha_secret_key'); ?>" style='width:300px' />

                        </td>

                    </tr>

                    <tr>

                        <th scope="row">

                            <label for="general[enquiry_source]" >

                                <?php echo _e('Enter Source', 'ho-enquiry'); ?>

                            </label>

                        </th>

                        <td>

                        <input type="text" name="enquiry_source" id="enquiry_source" value="<?php echo get_option('enquiry_source'); ?>" style='width:300px' />

                        </td>

                    </tr>

                    </tbody>

                </table>

                <?php wp_nonce_field( 'ho_enquiry_general_settings', 'ho_enquiry_general_wpnonce' ); ?>

                <?php submit_button( __( 'Save Changes', 'ho-enquiry', false ), 'primary', '', false ); ?>

            </div>

        </form>
    </div>
</div>
    

</div>



