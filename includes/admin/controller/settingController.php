<?php

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

final class WPUserSetting {

    function get_setting() {
        global $wpdb;
        $data = array();      
        
            $data['wp_user_disable_signup'] = get_option('wp_user_disable_signup');
            $data['wp_user_disable_admin_bar'] = get_option('wp_user_disable_admin_bar');
            $data['wp_user_tern_and_condition'] = (get_option('wp_user_tern_and_condition'));   
            $data['wp_user_show_term_data'] = get_option('wp_user_show_term_data');   
            
            //Appearance           
            $data['wp_user_appearance_skin'] = get_option('wp_user_appearance_skin');
            $data['wp_user_appearance_icon'] = get_option('wp_user_appearance_icon'); 
            $data['wp_user_appearance_custom_css'] = get_option('wp_user_appearance_custom_css');  
            $data['wp_user_language'] = get_option('wp_user_language');  
            
            //page setting
            $data['wp_user_page']['id'] = get_option('wp_user_page');   
            $data['wp_user_page']['permalink']=get_permalink( $data['wp_user_page']['id'] );
            $data['wp_user_page']['title']=get_the_title( $data['wp_user_page']['id'] );
            $data['wp_user_page_title']=$data['wp_user_page']['title'];
            $data['wp_user_member_page']['id'] = get_option('wp_user_member_page');   
            $data['wp_user_member_page']['permalink']=get_permalink( $data['wp_user_member_page']['id'] );
            $data['wp_user_member_page']['title']=get_the_title( $data['wp_user_member_page']['id'] );
            $data['wp_user_member_page_title']=$data['wp_user_member_page']['title'];
            
            //login limit
            $data['wp_user_login_limit_enable'] = get_option('wp_user_login_limit_enable');
            $data['wp_user_login_limit'] = get_option('wp_user_login_limit');
            $data['wp_user_login_limit_time'] = get_option('wp_user_login_limit_time');  
            $data['wp_user_login_limit_admin_notify'] = get_option('wp_user_login_limit_admin_notify');  
            
            
            //password security
            $data['wp_user_login_limit_password_enable'] = get_option('wp_user_login_limit_password_enable');
            $data['wp_user_login_limit_password'] = get_option('wp_user_login_limit_password');
            $data['wp_user_login_password_valid_message'] = get_option('wp_user_login_password_valid_message');

            
            //reCaptcha
            $data['wp_user_security_reCaptcha_enable'] = get_option('wp_user_security_reCaptcha_enable');
            $data['wp_user_security_reCaptcha_secretkey'] = get_option('wp_user_security_reCaptcha_secretkey');
            
            //Admin Notification
            $data['wp_user_email_name'] = get_option('wp_user_email_name');
            $data['wp_user_email_id'] = get_option('wp_user_email_id');  
            //admin
            $data['wp_user_email_admin_register_enable'] = get_option('wp_user_email_admin_register_enable');
            $data['wp_user_email_admin_register_subject'] = get_option('wp_user_email_admin_register_subject');
            $data['wp_user_email_admin_register_content'] = get_option('wp_user_email_admin_register_content');  
            //user
            $data['wp_user_email_user_register_enable'] = get_option('wp_user_email_user_register_enable');
            $data['wp_user_email_user_register_subject'] = get_option('wp_user_email_user_register_subject');
            $data['wp_user_email_user_register_content'] = get_option('wp_user_email_user_register_content');  
           //forgot password
            $data['wp_user_email_user_forgot_subject'] = get_option('wp_user_email_user_forgot_subject');
            $data['wp_user_email_user_forgot_content'] = get_option('wp_user_email_user_forgot_content');  

            $data['wp_user_truncate_login_entries'] = get_option('wp_user_truncate_login_entries');
            
        print_r(json_encode($data));
        die();
        //return json_encode($data);  
    }

    function get_login_log() {
        global $wpdb;
        $cu = wp_get_current_user();
        if ($cu->has_cap('manage_options')) {
        $data = array();      
        $q="SELECT u.id as user_id,l.user,u.user_email,l.status,l.message,l.user_agent,l.ip,l.created_date FROM {$wpdb->prefix}wpuser_login_log l LEFT JOIN {$wpdb->prefix}users u ON (l.user=u.user_login OR l.user=u.user_email ) ORDER BY l.created_date DESC";
         $data = $wpdb->get_results($q);
         print_r(json_encode($data));
     }
        die();
}

    function get_user_list() {
         global $wpdb;
         $data = array(); 
        $cu = wp_get_current_user();
        if ($cu->has_cap('manage_options')) {
              $number=(isset($_REQUEST['users_num']) && !empty($_REQUEST['users_num'])) ? $_REQUEST['users_num'] : '';
               $fields=(isset($_REQUEST['include_fields']) && !empty($_REQUEST['include_fields'])) ? explode(',',$_REQUEST['include_fields']) : array( 'ID','user_login','user_nicename','display_name','user_email','user_activation_key','user_registered');

            $args = array(                   
                        'role'         => '',
                        'role__in'     => array(),
                        'role__not_in' => array(),
                        'meta_key'     => '',
                        'meta_value'   => '',
                        'meta_compare' => '',
                        'meta_query'   => array(),
                        'date_query'   => array(),        
                        'include'      => array(),
                        'exclude'      => array(),
                        'offset'       => '',
                        'search'       => '',
                        'number'       => $number,
                        'count_total'  => false,
                        'fields'       => $fields
                     ); 
            $data = get_users($args);
             print_r(json_encode($data));
     }
        die();
    }

    function update_setting() {

        global $wpdb;
        $cu = wp_get_current_user();
        if ($cu->has_cap('manage_options')) {
            $haystack= array('wp_user_show_term_data','wp_user_email_admin_register_content','wp_user_email_user_register_content','wp_user_email_user_forgot_content');
            $data = json_decode(file_get_contents("php://input"), true);
            foreach ($data as $key => $item) {
                if(in_array($key, $haystack)){
                    update_option($key,($item));
                }else{
                update_option($key, sanitize_text_field($item));
                }
            }
        }
        echo "true";
        die();
        //return json_encode($data);  
    }
     function update_page_setting() {

        global $wpdb;
        $cu = wp_get_current_user();
        if ($cu->has_cap('manage_options')) {
            $data = json_decode(file_get_contents("php://input"), true);
            //post status and options
              $post = array(
                    'comment_status' => 'closed',
                    'ping_status' =>  'closed' ,
                    'post_author' => 1,
                    'post_date' => date('Y-m-d H:i:s'),
                    'post_name' => $data['wp_user_page_title'],
                    'post_status' => 'publish' ,
                    'post_title' => $data['wp_user_page_title'],
                    'post_content' => '[wp_user]',
                    'post_type' => 'page',
              );  
              //insert page and save the id
              $newvalue = wp_insert_post( $post, false );
              //save the id in the database
              update_option( 'wp_user_page', $newvalue );

               $post = array(
                'comment_status' => 'closed',
                'ping_status' => 'closed',
                'post_author' => 1,
                'post_date' => date('Y-m-d H:i:s'),
                'post_name' => $data['wp_user_member_page_title'],
                'post_status' => 'publish',
                'post_title' => $data['wp_user_member_page_title'],
                'post_content' => '[wp_user_member]',
                'post_type' => 'page',
            );
            //insert page and save the id
            $newvalue = wp_insert_post($post, false);
            //save the id in the database
            update_option('wp_user_member_page', $newvalue);
        }
        $data = array();  
        
            $data['wp_user_page']['id'] = get_option('wp_user_page');   
            $data['wp_user_page']['permalink']=get_permalink( $data['wp_user_page']['id'] );
            $data['wp_user_page']['title']=get_the_title( $data['wp_user_page']['id'] );            
            $data['wp_user_page_title']=$data['wp_user_page']['title'];
            $data['wp_user_member_page']['id'] = get_option('wp_user_member_page');   
            $data['wp_user_member_page']['permalink']=get_permalink( $data['wp_user_member_page']['id'] );
            $data['wp_user_member_page']['title']=get_the_title( $data['wp_user_member_page']['id'] );       
            $data['wp_user_member_page_title']=$data['wp_user_member_page']['title'];

        print_r(json_encode($data));
        die();
        //return json_encode($data);  
    }

}
