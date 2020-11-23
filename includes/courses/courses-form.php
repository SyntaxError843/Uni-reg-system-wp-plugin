<?php

/**
 * 
 * Course Form HTML
 * 
 */
function course_form_html() {
    $result = get_course_if_updating();

    if( is_null( $result ) ) {
        echo '<div class="wrap"><h1>Course Not Found</h1></div>';
        exit;
    }

    if ( isset( $_GET['action'] ) && $_GET['action'] === 'delete' ) {
        ?>
            <div class="wrap">
                <h1>Delete Course</h1><hr />
                <form method="POST" action="#">
                    <p>Are you sure you want to delete <strong><?php echo $result->course_name . ' (' . $result->course_code . ')' ?></strong>?</p>

                    <?php submit_button( __( 'Delete Course' ), 'primary', 'course-delete-confirm', true, array( 'style' => 'background: #DC3232; color: #fff; border: 1px solid #DC3232; margin-left: 13px;' ) ); ?>
                </form>
            </div>
        <?php

        exit;
    }
    
    ?>

    <div class="wrap">

        <?php
        /**
         * Display the errors on top of everything
         */
        course_form_message();

        if( $result ) {
            ?>
                <h1>Edit Course</h1>
                <h3><?php echo $result->course_name . ' (' . $result->course_code . ')' ?></h3>
            <?php
        } else {
            ?>
                <h1>Add New Course</h1>
            <?php 
        }
        
        ?>
        <hr />
        <form method="POST" action="#" class="validate" novalidate="novalidate">

        <table class="form-table" role="presentation">

            <tr class="form-field form-required">
                <th scope="row">
                    <label for="course_code"><?php _e( 'Course Code' ); ?> <span class="description"><?php _e( '(required)' ); ?></span></label>
                </th>
                <td>
                    <input name="course_code"
                           type="text" 
                           id="course_code"
                           aria-required="true"
                           autocapitalize
                           autocorrect="off"
                           maxlength="255"
                           placeholder="Course Code"
                           <?php echo $result ? 'readonly' : '' ?> 
                           value="<?php echo $result ? $result->course_code : '' ?>" />
                </td>
            </tr>


            <tr class="form-field form-required">
                <th scope="row">
                    <label for="course_name"><?php _e( 'Course Name' ); ?> <span class="description"><?php _e( '(required)' ); ?></span></label>
                </th>
                <td>
                    <input name="course_name"
                           type="text"
                           id="course_name"
                           aria-required="true"
                           autocapitalize
                           autocorrect="off"
                           maxlength="255"
                           placeholder="Course Name"
                           value="<?php echo $result ? $result->course_name : '' ?>" />
                </td>
            </tr>
 
        </table>

        <div class="tablenav bottom">
			<div class="alignleft actions">
                <?php           
                    submit_button( $result ? __( 'Update Course' ) : __( 'Add Course' ), 'primary', 'course-form-submit', false );         
                    if ( $result ) submit_button( __( 'Delete Course' ), 'primary', 'course-delete-submit', false, array( 'style' => 'background: #DC3232; color: #fff; border: 1px solid #DC3232; margin-left: 13px;' ) );
                ?>
            </div>
        </div>
           
        </form>
    </div>

    <?php
}

/**
 * 
 * Course Form Logic
 * 
 */

/**
 * Course fetch if available
 */
function get_course_if_updating() {
    $result = '';

    if( isset( $_GET['id'] ) && $_GET['id'] !== '' ) {
        // echo "<script>console.log('ass');</script>";
        global $wpdb;

        $table_name = $wpdb->prefix . 'courses';
        $sql = $wpdb->prepare( "SELECT * FROM `$table_name` WHERE id = %d", (int) $_GET['id'] );
        $result = $wpdb->get_row( $sql );
    }

    return $result;
}

/**
 * Validate course form
 */
function validate_course_form()
    {        
        global $wpdb;
        
        $errors = new WP_Error();
        $courses_table = $wpdb->prefix . 'courses';

        if ( isset($_POST['course_code']) && $_POST['course_code'] == '' ) {
            $errors->add('course_code', 'Sorry, Course Code field is required.');
        }
        if ( isset($_POST['course_name']) && $_POST['course_name'] == '' ) {
            $errors->add('course_name', 'Sorry, Course Name field is required.');
        }

        if( ! isset($_GET['id']) && isset($_POST['course_code']) && $_POST['course_code'] !== '' ) {
            
            $query = $wpdb->prepare( "SELECT course_code
                                      FROM $courses_table
                                      WHERE `course_code` = %s", array( $_POST['course_code'] ) );

            $result = $wpdb->get_var( $query );

            if ( $result ) {
                $msg = sprintf( "Sorry, Course Code <strong>%s</strong> already exists.", $result );
                $errors->add( "course_code_exists", $msg );
            }
        }

       return $errors;
    }

/**
 * Sanitize course text fields
 */
function sanitize_course_form_text_field($input)
    {
        return trim(stripslashes(sanitize_text_field($input)));
    }

/**
 * Form output messages
 */
function course_form_message()
    {
        global $errors;
        
        if (is_wp_error($errors) && empty($errors->errors)) {
            ?>
                <div class="notice notice-info is-dismissible inline">
                    <p>
                        <?php echo isset( $_GET['id'] ) && $_GET['id'] !== '' ? 'Course updated successfully!' : 'Course added successfully!' ?>
                    </p>
                </div>
            <?php

            /**
             * Empty POST array
             */
            $_POST = '';

        } else {
            if (is_wp_error($errors) && !empty($errors->errors)) {
                $error_messages = $errors->get_error_messages();
                foreach ($error_messages as $message) {
                    ?>
                        <div class="notice notice-error inline">
                            <p>
                                <?php echo $message ?>
                            </p>
                        </div>
                    <?php
                }
            }
        }
    }

/**
 * Course form POST requests handling
 */
add_action('init', 'handle_course_form_post_requests');
function handle_course_form_post_requests() {
    if ( isset( $_POST['course-form-submit'] ) ) {

        global $errors;
        global $wpdb;

        $table_name = $wpdb->prefix . 'courses';
        $errors = validate_course_form();
            
        if ( empty($errors->errors) ) {

            $args = array(
                'course_name' => ucfirst(sanitize_course_form_text_field($_POST['course_name'])),
                );

            if ( ! isset( $_GET['id'] ) || $_GET['id'] == '' ) {
                $args['course_code'] = sanitize_course_form_text_field($_POST['course_code']);
                
                $default = array(
                    'course_code' => '',
                    'course_name' => '',
                );
                
                $record = wp_parse_args( $args, $default );
            }
              
            isset( $_GET['id'] ) && $_GET['id'] !== '' ? $wpdb->update( $table_name, $args, array( 'id' => $_GET['id'] ) ) : $wpdb->insert( $table_name, $record );
                
        } else {
            return $errors;
        }

    } elseif ( isset( $_POST['course-delete-submit'] ) ) {

        wp_redirect( '?page=course_form&action=delete&id=' . $_GET['id'] );
  
    } elseif ( isset( $_POST['course-delete-confirm'] ) ) {

        global $wpdb;

        $table_name = $wpdb->prefix . 'courses';
        $success = 'false';

        if ( $wpdb->delete( $table_name, array( 'id' => $_GET['id'] ) ) ) $success = 'true';

        wp_redirect( '?page=courses_list&action=delete&success=' . $success );

    }
}    