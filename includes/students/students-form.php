<?php

/**
 * 
 * Student Form HTML
 * 
 */
function student_form_html() {
    $result = get_student_if_updating();

    if( is_null( $result ) ) {
        echo '<div class="wrap"><h1>Student Not Found</h1></div>';
        exit;
    }

    if ( isset( $_GET['action'] ) && $_GET['action'] === 'delete' ) {
        ?>
            <div class="wrap">
                <h1>Delete Student</h1><hr />
                <form method="POST" action="#">
                    <p>Are you sure you want to delete <strong><?php echo $result->first_name . ' ' . $result->father_name[0] . '. ' . $result->last_name . ' (' . $result->student_code . ')' ?></strong>?</p>

                    <?php submit_button( __( 'Delete Student' ), 'primary', 'student-delete-confirm', true, array( 'style' => 'background: #DC3232; color: #fff; border: 1px solid #DC3232; margin-left: 13px;' ) ); ?>
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
        student_form_message();

        if( $result ) {
            ?>
                <h1>Edit Student</h1>
                <h3><?php echo $result->first_name . ' ' . $result->father_name[0] . '. ' . $result->last_name . ' (' . $result->student_code . ')' ?></h3>
            <?php
        } else {
            ?>
                <h1>Add New Student</h1>
            <?php 
        }
        
        ?>
        <hr />
        <form method="POST" action="#" class="validate" novalidate="novalidate">

        <table class="form-table" role="presentation">

            <tr class="form-field form-required">
                <th scope="row">
                    <label for="student_code"><?php _e( 'Student Code' ); ?> <span class="description"><?php _e( '(required)' ); ?></span></label>
                </th>
                <td>
                    <input name="student_code"
                           type="text" 
                           id="student_code"
                           aria-required="true"
                           autocapitalize
                           autocorrect="off"
                           maxlength="255"
                           placeholder="Student Code"
                           <?php echo $result ? 'readonly' : '' ?> 
                           value="<?php echo $result ? $result->student_code : '' ?>" />
                </td>
            </tr>


            <tr class="form-field form-required">
                <th scope="row">
                    <label for="first_name"><?php _e( 'First Name' ); ?> <span class="description"><?php _e( '(required)' ); ?></span></label>
                </th>
                <td>
                    <input name="first_name"
                           type="text"
                           id="first_name"
                           aria-required="true"
                           autocapitalize
                           autocorrect="off"
                           maxlength="255"
                           placeholder="First Name"
                           value="<?php echo $result ? $result->first_name : '' ?>" />
                </td>
            </tr>


            <tr class="form-field form-required">
                <th scope="row">
                    <label for="father_name"><?php _e( 'Father Name' ); ?> <span class="description"><?php _e( '(required)' ); ?></span></label>
                </th>
                <td>
                    <input name="father_name"
                           type="text"
                           id="father_name"
                           aria-required="true"
                           autocapitalize
                           autocorrect="off"
                           maxlength="255"
                           placeholder="Father Name" 
                           value="<?php echo $result ? $result->father_name : '' ?>" />
                </td>
            </tr>


            <tr class="form-field form-required">
                <th scope="row">
                    <label for="last_name"><?php _e( 'Last Name' ); ?> <span class="description"><?php _e( '(required)' ); ?></span></label>
                </th>
                <td>
                    <input name="last_name"
                           type="text"
                           id="last_name"
                           aria-required="true"
                           autocapitalize
                           autocorrect="off"
                           maxlength="255"
                           placeholder="Last Name" 
                           value="<?php echo $result ? $result->last_name : '' ?>" />
                </td>
            </tr>


            <tr class="form-field form-required">
                <th scope="row">
                    <label for="phone_number"><?php _e( 'Phone Number' ); ?></label>
                </th>
                <td>
                    <input name="phone_number" 
                           type="text"
                           id="phone_number"
                           aria-required="true"
                           autocorrect="off"
                           maxlength="255"
                           placeholder="Phone Number"
                           value="<?php echo $result ? $result->phone_number : '' ?>" />
                </td>
            </tr>


            <tr class="form-field form-required">
                <th scope="row">
                    <label for="email"><?php _e( 'Email' ); ?></label>
                </th>
                <td>
                    <input name="email"
                           type="email"
                           id="email"
                           placeholder="Email"
                           value="<?php echo $result ? $result->email : '' ?>" />
                </td>
            </tr>


            <tr class="form-field form-required">
                <th scope="row">
                    <label for="student_password">
                        <?php echo $result ? _e( 'New Password' ) : _e( 'Password' ) ?>
                        <span class="description"><?php echo $result ? '' : _e( '(required)' ); ?></span>
                    </label>
                </th>
                <td>
                    <input name="student_password"
                           type="password"
                           id="student_password"
                           aria-required="true"
                           autocorrect="off"
                           maxlength="255"
                           placeholder="<?php echo $result ? _e( 'New Password' ) : _e( 'Password' ) ?>" />
                </td>
            </tr>


            <tr class="form-field form-required">
                <th scope="row">
                    <label for="date_of_birth"><?php _e( 'Date of Birth' ); ?> <span class="description"><?php _e( '(required)' ); ?></span></label>
                </th>
                <td>
                    <input name="date_of_birth" 
                           type="date"
                           id="date_of_birth"
                           aria-required="true"
                           value="<?php echo $result ? substr( $result->date_of_birth, 0, 10 ) : '' ?>" />
                </td>
            </tr>
 
        </table>

        <div class="tablenav bottom">
			<div class="alignleft actions">
                <?php           
                    submit_button( $result ? __( 'Update Student' ) : __( 'Add Student' ), 'primary', 'student-form-submit', false );         
                    if ( $result ) submit_button( __( 'Delete Student' ), 'primary', 'student-delete-submit', false, array( 'style' => 'background: #DC3232; color: #fff; border: 1px solid #DC3232; margin-left: 13px;' ) );
                ?>
            </div>
        </div>
           
        </form>
    </div>

    <?php
}

/**
 * 
 * Student Form Logic
 * 
 */

/**
 * Student fetch if available
 */
function get_student_if_updating() {
    $result = '';

    if( isset( $_GET['id'] ) && $_GET['id'] !== '' ) {
        // echo "<script>console.log('ass');</script>";
        global $wpdb;

        $table_name = $wpdb->prefix . 'students';
        $sql = $wpdb->prepare( "SELECT * FROM `$table_name` WHERE id = %d", (int) $_GET['id'] );
        $result = $wpdb->get_row( $sql );
    }

    return $result;
}

/**
 * Validate student form
 */
function validate_student_form()
    {        
        global $wpdb;
        
        $errors = new WP_Error();
        $students_table = $wpdb->prefix . 'students';

        if ( isset($_POST['student_code']) && $_POST['student_code'] == '' ) {
            $errors->add('student_code', 'Sorry, Student Code field is required.');
        }
        if ( isset($_POST['first_name']) && $_POST['first_name'] == '' ) {
            $errors->add('first_name', 'Sorry, First Name field is required.');
        }
        if ( isset($_POST['father_name']) && $_POST['father_name'] == '' ) {
            $errors->add('father_name', 'Sorry, Father Name field is required.');
        }
        if ( isset($_POST['last_name']) && $_POST['last_name'] == '' ) {
            $errors->add('last_name', 'Sorry, Last Name field is required.');
        }
        if ( isset($_POST['student_password']) && $_POST['student_password'] == '' && ! isset( $_GET['id'] ) ){
            $errors->add('student_password', 'Sorry, Password field is required.');
        }
        if ( isset($_POST['date_of_birth']) && $_POST['date_of_birth'] == '' ) {
            $errors->add('date_of_birth', 'Sorry, Date of Birth field is required.');
        }
        if ( isset($_POST['date_of_birth']) && $_POST['date_of_birth'] >= date("Y-m-d") ) {
            $errors->add('date_of_birth', 'Sorry, Student can\'t be born today (or in the future).');
        }

        if( ! isset( $_GET['id'] ) && isset( $_POST['student_code'] ) && $_POST['student_code'] !== '' ) {
            
            $query = $wpdb->prepare( "SELECT student_code
                                      FROM $students_table
                                      WHERE `student_code` = %s", array( $_POST['student_code'] ) );

            $result = $wpdb->get_var( $query );

            if ( $result ) {
                $msg = sprintf( "Sorry, Student Code <strong>%s</strong> already exists.", $result );
                $errors->add( "student_code_exists", $msg );
            }
        }

       return $errors;
    }

/**
 * Sanitize student text fields
 */
function sanitize_student_form_text_field($input)
    {
        return trim(stripslashes(sanitize_text_field($input)));
    }

/**
 * Form output messages
 */
function student_form_message()
    {
        global $errors;
        
        if (is_wp_error($errors) && empty($errors->errors)) {
            ?>
                <div class="notice notice-info is-dismissible inline">
                    <p>
                        <?php echo isset( $_GET['id'] ) && $_GET['id'] !== '' ? 'Student updated successfully!' : 'Student added successfully!' ?>
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
 * Student form POST requests handling
 */
add_action('init', 'handle_student_form_post_requests');
function handle_student_form_post_requests() {
    if ( isset( $_POST['student-form-submit'] ) ) {

        global $errors;
        global $wpdb;

        $table_name = $wpdb->prefix . 'students';
        $errors = validate_student_form();
            
        if ( empty($errors->errors) ) {

            $args = array(
                'first_name' => ucfirst(sanitize_student_form_text_field($_POST['first_name'])),
                'father_name' => ucfirst(sanitize_student_form_text_field($_POST['father_name'])),
                'last_name' => ucfirst(sanitize_student_form_text_field($_POST['last_name'])),
                'phone_number' => sanitize_student_form_text_field($_POST['phone_number']),
                'email' => sanitize_email($_POST['email']),
                'date_of_birth' => $_POST['date_of_birth'],
                );

            if ( ! isset( $_GET['id'] ) || $_GET['id'] == '' ) {
                $args['student_code'] = sanitize_student_form_text_field($_POST['student_code']);
                $args['student_password'] = wp_hash_password($_POST['student_password']);
                
                $default = array(
                    'student_code' => '',
                    'first_name' => '',
                    'father_name' => '',
                    'last_name' => '',
                    'phone_number' => '',
                    'email' => '',
                    'student_password' => '',
                    'date_of_birth' => '',
                );
                
                $record = wp_parse_args( $args, $default );
            }
                
            if ( isset($_GET['id'] ) && $_GET['id'] !== '' && $_POST['student_password'] !== '' ) $args['student_password'] = wp_hash_password($_POST['student_password']);
              
            isset( $_GET['id'] ) && $_GET['id'] !== '' ? $wpdb->update( $table_name, $args, array( 'id' => $_GET['id'] ) ) : $wpdb->insert( $table_name, $record );
                
        } else {
            return $errors;
        }

    } elseif ( isset( $_POST['student-delete-submit'] ) ) {

        wp_redirect( '?page=student_form&action=delete&id=' . $_GET['id'] );
  
    } elseif ( isset( $_POST['student-delete-confirm'] ) ) {

        global $wpdb;

        $table_name = $wpdb->prefix . 'students';
        $success = 'false';

        if ( $wpdb->delete( $table_name, array( 'id' => $_GET['id'] ) ) ) $success = 'true';

        wp_redirect( '?page=students_list&action=delete&success=' . $success );

    }
}    