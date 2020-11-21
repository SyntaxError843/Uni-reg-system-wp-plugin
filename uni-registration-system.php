<?php
/**
 * Plugin Name: Uni Registration System
 * Description: simple plugin to manage students, courses and course registrations in a university.
 * Version: 1.0
 * Author: Salah Ghomrawi
 * Author URI: https://github.com/SyntaxError843 
 * License: GPLv2
 * WordPress Available:  yes
 * Requires License:    no
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 */

// Exit if accessed directly
if ( ! defined('ABSPATH') ) {
     exit;
}

define( 'UNIREGSYS_DIR', trailingslashit( plugin_dir_path( __FILE__ ) ) );

require_once UNIREGSYS_DIR . 'includes/students/students-datatable.php';
require_once UNIREGSYS_DIR . 'includes/students/students-form.php';
require_once UNIREGSYS_DIR . 'includes/students/students-list.php';

require_once UNIREGSYS_DIR . 'includes/scripts.php';

// Create the data tables in the database on activate
register_activation_hook(__FILE__, 'uni_reg_system_create_db');
function uni_reg_system_create_db() {
    create_students_datatable();
}

// drop the data tables from the database on uninstall
register_uninstall_hook(__FILE__, 'uni_reg_system_drop_db');
function uni_reg_system_drop_db() {
    drop_students_datatable(); 
}

add_action( 'admin_menu', 'uni_reg_system_admin_main_menu' );
function uni_reg_system_admin_main_menu() {
    add_menu_page(
        __( 'Uni Registrations' ),
        __( 'Uni Registrations' ),
        'edit_posts',
        'uni_reg_system',
        'uni_reg_system_registrations_list_handler',
        'dashicons-welcome-learn-more',
        6
    );
    add_submenu_page(
        'uni_reg_system',
        __( 'Students' ),
        __( 'Students' ),
        'edit_posts',
        'students_list',
        'uni_reg_system_students_list_handler'
    );
    add_submenu_page(
        'uni_reg_system',
        __( 'Add Student' ),
        __( 'Add Student' ),
        'edit_posts',
        'student_form',
        'uni_reg_system_student_form_handler'
    );
}

function uni_reg_system_registrations_list_handler() {
    echo 'ass';
}

function uni_reg_system_students_list_handler() {
    students_list_html();
}

function uni_reg_system_student_form_handler() {
    student_form_html();
}

