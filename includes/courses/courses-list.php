<?php

function courses_list_html() {
    global $wpdb;
    $table_name = $wpdb->prefix . 'courses';
    $sql = "SELECT * FROM $table_name";

    $results = $wpdb->get_results( $sql );

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
        <p>
            <?php

            if( $results ) {
                ?> 

                    <table class="widefat fixed" cellspacing="0">
                        <thead>
                            <tr>
                                <th id="cb" class="manage-column column-cb check-column" scope="col"></th>
                                <th id="course-name" class="manage-column column-course-name" scope="col">Course Name</th>
                                <th id="course-code" class="manage-column column-course-code" scope="col">Course Code</th>
                            </tr>   
                        </thead>

                        <tbody>
                            <?php
                                foreach( $results as $result ) {
                                    ?>

                                    <tr class="alternate" valign="top">
                                        <th class="check-column" scope="row"></th>
                                        <td class="column-course-name">
                                            <strong><?php echo $result->course_name?></strong>
                                            <div class="row-actions">
                                                <span class="edit"><a href="<?php echo '?page=course_form&course_id=' . $result->id ?>">Edit</a> |</span>
                                                <span class="delete"><a class="submitdelete" href="<?php echo '?page=course_form&action=delete&course_id=' . $result->id ?>">Delete</a></span>
                                            </div>
                                        </td>
                                        <td class="column-course-code"><?php echo $result->course_code; ?></td>
                                    </tr>

                                    <?php 
                                }
                            ?>
                        </tbody>    
                    </table>

                <?php
            } else {
                echo 'No courses found.';
            }

            ?>
        </p>
    </div>
<?php
}