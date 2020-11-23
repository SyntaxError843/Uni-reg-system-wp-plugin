<?php

function registrations_list_html() {
    global $wpdb;

    $students_table = $wpdb->prefix . 'students';
    $courses_table = $wpdb->prefix . 'courses';
    $registrations_table = $wpdb->prefix . 'registrations';
    $sql = "
        SELECT student_id, student_code, first_name, father_name, last_name, GROUP_CONCAT(course_name) AS registered_courses, GROUP_CONCAT(course_code) AS registered_course_codes, GROUP_CONCAT($registrations_table.`id`) AS registration_ids
        FROM $registrations_table, $students_table, $courses_table
        WHERE $students_table.`id` = $registrations_table.`student_id`
        AND $courses_table.`id` = $registrations_table.`course_id`
        GROUP BY student_id;
    ";

    $results = $wpdb->get_results( $sql );

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
        <p>
            <?php

            if( $results ) {
                ?> 

                    <table class="widefat fixed" cellspacing="0">
                        <thead>
                            <tr>
                                <th id="cb" class="manage-column column-cb check-column" scope="col"></th>
                                <th id="full-name" class="manage-column column-full-name" scope="col">Student</th>
                                <th id="courses" class="manage-column column-courses" scope="col">Registered Courses</th>
                            </tr>   
                        </thead>

                        <tbody>
                            <?php
                                foreach( $results as $result ) {
                                    $registration_ids = explode( ',', $result->registration_ids );
                                    $registered_course_codes = explode( ',', $result->registered_course_codes );
                                    ?>

                                    <tr class="alternate" valign="top">
                                        <th class="check-column" scope="row"></th>
                                        <td class="column-full-name">
                                            <strong><?php echo $result->first_name . ' ' . $result->father_name[0] . '. ' . $result->last_name . ' (' . $result->student_code . ')';?></strong>
                                            <div class="row-actions">
                                                <span class="edit"><a href="<?php echo '?page=registration_form&student_id=' . $result->student_id ?>">Add Courses To This Student</a></span>
                                            </div>
                                        </td>
                                        <td class="column-courses">
                                            <ul style="list-style-type: disc; margin-left: 25px">
                                                <?php
                                                    foreach( explode( ",", $result->registered_courses ) as $key => $course ) {
                                                        ?>
                                                            <li>
                                                                <?php echo $course . ' (' . $registered_course_codes[$key] . ')' ?>
                                                                <div class="row-actions">
                                                                    <span class="" style="margin-left: 5px;"> - </span>
                                                                    <span class="edit" style="margin-left: 5px;"><a href="<?php echo '?page=registration_form&id=' . $registration_ids[$key] ?>">Edit</a></span>
                                                                    <span class="" style="margin-left: 5px;"> | </span>
                                                                    <span class="delete" style="margin-left: 5px;"><a class="submitdelete" href="<?php echo '?page=registration_form&action=delete&id=' . $registration_ids[$key] ?>">Delete</a></span>
                                                                </div>
                                                            </li>
                                                        <?php
                                                    }
                                                    ?>
                                            </ul>
                                        </td>
                                    </tr>

                                    <?php 
                                }
                            ?>
                        </tbody>    
                    </table>

                <?php
            } else {
                echo 'No registrations found.';
            }

            ?>
        </p>
    </div>
<?php
}