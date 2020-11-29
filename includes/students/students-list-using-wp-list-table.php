<?php

if( ! class_exists( 'WP_List_Table' ) ) {
    require_once( ABSPATH . 'wp-admin/includes/class-wp-list-table.php' );
}

class Students_List_Table extends WP_List_Table {
    
    function __construct() {
        global $status, $page;
                
        //Set parent defaults
        parent::__construct( array(

            'singular'  => 'student',     //singular name of the listed records
            'plural'    => 'students',    //plural name of the listed records
            'ajax'      => false          //does this table support ajax?

        ) );
        
    }

    function get_columns() {
        $columns = array(

            'cb'                => '<input type="checkbox" />', //Render a checkbox instead of text
            'student_name'      => 'Student Name',
            'student_code'      => 'Student Code',
            'phone_number'      => 'Phone Number',
            'email'             => 'Email',
            'student_password'  => 'Password Hash',
            'date_of_birth'     => 'Date of Birth',
            'date_created'      => 'Date Created',

        );

        return $columns;
    }
    
    function column_default( $result, $column_name ) {
        switch( $column_name ) {
            case 'student_code':
            case 'phone_number':
            case 'email':
            case 'student_password':
            case 'date_created':
                return $result->$column_name;

            default:
                return print_r( $result, true ); //Show the whole array for troubleshooting purposes
        }
    }

    function column_cb( $result ){
        return sprintf(

            '<input type="checkbox" name="%1$s[]" value="%2$s" />',
            /*$1%s*/ $this->_args['plural'],    //Let's simply repurpose the table's plural label ("students")
            /*$2%s*/ $result->id               //The value of the checkbox should be the record's id

        );
    }

    function column_student_name( $result ){
        
        //Build row actions
        $actions = array(

            'edit'      => sprintf('<a href="?page=%s&action=%s&id=%s">Edit</a>','student_form','edit',$result->id),
            'delete'    => sprintf('<a href="?page=%s&action=%s&id=%s">Delete</a>','student_form','delete',$result->id),

        );
        
        //Return the student's full name
        return sprintf(

            '<strong>%1$s %2$s. %3$s</strong> <span style="color:silver">(id:%4$s)</span>%5$s',
            /*$1%s*/ $result->first_name,
            /*$2%s*/ $result->father_name[0],
            /*$3%s*/ $result->last_name,
            /*$4%s*/ $result->id,
            /*$5%s*/ $this->row_actions($actions)

        );
    }

    function column_date_of_birth( $result ){
        return mb_substr( $result->date_of_birth, 0, 10 );
    }

    function get_sortable_columns() {

        $sortable_columns = array(

            'student_name'      => array( 'student_name', false ),     //true means it's already sorted
            'student_code'      => array( 'student_code', false ),
            'date_of_birth'     => array( 'date_of_birth', false ),
            'date_created'      => array( 'date_created', false ),
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
        
        //Detect when a bulk action is being triggered...
        if( 'bulk_delete' === $this->current_action() ) {
            wp_die('Students deleted! (or they would be if i actually coded this in... lets just pretend for now :D)');
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
        
        
        $table_name = $wpdb->prefix . 'students';
        $orderby = ( ! empty( $_REQUEST['orderby'] ) ) ? ( $_REQUEST['orderby'] === 'student_name' ? 'first_name' : $_REQUEST['orderby'] ) : 'first_name';   //If no sort, default to first_name
        $order = ( ! empty($_REQUEST['order'] ) ) ? $_REQUEST['order'] : 'asc';                                                                              //If no order, default to asc

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

function students_render_list_page(){
    
    //Create an instance of our package class...
    $studentsListTable = new Students_List_Table();
    //Fetch, prepare, sort, and filter our data...
    $studentsListTable->prepare_items();
    
    ?>

    <?php
        if ( isset( $_GET['action'] ) && $_GET['action'] === 'delete' ) {
            if ( isset( $_GET['success'] ) && $_GET['success'] === 'true' ) {
                ?>
                    <div class="notice notice-info is-dismissible inline">
                        <p>Student Deleted Successfully!</p>
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
        
        <h1 class="wp-heading-inline">Students List</h1><a href="?page=student_form" class="page-title-action">Add New</a><hr />
        
        <div style="background:#ECECEC;border:1px solid #CCC;padding:0 10px;margin-top:5px;border-radius:5px;-moz-border-radius:5px;-webkit-border-radius:5px;">
            <p>This list was generated using the WP_List_Table WordPress core class!</p>
        </div>
        
        <!-- Forms are NOT created automatically, so you need to wrap the table in one to use features like bulk actions -->
        <form id="movies-filter" method="get">
            <!-- For plugins, we also need to ensure that the form posts back to our current page -->
            <input type="hidden" name="page" value="<?php echo $_REQUEST['page'] ?>" />
            <!-- Now we can render the completed list table -->
            <?php $studentsListTable->display() ?>
        </form>
        
    </div>
    
    <?php
}