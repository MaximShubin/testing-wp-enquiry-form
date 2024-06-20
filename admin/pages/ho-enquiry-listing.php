<?php
/**
 * HO_Enquiry_List
 */
 
// Disable direct access
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
// Check ActualiseMe_Plans class 
if ( ! class_exists( 'HO_Enquiry_List', false ) ) :
    
if ( ! class_exists( 'WP_List_Table' ) ) {
    require_once( ABSPATH . 'wp-admin/includes/class-wp-list-table.php' );
}

class HO_Enquiry_List extends WP_List_Table {

    /** Class constructor */
    public function __construct() {
    
    parent::__construct( [
    'singular' => __( 'HOEnquirylist', 'ho-enquiry' ), //singular name of the listed records
    'plural' => __( 'HOEnquirylists', 'ho-enquiry' ), //plural name of the listed records
    'ajax' => false //should this table support ajax?
    
    ] );
    
    }

    /**
    * Retrieve user’s data from the database
    *
    * @param int $per_page
    * @param int $page_number
    *
    * @return mixed
    */
    public static function get_users( $per_page = 10, $page_number = 1 ) {

        global $wpdb;
        
        $sql = "SELECT * FROM {$wpdb->base_prefix}heypa_contacts";
		
        if ( ! empty( $_REQUEST['orderby'] ) ) {
        $sql .= ' ORDER BY ' . esc_sql( $_REQUEST['orderby'] );
        $sql .= ! empty( $_REQUEST['order'] ) ? ' ' . esc_sql( $_REQUEST['order'] ) : ' DESC';
        }else{
            $sql .=" ORDER BY last_updated_at ASC";
        }
		
        $sql .= " LIMIT $per_page";
        
        $sql .= ' OFFSET ' . ( $page_number - 1 ) * $per_page;
        $result = $wpdb->get_results( $sql, 'ARRAY_A' );

        $resultData = [];

        foreach($result as $res){
            $newData = [];
            
            $newData['id'] = $res['id'];
            $newData['title'] = $res['form_title'];

            $newData['created_at'] = $res['last_updated_at'] ? $res['last_updated_at'] : '';

            $resultData[] = $newData;

        }

        return $resultData;
    }


    

    /**
    * Returns the count of records in the database.
    *
    * @return null|string
    */
    public static function record_count() {
        global $wpdb;
        
        $sql = "SELECT COUNT(*) FROM {$wpdb->base_prefix}heypa_contacts";

        return $wpdb->get_var( $sql );
		
    }

    /** Text displayed when no users data is available */
    public function no_items() {
        _e( 'No Data avaliable.', 'ho-enquiry' );
     }


    /**
    * Render a column when no column specific method exists.
    *
    * @param array $item
    * @param string $column_name
    *
    * @return mixed
    */
    public function column_default( $item, $column_name ) {

        switch ( $column_name ) {

        case 'title':

        case 'shortcode':

        case 'author':

        case 'created_at':

        case 'action':

        return $item[ $column_name ];

        default:

        return print_r( $item, true ); //Show the whole array for troubleshooting purposes

        }

     }

    /**
    * Render the bulk edit checkbox
    * @param array $item
    * @return string
    */
    protected function column_cb( $item ) {
        return sprintf( '<input type="checkbox" name="%1$s[]" value="%2$s" />', 
                $this->_args['plural'], 
                $item['id'] );
    }

  
    /**
    * Associative array of columns
    *
    * @return array
    */
    function get_columns() {
        $columns = [
        'cb' => '<input type="checkbox" />',
        'title' => __( 'Title', 'ho-enquiry' ),
        'shortcode' => __( 'Shortcode', 'ho-enquiry' ),
        'author' => __( 'Author', 'ho-enquiry' ),
        'created_at' => __( 'Date', 'ho-enquiry' ),
        'action' => __( 'Action', 'ho-enquiry' ),
        ];
        
        return $columns;
    }
    public function single_row( $item ) {
        echo "<tr id='preffered-time'>";
        $this->single_row_columns( $item );
        echo '</tr>';
    }
    /**
    * Columns to make sortable.
    *
    * @return array
    */
    public function get_sortable_columns() {
        $sortable_columns = array(
        'preferred_time' => array( 'preferred_time', true )
        );
        
        return $sortable_columns;
    }
    

	protected function column_shortcode( $item ) {
        $id = $item['id'];
        $shortCode = "[Heypa-Form id=$id]";

        return   $shortCode;

    }
	protected function column_author( $item ) {
        $html = 'Heypa Online';
        return  $html;

    }
	protected function column_action( $item ) {

        global $wpdb;
        $actions = "<a href='".admin_url('admin.php?page=ho-enquiry&action=ho-form&id='.$item['id'].'')."' class='button'>Edit</a>";

        return  $actions ;

    }


    /**
     * Returns an associative array containing the bulk action
     * @return array
     */
    function get_bulk_actions() {
        $actions = array(
            'bulk-delete'    => 'Delete'
        );
        return $actions;
    }

    /**
     * Method to display filter links
     * @return array
     */
    function get_views(){
        $views = array();
        $params = array();
        $current = isset($_GET['name'])?$_GET['name']:'';
        
        if(isset($_GET['s']) && !empty($_GET['s'])) {
            $params['search'] = $_GET['s'];
        }
        
        $count_all = $this->record_count($params);
        // clean up url
        $url = esc_url(remove_query_arg(array('name', 'is_trash','action')));
        
        //All users
        $class = ($current == '' && !isset($_GET['is_trash'])? ' class="current"' :'');
        
        $views['all'] = "<a href='{$url }' {$class} >All({$count_all})</a>";
    
       
        return $views;
    }

    /**
    * Handles data query and filter, sorting, and pagination.
    */
    public function prepare_items() {
        global $wpdb;
        
        $hidden = array();
        $columns = $this->get_columns();
        $sortable = $this->get_sortable_columns();
        $this->_column_headers = array( $columns, $hidden, $sortable );
        $where_clause = array();
        $params = array();
        $args = array();
        $this->process_bulk_action();
        
        $per_page = $this->get_items_per_page( 'users_per_page', 5 );
        $current_page = $this->get_pagenum();
        $total_items = self::record_count();
        
        $this->set_pagination_args( [
        'total_items' => $total_items, 
        'per_page' => $per_page 
        ] );
        
        $this->items = self::get_users( $per_page, $current_page );
    }


    /**
     * Handle the bulk action
     */
    function process_bulk_action() {
        global $wpdb;
        $tableName = $wpdb->base_prefix.'heypa_contacts';
        if ( current_user_can('edit_posts') && isset( $_REQUEST['_wpnonce'] ) && wp_verify_nonce( $_REQUEST['_wpnonce'], 'bulk-' . $this->_args['plural'] ) && is_array($_REQUEST['hoenquirylists']) && count($_REQUEST['hoenquirylists']) > 0) {
        
            $ids_array = $_REQUEST['hoenquirylists'];
            $ids =  implode(',', $_REQUEST['hoenquirylists']);
            if ( 'bulk-delete' === $this->current_action() ) {

                $wpdb->query("DELETE FROM $tableName WHERE id IN($ids)" ); 
    
            }
            HOEnquiry_Notice::success('Form has been deleted Successfully');
        }
    }

     /**
     * Display content
     */
    public function display_content() {

        ?>
        <div class="wrap ho-enquiry admin-wrapper-contact-list">
             <h1 class="wp-heading-inline">
                <?php _e( 'Enquiry Contact Listing', 'ho-enquiry' ); ?>
            </h1>
            
            <hr class="wp-header-end">
            <?php $this->views(); 
            ?>
             <form method="POST" >
                 <?php
                $this->prepare_items();
                $this->display(); ?>
             </form>
             
        </div>
        <?php 
    
    }
    
}
endif;
$OpContactListTable = new HO_Enquiry_List();
$OpContactListTable->display_content();
?>