<?php

function students_list_html() {
    global $wpdb;
    $table_name = $wpdb->prefix . 'students';
    $sql = "SELECT * FROM $table_name";

    $results = $wpdb->get_results( $sql );

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
        <p>
            <?php

            if( count($results) > 0 ) {
                ?> 

                    <table class="widefat fixed" cellspacing="0">
                        <thead>
                            <tr>
                                <th id="cb" class="manage-column column-cb check-column" scope="col"></th>
                                <th id="full-name" class="manage-column column-full-name" scope="col">Student Name</th>
                                <th id="student-code" class="manage-column column-student-code" scope="col">Student Code</th>
                                <th id="phone-number" class="manage-column column-phone-number" scope="col">Phone Number</th>
                                <th id="email" class="manage-column column-email" scope="col">Email</th>
                                <th id="student-password" class="manage-column column-student-password" scope="col">Password Hash</th>
                                <th id="date-of-birth" class="manage-column column-date-of-birth" scope="col">Date of Birth</th>
                                <th id="date-of-birth" class="manage-column column-date-created" scope="col">Date Created</th>
                            </tr>   
                        </thead>

                        <tbody>
                            <?php
                                foreach( $results as $result ) {
                                    ?>

                                    <tr class="alternate" valign="top">
                                        <th class="check-column" scope="row"></th>
                                        <td class="column-full-name">
                                            <strong><?php echo $result->first_name . ' ' . $result->father_name[0] . '. ' . $result->last_name;?></strong>
                                            <div class="row-actions">
                                                <span class="edit"><a href="<?php echo '?page=student_form&student_id=' . $result->id ?>">Edit</a> |</span>
                                                <span class="delete"><a class="submitdelete" href="<?php echo '?page=student_form&action=delete&student_id=' . $result->id ?>">Delete</a></span>
                                            </div>
                                        </td>
                                        <td class="column-student-code"><?php echo $result->student_code; ?></td>
                                        <td class="column-phone-number"><?php echo $result->phone_number; ?></td>
                                        <td class="column-email"><?php echo $result->email; ?></td>
                                        <td class="column-student-password"><?php echo $result->student_password; ?></td>
                                        <td class="column-date-of-birth"><?php echo $result->date_of_birth; ?></td>
                                        <td class="column-date-created"><?php echo $result->date_created; ?></td>
                                    </tr>

                                    <?php 
                                }
                            ?>
                        </tbody>    
                    </table>

                <?php
            } else {
                echo 'No students found.';
            }

            ?>
        </p>
    </div>
<?php
}