<?php

require_once ABSPATH . 'wp-admin/includes/upgrade.php';

function create_students_datatable() {
    global $wpdb;
    $table_name = $wpdb->prefix . 'students';

    $sql = "CREATE TABLE $table_name (
            id mediumint(9) NOT NULL AUTO_INCREMENT,
            student_code varchar(255) NOT NULL,
            first_name varchar(255) NOT NULL,
            father_name varchar(255) NOT NULL,
            last_name varchar(255) NOT NULL,
            phone_number varchar(10),
            email varchar(255),
            student_password varchar(255) NOT NULL,
            date_of_birth datetime NOT NULL,
            date_created datetime DEFAULT NOW() NOT NULL,
            PRIMARY KEY (id),
            CONSTRAINT students_unique UNIQUE (student_code)
        )";

    maybe_create_table( $table_name, $sql );
}

function drop_students_datatable() {
    global $wpdb;
    $table_name = $wpdb->prefix . 'students';
    $sql = "DROP TABLE $table_name CASCADE";
    $query = $wpdb->prepare( 'SHOW TABLES LIKE %s', $wpdb->esc_like( $table_name ) );
 
    if ( $wpdb->get_var( $query ) === $table_name ) {
        $wpdb->query( $sql );
    }
}