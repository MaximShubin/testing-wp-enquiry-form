<?php 
$notices = new HOEnquiry_Notice();
if ( isset( $_POST['hoenquiry_wpnonce'] ) && wp_verify_nonce( $_POST['hoenquiry_wpnonce'], 'hoenquiry_form' )) {
    global $wpdb;
    $table_name = $wpdb->prefix . 'heypa_contacts';
    $id = isset($_GET['id']) ? $_GET['id'] : '';
    $button_name = isset($_POST['submit_button_name']) ? sanitize_text_field($_POST['submit_button_name']) : '';
    $form_title = isset($_POST['form_title']) ? sanitize_text_field($_POST['form_title']) : '';
    $form_content = isset($_POST['ho-from-body']) ? wp_kses_post($_POST['ho-from-body']) : '';
    
    $data = array(
            'form_title' => $form_title,
            'submit_button_title' => $button_name,
            'form_content' => $form_content,
            // Add more columns and values as needed
        );
    if($id){
        $where = array( 'id' => $id);
    
        $result = $wpdb->update($table_name, $data, $where);
    }else{
        $result = $wpdb->insert( $table_name, $data );
    }
    
    $notices->success('Your Form Updated Successfully');

}
$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
        global $wpdb;
        $sql ="";
        $table_name = $wpdb->prefix . 'heypa_contacts';
        if(isset($_GET['id']) && $_GET['id']){
            
            $sql = "SELECT * FROM $table_name  WHERE `id` ='". $_GET['id']."'";
        }else{
            $sql = "SELECT * FROM $table_name";
        }
        
    
        // Fetch data from the database
        $results = (array) $wpdb->get_row( $sql );
        $formTitle = $results['form_title'];
        $content = $results['form_content'];
        $button_title = $results['submit_button_title'];
      

        $variables = array(
        
            array(
                'label' => 'Street Address',
                'identifier' => 'street_address',
                'placeholder' => 'Enter Your Street Address',
                'required' => false,
            ),
            array(
                'label' => 'Suburb',
                'identifier' => 'suburb',
                'placeholder' => 'Enter Your Suburb',
                'required' => false,
            ),
            array(
                'label' => 'Post Code',
                'identifier' => 'postal_code',
                'placeholder' => 'Enter Your Post Code',
                'required' => false,
            ),
            array(
                'label' => 'State',
                'identifier' => 'state',
                'placeholder' => 'Enter Your State',
                'required' => false,
            ),
            array(
                'label' => 'Company Name',
                'identifier' => 'company_name',
                'placeholder' => 'Enter Your Company Name',
                'required' => false,
            ),
            array(
                'label' => 'Website',
                'identifier' => 'website',
                'placeholder' => 'Enter Your Website',
                'required' => false,
            ),
        );
?>
<div class="admin-wrapper-contact-form wrap">
<h1 class="wp-heading-inline">Add New Heypa Enquiry Form</h1>
  <div class="ho-enquiry-wrap">
        <form name='ho-enquiry-form' method="post" class="white-bg wealthsharerealty-general-settings">
            <div id="titlediv">
                <?php if($id){?>
                <input type="text" name="form_title" value="<?php echo $formTitle;?>" required id='title'>
                <?php } else{?>
                <input type="text" name="form_title" value="" required id='title' placeholder="Enter Title Here">
                <?php } ?>
            </div>
            <div>
                <div class="ho-enquiry-form-content">
                    <h2>Form</h2>
                    <p>You can edit the form template here. </p>
                    <div class="ho-enquiry-left-panel" id="tag-generator-list">
                <?php
                    
                    
                    
                    foreach($variables as $key => $value){
                        ?>
                        <a href="#" class="button" id="item-<?php echo htmlspecialchars($value['identifier']) ?>" onClick="selectItem(this,'<?php echo htmlspecialchars(json_encode($value)) ?>')">
                        <?php echo htmlspecialchars($value['label']); ?>
                        </a>

                    <?php
                        }
                    ?>
                    </div>
                    <div>
                        <textarea id="ho-enquiry-form-body" name="ho-from-body" cols="100" rows="24"> <?php echo stripslashes($content); ?> </textarea>
                    </div>
                    <div class="form-submit-title">
                        <label>Change Form Sumit Button Title</label>
                        <input type="text" name="submit_button_name" value="<?php echo $button_title; ?>"/>
                    </div>
                </div>
                
                <input type="hidden" name="form_id" value="<?php echo $id;?>"/>
            <?php wp_nonce_field( 'hoenquiry_form', 'hoenquiry_wpnonce' );?>

            <?php submit_button( __( 'Save Changes', 'hoenquiry', false ), 'primary', '', false ); ?>
            </div>

        </form>
</div>
</div>


<script>


    function insertAtCursor(text) {
        var textarea = jQuery('#ho-enquiry-form-body')[0];
        var cursorPosition = textarea.selectionStart;
        var textBeforeCursor = textarea.value.substring(0, cursorPosition);
        var textAfterCursor = textarea.value.substring(cursorPosition);
        textarea.value = textBeforeCursor + text + textAfterCursor;
        textarea.selectionStart = textarea.selectionEnd = cursorPosition + text.length;
        textarea.focus();
    }
    function selectItem (e, data){
        var requiredData = '';
        var jsonData = JSON.parse(data);
        if(jsonData.required == true){
            requiredData = 'required';
        }

        var htmlData = '';
        htmlData += '['+ jsonData.identifier +' placeholder="'+ jsonData.placeholder +'"'+ requiredData +']'
        insertAtCursor(htmlData);
        
    }

</script>