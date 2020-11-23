<?php

require_once ABSPATH . 'wp-admin/includes/upgrade.php';

function create_registrations_datatable() {
    global $wpdb;
    $students_table_name = $wpdb->prefix . 'students';
    $courses_table_name = $wpdb->prefix . 'courses';
    $table_name = $wpdb->prefix . 'registrations';

    $sql = "CREATE TABLE $table_name (
            id mediumint(9) NOT NULL AUTO_INCREMENT,
            course_id mediumint(9) NOT NULL,
            student_id mediumint(9) NOT NULL,
            date_created datetime DEFAULT NOW() NOT NULL,
            PRIMARY KEY (id),

            FOREIGN KEY (course_id) REFERENCES $courses_table_name (id)
                ON DELETE CASCADE
                ON UPDATE CASCADE,

            FOREIGN KEY (student_id) REFERENCES $students_table_name (id)
                ON DELETE CASCADE
                ON UPDATE CASCADE,

            CONSTRAINT registrations_unique UNIQUE (course_id, student_id)
        )";

    maybe_create_table( $table_name, $sql );
}

function drop_registrations_datatable() {
    global $wpdb;
    $table_name = $wpdb->prefix . 'registrations';
    $sql = "DROP TABLE $table_name CASCADE";
    $query = $wpdb->prepare( 'SHOW TABLES LIKE %s', $wpdb->esc_like( $table_name ) );
 
    if ( $wpdb->get_var( $query ) === $table_name ) {
        $wpdb->query( $sql );
    }
}