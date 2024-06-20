<?php
/* 
 * db_install.php 
 */

 global $wpdb;
 $table_name = $wpdb->prefix . 'heypa_contacts';
 $table_exists = $wpdb->get_var("SHOW TABLES LIKE '$table_name'") === $table_name;
 
 
 // SQL query for creating the table
 // If the table doesn't exist, create it and insert data
 if (!$table_exists) {
    $charset_collate = $wpdb->get_charset_collate();

    $sql_create_table = "CREATE TABLE IF NOT EXISTS $table_name (
        `id` int(11) NOT NULL AUTO_INCREMENT,
        `form_title` varchar(255) NULL,
        `submit_button_title` varchar(50) NULL,
        `form_content` longtext NOT NULL,
        last_updated_at datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        PRIMARY KEY  (id)
    ) $charset_collate;";
    
    // Execute query to create the table
    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
    dbDelta($sql_create_table);
    
    // SQL query for inserting data into the table
    $sql_insert_data = "INSERT INTO $table_name (`form_title`, `submit_button_title`, `form_content`, `last_updated_at`) VALUES ('Enquiry Form', 'Save', '<h1> Enquiry Form </h1>\r\n\r\n<div>\r\n  <label>Your Name</label>\r\n  [customer_name placeholder=\"Enter your name\"  required]\r\n</div>\r\n\r\n<div>\r\n  <label>Your Email</label>\r\n  [email placeholder=\"Enter your email address\" ]\r\n</div>\r\n\r\n<div>\r\n  <label>Your Phone</label>\r\n  [phone placeholder=\"Enter Your phone\"]\r\n</div>\r\n\r\n<div>\r\n  <label>Enquiry Information</label>\r\n  [enquiry_information placeholder=\"Enter Your Enquiry Information\" required]\r\n</div>', NOW())";
    
    // Execute query to insert data into the table
    $wpdb->query($sql_insert_data);

 }
?>