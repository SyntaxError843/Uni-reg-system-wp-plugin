<?php

/**
 * 
 * Registration Form HTML
 * 
 */
function registration_form_html() {

    $result = get_registration_if_updating();
    if( is_null( $result ) ) {
        echo '<div class="wrap"><h1>Student Course Registration Not Found</h1></div>';

        exit;
    }
    
    if ( isset( $_GET['action'] ) && $_GET['action'] === 'delete' ) {
        ?>
            <div class="wrap">
                <h1>Delete Registration</h1><hr />
                <form method="POST" action="#">
                    <p>Are you sure you want to delete student 
                        <strong><?php echo $result->first_name . ' ' . $result->father_name[0] . '. ' . $result->last_name . ' (' . $result->student_code . ')' ?></strong>'s
                        registration in course
                        <strong><?php echo $result->course_name . ' (' . $result->course_code . ')' ?></strong>
                        ?
                    </p>
                        
                    <?php submit_button( __( 'Delete Registration' ), 'primary', 'registration-delete-confirm', true, array( 'style' => 'background: #DC3232; color: #fff; border: 1px solid #DC3232; margin-left: 13px;' ) ); ?>
                </form>
            </div>
        <?php  

        exit;
    }

    $students = get_students();
    if( ! $students ) {
        echo '<div class="wrap"><h1>No Students Available</h1></div>';

        exit;
    }

    $courses = get_courses();
    if( ! $courses ) {
        echo '<div class="wrap"><h1>No Courses Availalbe</h1></div>';

        exit;
    }

    
    ?>

    <div class="wrap">

        <?php
        /**
         * Display the errors on top of everything
         */
        registration_form_message();

        if( $result ) {
            ?>
                <h1>Edit Course Registrations</h1>
            <?php
        } else {
            ?>
                <h1>Add New Registration</h1>
            <?php 
        }
        
        ?>
        <hr />
        <form method="POST" action="#" class="validate" novalidate="novalidate">

        <table class="form-table" role="presentation">

            <tr class="form-field form-required">
                <th scope="row">
                    <label for="student_id"><?php _e( 'Student' ); ?> <span class="description"><?php _e( '(required)' ); ?></span></label>
                </th>
                <td>
                    <select name="student_id" id="student_id" <?php echo $result ? 'disabled' : '' ?>>
                        <?php
                            foreach( $students as $student ) {
                                ?>
                                    <option value="<?php echo $student->id ?>"
                                            <?php echo isset( $_GET['student_id'] ) && $_GET['student_id'] === $student->id ? 'selected' : ($result ? ($result->student_id === $student->id ? 'selected' : '') : '') ?>
                                    >
                                            <?php echo $student->first_name . ' ' . $student->father_name[0] . '. ' . $student->last_name . ' (' . $student->student_code . ')' ?>
                                    </option>
                                <?php
                            }
                        ?>
                    </select>
                </td>
            </tr>

            <tr class="form-field form-required">
                <th scope="row">
                    <label for="course_id"><?php _e( 'Course' ); ?> <span class="description"><?php _e( '(required)' ); ?></span></label>
                </th>
                <td>
                    <select name="course_id" id="course_id">
                        <?php
                            foreach( $courses as $course ) {
                                ?>
                                    <option value="<?php echo $course->id ?>"
                                            <?php echo $result ? ($result->course_id === $course->id ? 'selected' : '') : '' ?>
                                    >

                                        <?php echo $course->course_name . ' (' . $course->course_code . ')' ?>

                                    </option>
                                <?php
                            }
                        ?>
                    </select>
                </td>
            </tr>
 
        </table>

        <div class="tablenav bottom">
			<div class="alignleft actions">
                <?php           
                    submit_button( $result ? __( 'Update Registration' ) : __( 'Add Registration' ), 'primary', 'registration-form-submit', false );         
                    if ( $result ) submit_button( __( 'Delete Registration' ), 'primary', 'registration-delete-submit', false, array( 'style' => 'background: #DC3232; color: #fff; border: 1px solid #DC3232; margin-left: 13px;' ) );
                ?>
            </div>
        </div>
           
        </form>
    </div>

    <?php
}

/**
 * 
 * Registration Form Logic
 * 
 */

/**
 * Registration fetch if available
 */
function get_registration_if_updating() {
    $result = '';

    if( isset( $_GET['id'] ) && $_GET['id'] !== '' ) {
        // echo "<script>console.log('ass');</script>";
        global $wpdb;

        $students_table = $wpdb->prefix . 'students';
        $courses_table = $wpdb->prefix . 'courses';
        $registrations_table = $wpdb->prefix . 'registrations';

        $sql = $wpdb->prepare( "
            SELECT student_id, student_code, first_name, father_name, last_name, course_id, course_code, course_name
            FROM $registrations_table, $students_table, $courses_table
            WHERE $students_table.`id` = $registrations_table.`student_id`
            AND $courses_table.`id` = $registrations_table.`course_id`
            AND $registrations_table.`id` = %d
        ", 
            (int) $_GET['id']
        );

        $result = $wpdb->get_row( $sql );
    }

    return $result;
}

/**
 * Students prefetch to fill combobox
 */
function get_students() {
    global $wpdb;

    $table_name = $wpdb->prefix . 'students';
    $sql = "SELECT id, student_code, first_name, father_name, last_name FROM $table_name";

    return $wpdb->get_results( $sql );
}

/**
 * Courses prefetch to fill combobox
 */
function get_courses() {
    global $wpdb;

    $table_name = $wpdb->prefix . 'courses';
    $sql = "SELECT id, course_code, course_name FROM $table_name";

    return $wpdb->get_results( $sql );
}

/**
 * Validate registration form
 */
function validate_registration_form()
    {        
        global $wpdb;

        $errors = new WP_Error();
        $registrations_table = $wpdb->prefix . 'registrations';
        $students_table = $wpdb->prefix . 'students';
        $courses_table = $wpdb->prefix . 'courses';

        if ( isset($_POST['student_id']) && $_POST['student_id'] == '' ) {
            $errors->add('student_id', 'Sorry, Student field is required.');
        }
        if ( isset($_POST['course_id']) && $_POST['course_id'] == '' ) {
            $errors->add('course_id', 'Sorry, Course field is required.');
        }
        if( isset($_POST['student_id']) && $_POST['student_id'] !== '' && isset($_POST[ 'course_id' ]) && $_POST[ 'course_id' ] !== '' ) {
            
            $query = $wpdb->prepare( "SELECT first_name, father_name, last_name, course_name
                                      FROM $registrations_table, $students_table, $courses_table 
                                      WHERE $students_table.`id` = $registrations_table.`student_id` 
                                      AND $courses_table.`id` = $registrations_table.`course_id` 
                                      AND `student_id` = %s 
                                      AND `course_id` = %s", array( $_POST['student_id'], $_POST['course_id'] ) );

            $result = $wpdb->get_row( $query );

            if ( $result ) {
                $msg = sprintf( "Sorry, Student <strong>%s</strong> is already registered in course <strong>%s</strong>",
                                $result->first_name . ' ' . $result->father_name[0] . '. ' . $result->last_name,
                                $result->course_name);

                $errors->add( "registration_exists", $msg );
            }
        }

       return $errors;
    }

/**
 * Form output messages
 */
function registration_form_message()
    {
        global $errors;
        
        if (is_wp_error($errors) && empty($errors->errors)) {
            ?>
                <div class="notice notice-info is-dismissible inline">
                    <p>
                        <?php echo isset( $_GET['id'] ) && $_GET['id'] !== '' ? 'Registration updated successfully!' : 'Registration added successfully!' ?>
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
 * Registration form POST requests handling
 */
add_action('init', 'handle_registration_form_post_requests');
function handle_registration_form_post_requests() {
    if ( isset( $_POST['registration-form-submit'] ) ) {

        global $errors;
        global $wpdb;

        $table_name = $wpdb->prefix . 'registrations';
        $errors = validate_registration_form();
            
        if ( empty($errors->errors) ) {

            if ( isset( $_GET['id'] ) && $_GET['id'] !== '' ) {

                $args = array(
                    'course_id' => $_POST['course_id'],
                    );

            } else {

                $args = array(
                    'student_id' => $_POST['student_id'],
                    'course_id' => $_POST['course_id'],
                    );

            }
              
            isset( $_GET['id'] ) && $_GET['id'] !== '' ? $wpdb->update( $table_name, $args, array( 'id' => $_GET['id'] ) ) : $wpdb->insert( $table_name, $args );
                
        } else {
            return $errors;
        }

    } elseif ( isset( $_POST['registration-delete-submit'] ) ) {

        wp_redirect( '?page=registration_form&action=delete&id=' . $_GET['id'] );
  
    } elseif ( isset( $_POST['registration-delete-confirm'] ) ) {

        global $wpdb;

        $table_name = $wpdb->prefix . 'registrations';
        $success = 'false';

        if ( $wpdb->delete( $table_name, array( 'id' => $_GET['id'] ) ) ) $success = 'true';

        wp_redirect( '?page=registrations_list&action=delete&success=' . $success );

    }
}    