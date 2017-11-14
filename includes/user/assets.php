<?php 
    wp_enqueue_script( 'jquery' );              
    wp_enqueue_style('wpsp_bootstrap', WPUSER_PLUGIN_URL . 'assets/css/bootstrap.min.css');
    wp_enqueue_style('wpdbadminltecss', WPUSER_PLUGIN_URL . "assets/dist/css/AdminLTE.css");  

    wp_enqueue_script('wpdbbootstrap', WPUSER_PLUGIN_URL . "assets/bootstrap/js/bootstrap.min.js");
    wp_enqueue_script('wpdbbootstrap');

    wp_enqueue_script('wpdbbootstraprecaptcha',"https://www.google.com/recaptcha/api.js");
    wp_enqueue_script('wpdbbootstraprecaptcha');

     wp_deregister_style('wpce_bootstrap'); 

        $isUserLogged = (is_user_logged_in()) ? 1 : 0;
        $wp_user_appearance_skin = get_option('wp_user_appearance_skin') ? get_option('wp_user_appearance_skin') : 'default';