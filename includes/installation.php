<?php

if (!defined('ABSPATH'))
    exit; // Exit if accessed directly
global $wpdb;
add_option('wp_user_disable_signup', 0);
add_option('wp_user_disable_admin_bar', 1); // Disable WordPress Admin Bar for All Users Except Admin
//Appearance 
add_option('wp_user_appearance_skin', 'horizontal');
add_option('wp_user_language', 'English');
add_option('wp_user_appearance_icon', 0);
add_option('wp_user_appearance_custom_css', '');

//login limit
add_option('wp_user_login_limit_enable', 1);
add_option('wp_user_login_limit', 5);
add_option('wp_user_login_limit_time', 10);
add_option('wp_user_login_limit_admin_notify', 1);

//password security
add_option('wp_user_login_limit_password_enable', 1);
add_option('wp_user_login_limit_password', '$\S*(?=\S{8,})(?=\S*[a-z])(?=\S*[A-Z])(?=\S*[\d])(?=\S*[\W])\S*$');
add_option('wp_user_login_password_valid_message', 'Password containing at least one lowercase letter,uppercase letter,special character (non-word characters),one number and at least length 8');
/*
  Explaining $\S*(?=\S{8,})(?=\S*[a-z])(?=\S*[A-Z])(?=\S*[\d])(?=\S*[\W])\S*$
  $ = beginning of string
  \S* = any set of characters
  (?=\S{8,}) = of at least length 8
  (?=\S*[a-z]) = containing at least one lowercase letter
  (?=\S*[A-Z]) = and at least one uppercase letter
  (?=\S*[\d]) = and at least one number
  (?=\S*[\W]) = and at least a special character (non-word characters)
  $ = end of the string

 */

//reCaptcha
add_option('wp_user_security_reCaptcha_enable', 0);
add_option('wp_user_security_reCaptcha_secretkey', '');

//Email 
add_option('wp_user_email_name', get_option('blogname'));
add_option('wp_user_email_id', get_option('admin_email'));

//Admin Email Notification
add_option('wp_user_email_admin_register_enable', 1);
add_option('wp_user_email_admin_register_subject', 'New User Registration');
add_option('wp_user_email_admin_register_content', 'Hi there,
{WPUSER_USERNAME} has just created a new account at {WPUSER_BLOGNAME}.');

//User Email Notification
add_option('wp_user_email_user_register_enable', 1);
add_option('wp_user_email_user_register_subject', 'Welcome');
add_option('wp_user_email_user_register_content', 'To login please visit the following URL:
{WPUSER_LOGIN_URL}
Your account e-mail: {WPUSER_EMAIL}
Your account username: {WPUSER_USERNAME}
If you have any problems, please contact us at {WPUSER_ADMIN_EMAIL}.
Best Regards!');

add_option('wp_user_email_user_forgot_subject', 'Your new password');
add_option('wp_user_email_user_forgot_content', 'Dear <strong style="font-family:Arial;margin:0px;padding:0px"> {WPUSER_USERNAME}</strong>, <br><br>
                <h3>Your password changed successfully. Details as follow</h3>
                <br><br>
                Login url :{WPUSER_LOGIN_URL}
                <br> <br>
                User Name : {WPUSER_USERNAME}
                <br>
                Your new password is: {WPUSER_NEW_PASSWORD}');


//Login Attempts
if ($wpdb->get_var("SHOW TABLES LIKE '{$wpdb->prefix}WPUser_LoginAttempts'") != $wpdb->prefix.'WPUser_LoginAttempts') {
    $wpdb->query("CREATE TABLE IF NOT EXISTS {$wpdb->prefix}WPUser_LoginAttempts (
	    id int(11) NOT NULL AUTO_INCREMENT,
            IP varchar(20) NOT NULL,
            Attempts int(11) NOT NULL,
            LastLogin datetime NOT NULL,
             PRIMARY KEY (id)
          )");
}

if ($wpdb->get_var("SHOW TABLES LIKE '{$wpdb->prefix}wpuser_login_log'") != $wpdb->prefix.'wpuser_login_log') {
    $wpdb->query("CREATE TABLE IF NOT EXISTS {$wpdb->prefix}wpuser_login_log (
      id int(11) NOT NULL AUTO_INCREMENT,
      user varchar(100) DEFAULT NULL,
      ip varchar(25) DEFAULT NULL,
      status varchar(15) DEFAULT NULL,
      message varchar(150) DEFAULT NULL,
      user_agent varchar(200) DEFAULT NULL,
      created_date timestamp NULL DEFAULT CURRENT_TIMESTAMP,
      PRIMARY KEY (id)
          )");
}
add_option('wp_user_truncate_login_entries', 0);