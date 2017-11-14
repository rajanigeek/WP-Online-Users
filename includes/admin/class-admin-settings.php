<?php

if (!defined('ABSPATH'))
    exit; // Exit if accessed directly

class WPUserMenu {

    public function __construct() {
        add_action('init', array('WPUserMenu', 'init'));
        add_action('admin_init', array($this, 'wp_user_admin_init'));
        add_action('after_setup_theme', array($this, 'remove_admin_bar'));
    }

    static function remove_admin_bar() {
        $wp_user_disable_admin_bar = get_option('wp_user_disable_admin_bar');
        if (!empty($wp_user_disable_admin_bar) && $wp_user_disable_admin_bar == 1) {
            if (!current_user_can('administrator') && !is_admin()) {
                show_admin_bar(false);
            }
        }
    }

    static function version() {
        return VERSION;
    }

    static function init() {
        add_action('admin_menu', array('WPUserMenu', 'adminPage'));
    }

    static function adminPage() {
        add_menu_page('WP Users', 'WP Users', 'manage_options', 'wp-user-setting', array('WPUserMenu', 'renderAdminPage'), 'dashicons-admin-users');
    }

    static function renderAdminPage() {
        wp_enqueue_script('wpusersettingcontroller', WPUSER_PLUGIN_URL . "assets/js/angular/settingController.js");

        include('views/setting.php');
    }

    function wp_user_admin_init() {
        if (is_admin()) {
            
        }
    }

}

$WPUserMenu = new WPUserMenu();

