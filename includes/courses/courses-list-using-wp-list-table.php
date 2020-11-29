<?php

if( ! class_exists( 'WP_List_Table' ) ) {
    require_once( ABSPATH . 'wp-admin/includes/class-wp-list-table.php' );
}

class Courses_List_Table extends WP_List_Table {
    
    function __construct() {
        global $status, $page;
                
        //Set parent defaults
        parent::__construct( array(

            'singular'  => 'course',     //singular name of the listed records
            'plural'    => 'courses',    //plural name of the listed records
            'ajax'      => false          //does this table support ajax?

        ) );
        
    }

    function get_columns() {
        $columns = array(

            'cb'                => '<input type="checkbox" />', //Render a checkbox instead of text
            'course_name'      => 'Course Name',
            'course_code'      => 'Course Code',

        );

        return $columns;
    }
    
    function column_default( $result, $column_name ) {
        switch( $column_name ) {
            case 'course_code':
            case 'phone_number':
            case 'email':
            case 'course_password':
            case 'date_of_birth':
            case 'date_created':
                return $result->$column_name;

            default:
                return print_r( $result, true ); //Show the whole array for troubleshooting purposes
        }
    }

    function column_cb( $result ){
        return sprintf(

            '<input type="checkbox" name="%1$s[]" value="%2$s" />',
            /*$1%s*/ $this->_args['plural'],    //Let's simply repurpose the table's plural label ("courses")
            /*$2%s*/ $result->id               //The value of the checkbox should be the record's id

        );
    }

    function column_course_name( $result ){
        
        //Build row actions
        $actions = array(

            'edit'      => sprintf('<a href="?page=%s&action=%s&id=%s">Edit</a>','course_form','edit',$result->id),
            'delete'    => sprintf('<a href="?page=%s&action=%s&id=%s">Delete</a>','course_form','delete',$result->id),

        );
        
        //Return the course's full name
        return sprintf(

            '<strong>%1$s</strong> <span style="color:silver">(id:%2$s)</span>%3$s',
            /*$1%s*/ $result->course_name,
            /*$2%s*/ $result->id,
            /*$3%s*/ $this->row_actions($actions)

        );
    }

    function get_sortable_columns() {

        $sortable_columns = array(

            'course_name'      => array( 'course_name', false ),     //true means it's already sorted
            'course_code'      => array( 'course_code', false ),
        );

        return $sortable_columns;
    }

    function get_bulk_actions() {
        $actions = array(

            'bulk_delete'    => 'Delete',

        );

        return $actions;
    }

    function process_bulk_action() {
        
        // Detect when a bulk action is being triggered...
        if( 'bulk_delete' === $this->current_action() ) {
            wp_die('Courses deleted! (or they would be if i actually coded this in... lets just pretend for now :D)');
        }
        
    }

    function prepare_items() {
        global $wpdb; //This is used only if making any database queries

        $per_page = 5;
        $columns = $this->get_columns();
        $hidden = array();
        $sortable = $this->get_sortable_columns();

        
        $this->_column_headers = array($columns, $hidden, $sortable);
        $this->process_bulk_action();
        
        
        $table_name = $wpdb->prefix . 'courses';
        $orderby = ( ! empty( $_REQUEST['orderby'] ) ) ? $_REQUEST['orderby'] : 'course_name';   //If no sort, default to course_name
        $order = ( ! empty($_REQUEST['order'] ) ) ? $_REQUEST['order'] : 'asc';                  //If no order, default to asc

        $sql = "SELECT * FROM $table_name ORDER BY $orderby $order";
        $data = $wpdb->get_results( $sql );
        
        $current_page = $this->get_pagenum();
        $total_items = count($data);
        $data = array_slice(
            $data,
            ( ( $current_page - 1 ) * $per_page ),
            $per_page
        );

        
        $this->items = $data;
        $this->set_pagination_args(
            array(

                'total_items' => $total_items,                  // We have to calculate the total number of items
                'per_page'    => $per_page,                     // We have to determine how many items to show on a page
                'total_pages' => ceil($total_items/$per_page)   // We have to calculate the total number of pages

            )
        );
    }


}

function courses_render_list_page(){
    
    //Create an instance of our package class...
    $coursesListTable = new Courses_List_Table();
    //Fetch, prepare, sort, and filter our data...
    $coursesListTable->prepare_items();
    
    ?>

    <?php
        if ( isset( $_GET['action'] ) && $_GET['action'] === 'delete' ) {
            if ( isset( $_GET['success'] ) && $_GET['success'] === 'true' ) {
                ?>
                    <div class="notice notice-info is-dismissible inline">
                        <p>Course Deleted Successfully!</p>
                    </div>
                <?php
            } else {
                ?>
                    <div class="notice notice-error inline">
                        <p>Something Went Wrong!</p>
                    </div>
                <?php
            }
        }
    ?>

    <div class="wrap">
        
        <h1 class="wp-heading-inline">Courses List</h1><a href="?page=course_form" class="page-title-action">Add New</a><hr />
        
        <div style="background:#ECECEC;border:1px solid #CCC;padding:0 10px;margin-top:5px;border-radius:5px;-moz-border-radius:5px;-webkit-border-radius:5px;">
            <p>This list was generated using the WP_List_Table WordPress core class!</p>
        </div>
        
        <!-- Forms are NOT created automatically, so you need to wrap the table in one to use features like bulk actions -->
        <form id="movies-filter" method="get">
            <!-- For plugins, we also need to ensure that the form posts back to our current page -->
            <input type="hidden" name="page" value="<?php echo $_REQUEST['page'] ?>" />
            <!-- Now we can render the completed list table -->
            <?php $coursesListTable->display() ?>
        </form>
        
    </div>
    <?php
}