<?php
/**
 * Plugin Name: Uni Registration System
 * Plugin URI: https://github.com/SyntaxError843/Uni-reg-system-wp-plugin
 * Description: Simple plugin to manage students, courses and course registrations in a university.
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
require_once UNIREGSYS_DIR . 'includes/students/students-list.php';
require_once UNIREGSYS_DIR . 'includes/students/students-form.php';

require_once UNIREGSYS_DIR . 'includes/courses/courses-datatable.php';
require_once UNIREGSYS_DIR . 'includes/courses/courses-list.php';
require_once UNIREGSYS_DIR . 'includes/courses/courses-form.php';

require_once UNIREGSYS_DIR . 'includes/registrations/registrations-datatable.php';
require_once UNIREGSYS_DIR . 'includes/registrations/registrations-list.php';
require_once UNIREGSYS_DIR . 'includes/registrations/registrations-form.php';

require_once UNIREGSYS_DIR . 'includes/scripts.php';

// Create the data tables in the database on activate
register_activation_hook(__FILE__, 'uni_reg_system_create_db');
function uni_reg_system_create_db() {
    create_students_datatable();
    create_courses_datatable();
    create_registrations_datatable();
}

// drop the data tables from the database on uninstall
register_deactivation_hook(__FILE__, 'uni_reg_system_drop_db');
function uni_reg_system_drop_db() {
    drop_registrations_datatable();
    drop_students_datatable(); 
    drop_courses_datatable(); 
}

add_action( 'admin_menu', 'uni_reg_system_admin_main_menu' );
function uni_reg_system_admin_main_menu() {
    add_menu_page(
        __( 'Course Registrations' ),
        __( 'Course Registrations' ),
        'edit_posts',
        'registrations_list',
        'uni_reg_system_registrations_list_handler',
        'dashicons-welcome-learn-more',
        6
    );
    add_submenu_page(
        'registrations_list',
        __( 'Add Registration' ),
        __( 'Add Registration' ),
        'edit_posts',
        'registration_form',
        'uni_reg_system_registrations_form_handler'
    );
    add_submenu_page(
        'registrations_list',
        __( 'Students' ),
        __( 'Students' ),
        'edit_posts',
        'students_list',
        'uni_reg_system_students_list_handler'
    );
    add_submenu_page(
        'registrations_list',
        __( 'Add Student' ),
        __( 'Add Student' ),
        'edit_posts',
        'student_form',
        'uni_reg_system_student_form_handler'
    );
    add_submenu_page(
        'registrations_list',
        __( 'Courses' ),
        __( 'Courses' ),
        'edit_posts',
        'courses_list',
        'uni_reg_system_courses_list_handler'
    );
    add_submenu_page(
        'registrations_list',
        __( 'Add Course' ),
        __( 'Add Course' ),
        'edit_posts',
        'course_form',
        'uni_reg_system_course_form_handler'
    );
}

function uni_reg_system_registrations_list_handler() {
    registrations_list_html();
}

function uni_reg_system_registrations_form_handler() {
    registration_form_html();
}

function uni_reg_system_students_list_handler() {
    students_list_html();
}

function uni_reg_system_student_form_handler() {
    student_form_html();
}

function uni_reg_system_courses_list_handler() {
    courses_list_html();
}

function uni_reg_system_course_form_handler() {
    course_form_html();
}

