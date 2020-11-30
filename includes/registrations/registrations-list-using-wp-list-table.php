<?php

if( ! class_exists( 'WP_List_Table' ) ) {
    require_once( ABSPATH . 'wp-admin/includes/class-wp-list-table.php' );
}

class Registrations_List_Table extends WP_List_Table {
    
    function __construct() {
        global $status, $page;
                
        //Set parent defaults
        parent::__construct( array(

            'singular'  => 'registration',     //singular name of the listed records
            'plural'    => 'registrations',    //plural name of the listed records
            'ajax'      => false               //does this table support ajax?

        ) );
        
    }

    function get_columns() {
        $columns = array(

            'student'                   => 'Student',
            'registered_courses'        => 'Registered Courses',

        );

        return $columns;
    }

    function column_student( $result ){
        
        //Build row actions
        $actions = array(

            'edit' => sprintf( '<a href="?page=%s&action=%s&student_id=%s">Add Courses To This Student</a>', 'registration_form', 'edit', $result->student_id ),

        );
        
        //Return the student's full name
        return sprintf(

            '<strong>%1$s %2$s. %3$s (%4$s)</strong> <span style="color:silver">(id:%5$s)</span>%6$s',
            /*$1%s*/ $result->first_name,
            /*$2%s*/ $result->father_name[0],
            /*$3%s*/ $result->last_name,
            /*$4%s*/ $result->student_code,
            /*$5%s*/ $result->student_id,
            /*$6%s*/ $this->row_actions($actions)

        );
    }

    function column_registered_courses( $result ){

        $registration_ids = explode( ',', $result->registration_ids );
        $registered_course_codes = explode( ',', $result->registered_course_codes );

        $registrations_list = '<ul style="list-style-type: disc; margin-left: 25px">';

        // Loop through the registered courses and build the list
        foreach( explode( ",", $result->registered_courses ) as $index => $course ) {
        
            // Build row actions for course registration number: #index
            $actions = array(

                'edit'   => sprintf('<a href="?page=%s&action=%s&id=%s">Edit</a>', 'registration_form', 'edit', $registration_ids[$index]),
                'delete' => sprintf('<a href="?page=%s&action=%s&id=%s">Delete</a>','registration_form', 'delete', $registration_ids[$index]),

            );

            // Appened the registration to the list
            $registrations_list .= sprintf( 
                                            
                                            '<li>%1$s (%2$s)%3$s</li>',
                                            /*$1%s*/ $course,
                                            /*$2%s*/ $registered_course_codes[$index],
                                            /*$2%s*/ $this->row_actions($actions)
                                        
                                          );
        }

        // Close the list and return it
        $registrations_list .= '</ul>';

        return $registrations_list;
    }

    function get_sortable_columns() {

        $sortable_columns = array(

            'student' => array( 'student', false ), //true means it's already sorted
        
        );

        return $sortable_columns;
    }

    function prepare_items() {
        global $wpdb; //This is used only if making any database queries

        $per_page = 5;
        $columns = $this->get_columns();
        $hidden = array();
        $sortable = $this->get_sortable_columns();

        
        $this->_column_headers = array($columns, $hidden, $sortable);
        
        
        $students_table = $wpdb->prefix . 'students';
        $courses_table = $wpdb->prefix . 'courses';
        $registrations_table = $wpdb->prefix . 'registrations';
        $orderby = ( ! empty( $_REQUEST['orderby'] ) ) ? 'first_name' : 'first_name';           //If no sort, default to first_name
        $order = ( ! empty($_REQUEST['order'] ) ) ? $_REQUEST['order'] : 'asc';                 //If no order, default to asc
        
        
        $sql = "
            SELECT student_id, student_code, first_name, father_name, last_name, GROUP_CONCAT(course_name) AS registered_courses, GROUP_CONCAT(course_code) AS registered_course_codes, GROUP_CONCAT($registrations_table.`id`) AS registration_ids
            FROM $registrations_table, $students_table, $courses_table
            WHERE $students_table.`id` = $registrations_table.`student_id`
            AND $courses_table.`id` = $registrations_table.`course_id`
            GROUP BY student_id
            ORDER BY $orderby $order;
        ";

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

function registrations_render_list_page(){
    
    //Create an instance of our package class...
    $registrationsListTable = new Registrations_List_Table();
    //Fetch, prepare, sort, and filter our data...
    $registrationsListTable->prepare_items();
    
    ?>

    <?php
        if ( isset( $_GET['action'] ) && $_GET['action'] === 'delete' ) {
            if ( isset( $_GET['success'] ) && $_GET['success'] === 'true' ) {
                ?>
                    <div class="notice notice-info is-dismissible inline">
                        <p>Course Registration Deleted Successfully!</p>
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
        
        <h1 class="wp-heading-inline">Course Registrations List</h1><a href="?page=registration_form" class="page-title-action">Add New</a><hr />
        
        <div style="background:#ECECEC;border:1px solid #CCC;padding:0 10px;margin-top:5px;border-radius:5px;-moz-border-radius:5px;-webkit-border-radius:5px;">
            <p>This list was generated using the WP_List_Table WordPress core class!</p>
        </div>
        
        <!-- Forms are NOT created automatically, so you need to wrap the table in one to use features like bulk actions -->
        <form id="movies-filter" method="get">
            <!-- For plugins, we also need to ensure that the form posts back to our current page -->
            <input type="hidden" name="page" value="<?php echo $_REQUEST['page'] ?>" />
            <!-- Now we can render the completed list table -->
            <?php $registrationsListTable->display() ?>
        </form>
        
    </div>
    <?php
}