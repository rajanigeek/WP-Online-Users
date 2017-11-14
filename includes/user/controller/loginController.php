<?php
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

final class WPUserFrontEnd {

    var $time;                //Time user was last active (page loaded)   
    var $ip;                  //Remote IP address  

    function WPUserFrontEnd() {
        $this->ip = $_SERVER["REMOTE_ADDR"];
        $this->time = time();
        add_action('wp_user_action_register', array($this, 'wp_user_action_register_function'));
        add_action('wp_user_action_login_attempts_admin_notify', array($this, 'wp_user_action_login_attempts_admin_notify_function'));
        add_filter('wp_user_filter_email', array($this, 'wp_user_filter_email_function'), 10, 6);
    }

    function get_setting_login() {
        $data['wp_user_disable_signup'] = get_option('wp_user_disable_signup');
        $data['wp_user_appearance_icon'] = get_option('wp_user_appearance_icon');
        print_r(json_encode($data));
        die();
    }

    function get_setting_register() {
        $data['wp_user_tern_and_condition'] = get_option('wp_user_tern_and_condition');
        $data['wp_user_show_term_data'] = get_option('wp_user_show_term_data');
        $data['wp_user_appearance_icon'] = get_option('wp_user_appearance_icon');
        $data['wp_user_security_reCaptcha_enable'] = get_option('wp_user_security_reCaptcha_enable');        
        print_r(json_encode($data));
        die();
    }

    function get_setting_forgot() {
        $data['wp_user_appearance_icon'] = get_option('wp_user_appearance_icon');
        print_r(json_encode($data));
        die();
    }

    function wpuser_login() {
        global $wpdb;
        $data = array();
        $data = json_decode(file_get_contents("php://input"), true);
        $creds = array();
        $loginLog=array();
        $str = file_get_contents(WPUSER_USER_DIR_i18n . '/' . get_option('wp_user_language') . '.json');
        $json = json_decode($str, true);

        $wp_user_email_name=(isset($data['wp_user_email_name'])) ? $data['wp_user_email_name'] : ((isset($_POST['wp_user_email_name'])) ? $_POST['wp_user_email_name'] : '') ;

         $wp_user_password=(isset($data['wp_user_password'])) ? $data['wp_user_password'] : ((isset($_POST['wp_user_password'])) ? $_POST['wp_user_password'] : '') ;
            $loginLog['ip']=$this->ip;
            $loginLog['user']=sanitize_text_field($wp_user_email_name);
        if (isset($wp_user_email_name)) {
            if (filter_var($wp_user_email_name, FILTER_VALIDATE_EMAIL)) {
                $userInfo = get_user_by_email(sanitize_text_field($wp_user_email_name));
                if (!empty($userInfo->user_login))
                    $creds['user_login'] = $userInfo->user_login;
            }else {
                $creds['user_login'] = sanitize_text_field($wp_user_email_name);
            }
        } else {
            $creds['user_login'] = '';
        }
        if (isset($wp_user_password)) {
            $creds['user_password'] = sanitize_text_field($wp_user_password);
        } else {
            $creds['user_password'] = '';
        }
        if (isset($data['wp_user_remember'])) {
            //$creds['remember'] = sanitize_text_field($data['wp_user_remember']);
        }

        /* Checks if this IP address is currently blocked */
        $wp_user_login_limit_enable = get_option('wp_user_login_limit_enable');
        if (isset($wp_user_login_limit_enable) && !empty($wp_user_login_limit_enable)) {
            $confirmResponse = $this->confirmIPAddress($this->ip, $creds['user_login']);
            if ($confirmResponse == 1) {
                $wp_user_login_limit_time = get_option('wp_user_login_limit_time');
                if (empty($wp_user_login_limit_time)) {
                    $wp_user_login_limit_time = 30;
                }
                $wp_user_disable_signup = get_option('wp_user_disable_signup');
                if (empty($wp_user_disable_signup)) {
                    $wp_user_disable_signup = 0;
                }
                $loginLog['message']=$result['message'] = $json['ACCESS_DENIED_FOR'] . " ". $wp_user_login_limit_time . " ". $json['MINUTS'];
                $loginLog['status']="Failed";                
                $result['status'] = 'warning';
                $result['wp_user_disable_signup'] = $wp_user_disable_signup;
                print_r(json_encode($result));
                $this->loginLog($loginLog);
                exit;
            }
        }

        $login_user = @wp_signon($creds, false);
        if (!is_wp_error($login_user)) {
            $args = (isset($data)) ? $data : (isset($_POST) ? $_POST : '');
            do_action_ref_array('wp_user_action_login', array(&$args));
            /* Null login attempts */
            if (isset($wp_user_login_limit_enable) && !empty($wp_user_login_limit_enable)) {
                $this->clearLoginAttempts($this->ip);
            }
            $result['message'] = $json['SUCCESSFULLY_LOGIN_REFRESH_PAGE'];
            $loginLog['message']="Successfull login";
            $loginLog['status']="Successfull";
            $result['status'] = 'success';
            $result['location'] = get_permalink(get_option('wp_user_page'));
            $result['wp_user_disable_signup'] = get_option('wp_user_disable_signup');
            print_r(json_encode($result));
            $this->loginLog($loginLog);
            exit;
        } elseif (is_wp_error($login_user)) {
            if (isset($wp_user_login_limit_enable) && !empty($wp_user_login_limit_enable)) {
                $this->addLoginAttempt($this->ip);
            }
            $args = array($creds['user_login'], $creds['user_password']);
            do_action_ref_array('wp_user_action_login_invalid', array(&$args));
            $loginLog['message']=$result['message'] = $json['INVALID_USER_NAME_OR_PASSWORD'];
            $result['status'] = 'warning';
            $loginLog['status']="Failed";
            $result['wp_user_disable_signup'] = get_option('wp_user_disable_signup') ? 1 : 0;
            print_r(json_encode($result));
            $this->loginLog($loginLog);
            //error_log( $login_user->get_error_message());
            exit;
        }        
        die;
        //   print_r(json_encode($data['data']));
        //return json_encode($data);  
    }

    function wpuser_forgot() {
        global $wpdb;
        $data = array();
        $data = json_decode(file_get_contents("php://input"), true);

        $email=sanitize_text_field((isset($data['wp_user_email'])) ? $data['wp_user_email'] : ((isset($_POST['wp_user_email_name'])) ? $_POST['wp_user_email'] : ''));

        $str = file_get_contents(WPUSER_USER_DIR_i18n . '/' . get_option('wp_user_language') . '.json');
        $json = json_decode($str, true);


        if (empty($email)) {
            $error = $json['ENTER_A_USERNAME_OR_EMAIL_ADDRESS'];
        } else if (!is_email($email)) {
            $error = $json['INVALID_USERNAME_OR_EMAIL_ADDRESS'];
        } else if (!email_exists($email)) {
            $error = $json['THERE_IS_NO_USER_REGISTERD_WITH_THAT_EMAIL_ADDRESS'];
        } else {

            // lets generate our new password
            $random_password = wp_generate_password(12, false);

            // Get user data by field and data, other field are ID, slug, slug and login
            $user = get_user_by('email', $email);

            $update_user = wp_update_user(array(
                'ID' => $user->ID,
                'user_pass' => $random_password
                    )
            );
            $args = array($email, $user->ID, $random_password);
            do_action_ref_array('wp_user_action_forgot_password', array(&$args));

            // if  update user return true then lets send user an email containing the new password
            if ($update_user) {
                $to = $email;
                $subject = get_option('wp_user_email_user_forgot_subject');
                $sender = get_option('name');
                $site_url = site_url();
                $user_login = $user->user_login;
                $email_header_text = get_option('wp_user_email_user_forgot_subject');
                $email_body_text = apply_filters('wp_user_filter_email', get_option('wp_user_email_user_forgot_content'), $to, $user_login, null, null, $random_password);
                $email_footer_text = 'You\'re receiving this email because you have register on ' . $site_url;
                include(WPUSER_PLUGIN_DIR . 'includes/user/module/template_email_defualt.php');
                $headers[] = 'MIME-Version: 1.0' . "\r\n";
                $headers[] = 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
                $headers[] = "X-Mailer: PHP \r\n";
                $headers[] = 'From: ' . $sender . ' < ' . $email . '>' . "\r\n";

                $mail = wp_mail($to, $subject, $message, $headers);
                if ($mail)
                    $success = $json['CHECK_YOUR_EMAIL_ADDRESS_FOR_NEW_PASSWORD'];
            } else {
                $error = $json['OOPS_SOMETHING_WENT_WRONG_UPDATING_YOUR_ACCOUNT'];
            }
        }

        if (!empty($error)) {
            $result['message'] = $error;
            $result['status'] = 'warning';
            print_r(json_encode($result));
            exit;
        }
        if (!empty($success)) {
            $result['message'] = $success;
            $result['status'] = 'success';
            print_r(json_encode($result));
            exit;
        }

        echo "false";
        die();
    }

    function wpuser_register() {
        global $wpdb;
        $data = array();
        $result = array();
        $data = json_decode(file_get_contents("php://input"), true);
        $str = file_get_contents(WPUSER_USER_DIR_i18n . '/' . get_option('wp_user_language') . '.json');
        $json = json_decode($str, true);
      //  var_dump($data);exit;
        //reCaptcha
        $wp_user_security_reCaptcha_enable = get_option('wp_user_security_reCaptcha_enable');
        $wp_user_security_reCaptcha_secretkey = get_option('wp_user_security_reCaptcha_secretkey');

        /*
         if ($wp_user_security_reCaptcha_enable == 1 && !empty($wp_user_security_reCaptcha_secretkey)) {
         

            //Should be some validations before you proceed    

            $captcha = $data['wp_user_Recaptcha']; //Captcha response send by client
            //Build post data to make request with fetch_file_contents
            $postdata = http_build_query(
                    array(
                        'secret' => $wp_user_security_reCaptcha_secretkey, //secret KEy provided by google
                        'response' => $captcha, // wp_user_Recaptcha string sent from client
                        'remoteip' => $_SERVER['REMOTE_ADDR']
                    )
            );

            //Build options for the post request
            $opts = array('http' =>
                array(
                    'method' => 'POST',
                    'header' => 'Content-type: application/x-www-form-urlencoded',
                    'content' => $postdata
                )
            );
            //Create a stream this is required to make post request with fetch_file_contents
            $context = stream_context_create($opts);

            // Send request to Googles siteVerify API 
            $response = file_get_contents("https://www.google.com/recaptcha/api/siteverify", false, $context);
            $response = json_decode($response, true);

            //var_dump($response);
            if ($response["success"] === false) {//if user verification failed
                $result['message'] = 'Robots Not allowed (Captcha verification failed)';
                $result['status'] = 'warning'; //error codes sent buy google's siteVerify API
                print_r(json_encode($result));
                exit;
            }
        }
        */
        //end reCaptcha

        $wp_user_email_name=(isset($data['wp_user_email_name'])) ? $data['wp_user_email_name'] : ((isset($_POST['wp_user_email_name_register'])) ? $_POST['wp_user_email_name_register'] : '') ;

        $wp_user_email=(isset($data['wp_user_email'])) ? $data['wp_user_email'] : ((isset($_POST['wp_user_email'])) ? $_POST['wp_user_email'] : '') ;

        $wp_user_password=(isset($data['wp_user_password'])) ? $data['wp_user_password'] : ((isset($_POST['wp_user_password'])) ? $_POST['wp_user_password'] : '') ;

         $wp_user_re_password=(isset($data['wp_user_re_password'])) ? $data['wp_user_re_password'] : ((isset($_POST['wp_user_re_password'])) ? $_POST['wp_user_re_password'] : '') ;


        if (isset($wp_user_email_name) && !empty($wp_user_email_name)) {
            $username = sanitize_text_field($wp_user_email_name);
        } else {
            $result['message'] = $json['USERNAME'] . ' ' . $json['FIELD_IS_REQUIRED'];
            $result['status'] = 'warning';
            print_r(json_encode($result));
            $username = "";
            exit;
        }


        if (isset($wp_user_email) && !empty($wp_user_email)) {
            $email = sanitize_text_field($wp_user_email);
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $result['message'] = $json['INVALID_EMAIL_FORMAT'];
                $result['status'] = 'warning';
                print_r(json_encode($result));
                exit;
            }
        } else {
            $result['message'] = $json['EMAIL'] . ' ' . $json['FIELD_IS_REQUIRED'];
            $result['status'] = 'warning';
            print_r(json_encode($result));
            exit;
        }

        if (isset($wp_user_password) && !empty($wp_user_password)) {
            $password = sanitize_text_field($wp_user_password);
        } else {
            $result['message'] = $json['PASSWORD'] . ' ' . $json['FIELD_IS_REQUIRED'];
            $result['status'] = 'warning';
            print_r(json_encode($result));
            $password = "";
            exit;
        }

        if (isset($wp_user_re_password) && !empty($wp_user_re_password)) {
            $wp_user_login_limit_password = get_option('wp_user_login_limit_password');
            $wp_user_login_limit_password_enable = get_option('wp_user_login_limit_password_enable');
            $re_password = sanitize_text_field($wp_user_re_password);
            if (!($password == $re_password)) {
                $result['message'] = $json['PASSWORD_IS_NOT_MATCH'];
                $result['status'] = 'warning';
                print_r(json_encode($result));

                exit;
            } else if (isset($wp_user_login_limit_password_enable) && isset($wp_user_login_limit_password) && !empty($wp_user_login_limit_password) && !(preg_match($wp_user_login_limit_password, $password))) {
                $wp_user_login_password_valid_message = get_option('wp_user_login_password_valid_message');
                $result['message'] = !empty($wp_user_login_password_valid_message) ? $wp_user_login_password_valid_message : $json['INVALID'] . ' ' . $json['PASSWORD'];
                $result['status'] = 'warning';
                print_r(json_encode($result));
                exit;
            }
        } else {
            $result['message'] = $json['RETYPE_PASSWORD'] . ' ' . $json['FIELD_IS_REQUIRED'];
            $result['status'] = 'warning';
            print_r(json_encode($result));
            $re_password = "";
            exit;
        }
        $wp_user_tern_and_condition = get_option('wp_user_tern_and_condition');
        if (isset($wp_user_tern_and_condition) && $wp_user_tern_and_condition == 1) {
            $wp_user_term_condition= (isset($data['wp_user_term_condition'])) ? $data['wp_user_term_condition'] : ((isset($_POST['wp_user_term_condition'])) ? $_POST['wp_user_term_condition'] : '') ;
            if (!(isset($wp_user_term_condition) && !empty($wp_user_term_condition))) {
                $result['message'] = $json['PLEASE_ACCEPT_THE_TERMS'];
                $result['status'] = 'warning';
                print_r(json_encode($result));
                exit;
            }
        }





        //print_r($data);exit;
        $register_user = wp_create_user($username, $password, $email);
        //wpuser_auto_login($username,true);
        //wp_redirect(get_permalink()); exit; 
        if ($register_user && !is_wp_error($register_user)) {
            $result['message'] = $json['REGISTRATION_COMPLETED'];
            $result['status'] = 'success';
            $args = (isset($data)) ? $data : (isset($_POST) ? $_POST : '');
            do_action_ref_array('wp_user_action_register', array(&$args));
        } elseif (is_wp_error($register_user)) {
            $result['message'] = $register_user->get_error_message();
            $result['status'] = 'warning';
        }


        print_r(json_encode($result));
        exit;
    }

    function confirmIPAddress($value, $user_login = null) {
        global $wpdb;
        $wp_user_login_limit_time = get_option('wp_user_login_limit_time');
        if (empty($wp_user_login_limit_time)) {
            $wp_user_login_limit_time = 30;
        }

        $wwp_user_login_limit = get_option('wp_user_login_limit');
        if (empty($wwp_user_login_limit)) {
            $wwp_user_login_limit = 5;
        }
        $accessTime = date('Y-m-d h:i:m');

        $q = "SELECT Attempts, (CASE when lastlogin is not NULL and DATE_ADD(LastLogin, INTERVAL " . $wp_user_login_limit_time .
                " MINUTE)>'" . $accessTime . "' then 1 else 0 end) as Denied FROM {$wpdb->prefix}WPUser_LoginAttempts WHERE ip = '$value'";
        $data = $wpdb->get_results($q);

        //Verify that at least one login attempt is in database 
        if (!$data) {
            return 0;
        }
        if ($data[0]->Attempts >= $wwp_user_login_limit) {
            $args = array($value, $accessTime, $user_login);
            do_action_ref_array('wp_user_action_login_attempts_admin_notify', array(&$args));
            if ($data[0]->Denied == 1) {
                return 1;
            } else {
                $this->clearLoginAttempts($value);
                return 0;
            }
        }
        return 0;
    }

    function addLoginAttempt($value) {
        //Increase number of Attempts. Set last login attempt if required.
        global $wpdb;
        $q = "SELECT * FROM {$wpdb->prefix}WPUser_LoginAttempts WHERE ip = '$value'";
        $data = $wpdb->get_results($q);

        if ($data) {
            $Attempts = $data[0]->Attempts + 1;

            if ($Attempts == 3) {
                $values['Attempts'] = $Attempts;
                $values['lastlogin'] = date('Y-m-d h:i:m');
                $wpdb->update($wpdb->prefix . 'WPUser_LoginAttempts', $values, array('IP' => $value));
            } else {
                $values['Attempts'] = $Attempts;

                $wpdb->update($wpdb->prefix . 'WPUser_LoginAttempts', $values, array('IP' => $value));
            }
        } else {
            $values['Attempts'] = 1;
            $values['IP'] = $value;
            $values['lastlogin'] = date('Y-m-d h:i:m');
            $result = $wpdb->insert($wpdb->prefix . 'WPUser_LoginAttempts', $values);
        }
    }

    function clearLoginAttempts($value) {
        global $wpdb;
        $values['Attempts'] = 0;
        return $wpdb->update($wpdb->prefix . 'WPUser_LoginAttempts', $values, array('IP' => $value));
    }

    function loginLog($value){
        global $wpdb;
        $value['user_agent']=$_SERVER['HTTP_USER_AGENT'];
        $wpdb->insert($wpdb->prefix . 'wpuser_login_log', $value);
    }

    function wp_user_action_register_function(&$args) {
        //error_log("WP USER :Inside wp_user_action_register action");
        $to = $args['wp_user_email'];
        $wp_user_email_name = get_option('wp_user_email_name');
        $wp_user_email_id = get_option('wp_user_email_id');
        $sender = !empty($wp_user_email_name) ? $wp_user_email_name : get_option('blogname');
        $email = !empty($wp_user_email_id) ? $wp_user_email_id : get_option('admin_email');
        $subject = get_option('wp_user_email_admin_register_subject');
        $site_url = site_url();
        $headers[] = 'MIME-Version: 1.0' . "\r\n";
        $headers[] = 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
        $headers[] = "X-Mailer: PHP \r\n";
        $headers[] = 'From: ' . $sender . ' < ' . $email . '>' . "\r\n";

        if (get_option('wp_user_email_admin_register_enable')) {
            //error_log("WP USER :Inside wp_user_email_admin_register_enable");
            $email_header_text = get_option('wp_user_email_admin_register_subject');
            $email_body_text = apply_filters('wp_user_filter_email', get_option('wp_user_email_admin_register_content'), $to, $args['wp_user_email'], null, null, null);
            $email_footer_text = 'You\'re receiving this email because you have Enable notifiacion for new user register on ' . $site_url;
            include(WPUSER_PLUGIN_DIR . 'includes/user/module/template_email_defualt.php');
            $mail = wp_mail($email, $subject, $message, $headers);
            error_log("WP USER :New user registration: Mail send to Admin");
        }

        if (get_option('wp_user_email_user_register_enable')) {
            //error_log("WP USER :Inside wp_user_email_user_register_enable");
            $email_header_text = get_option('wp_user_email_user_register_subject');
            $email_body_text = apply_filters('wp_user_filter_email', get_option('wp_user_email_user_register_content'), $to, $args['wp_user_email'], null, null, null);
            $email_footer_text = 'You\'re receiving this email because you have register on ' . $site_url;
            include(WPUSER_PLUGIN_DIR . 'includes/user/module/template_email_defualt.php');
            $mail = wp_mail($to, $subject, $message, $headers);
            error_log("WP USER :New user registration: Mail send to $to");
        }
    }

    function wp_user_action_login_attempts_admin_notify_function(&$args) {
        if (get_option('wp_user_login_limit_admin_notify')) {
            //error_log("WP USER :Inside wp_user_action_login_attempts_admin_notify_function action");
            $subject = 'Login Attempts';
            $wp_user_email_name = get_option('wp_user_email_name');
            $wp_user_email_id = get_option('wp_user_email_id');
            $sender = !empty($wp_user_email_name) ? $wp_user_email_name : get_option('blogname');
            $email = !empty($wp_user_email_id) ? $wp_user_email_id : get_option('admin_email');
            $site_url = site_url();
            $headers[] = 'MIME-Version: 1.0' . "\r\n";
            $headers[] = 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
            $headers[] = "X-Mailer: PHP \r\n";
            $headers[] = 'From: ' . $sender . ' < ' . $email . '>' . "\r\n";
            $ip = $args[0];
            $accesTime = $args[1];
            $accesUserName = $args[2];
            $bodyText = '<p>A failed login attempt has occurred on' . $accesTime . '.
                            Someone from the ' . $ip . ' IP address  used the ' . $accesUserName . ' username  to attempt to login on ' . $site_url . '</p>
                 <p>If you did not attempt to access your site, please contact your Information Technology Security Team immediately.</p>
                 <p>
                 Server Date & Time: ' . $accesTime . ' <br>
                 From IP Address: ' . $ip . '
                 </p>';
            $email_header_text = 'SECURITY ALERT: Failed Login Attempt on ' . $site_url;
            $email_body_text = apply_filters('wp_user_filter_email', $bodyText, null, $accesUserName, null, null, null);
            $email_footer_text = ' You\'re receiving this email because you have enable setting (WP User) "Notify on lockout (Email to admin after)" on ' . $site_url;
            include(WPUSER_PLUGIN_DIR . 'includes/user/module/template_email_defualt.php');
            $mail = wp_mail($email, $subject, $message, $headers);
            error_log("WP USER :Login Attempts $ip");
        }
    }

    function wp_user_filter_email_function($value, $userEmail = null, $userName = null, $userFirstName = null, $userLastName = null, $newPassword = null) {
        $wp_user_email_name = get_option('wp_user_email_name');
        $wp_user_email_id = get_option('wp_user_email_id');
        $replace = array(
            '{WPUSER_ADMIN_EMAIL}' => !empty($wp_user_email_id) ? $wp_user_email_id : get_option('admin_email'),
            '{WPUSER_BLOGNAME}' => get_option('blogname'),
            '{WPUSER_LOGIN_URL}' => get_permalink(get_option('wp_user_page')),
            '{WPUSER_BLOG_ADMIN}' => !empty($wp_user_email_name) ? $wp_user_email_name : get_option('blogname'),
            '{WPUSER_BLOG_URL}' => get_option('siteurl'),
            '{WPUSER_USERNAME}' => $userName,
            '{WPUSER_FIRST_NAME}' => $userFirstName,
            '{WPUSER_LAST_NAME}' => $userLastName,
            '{WPUSER_NAME}' => $userName,
            '{WPUSER_EMAIL}' => $userEmail,
            '{WPUSER_NEW_PASSWORD}' => $newPassword
        );
        $value = str_replace(array_keys($replace), $replace, $value);
        return $value;
    }

}
