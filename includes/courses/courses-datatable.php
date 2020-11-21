<?php

require_once ABSPATH . 'wp-admin/includes/upgrade.php';

function create_courses_datatable() {
    global $wpdb;
    $table_name = $wpdb->prefix . 'courses';

    $sql = "CREATE TABLE $table_name (
            id mediumint(9) NOT NULL AUTO_INCREMENT,
            course_code varchar(255) NOT NULL,
            course_name varchar(255) NOT NULL,
            PRIMARY KEY (id)
        )";

    maybe_create_table( $table_name, $sql );
}

function drop_courses_datatable() {
    global $wpdb;
    $table_name = $wpdb->prefix . 'courses';
    $sql = "DROP TABLE $table_name CASCADE";
    $query = $wpdb->prepare( 'SHOW TABLES LIKE %s', $wpdb->esc_like( $table_name ) );
 
    if ( $wpdb->get_var( $query ) === $table_name ) {
        $wpdb->query( $sql );
    }
}