<?php
// Add Scripts
function uni_reg_system_scripts() {
    // Add Main CSS
    wp_enqueue_style('uni-reg-system-style', plugins_url() . '/uni-registration-system/css/bootstrap.min.css');
}

add_action('wp_enqueue_scripts', 'uni_reg_system_scripts');