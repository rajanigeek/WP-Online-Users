<?php 
       wp_enqueue_script('jquery');
       wp_enqueue_style('wpce_bootstrap', WPUSER_PLUGIN_URL . 'assets/css/bootstrap.min.css');
       wp_enqueue_style('wpdbadminltecss', WPUSER_PLUGIN_URL . "assets/dist/css/AdminLTE.css");
  
        wp_enqueue_script('jquery');
        wp_enqueue_script('wpangularjs', WPUSER_PLUGIN_URL . "assets/angular.min.js");
        wp_enqueue_script('wpangularjs');

        wp_enqueue_script('wpuserappcontroller', WPUSER_PLUGIN_URL . "assets/userController.js");
        wp_enqueue_script('wpuserappcontroller');
        
        wp_enqueue_script('wpangularjsresource', WPUSER_PLUGIN_URL . "assets/angular-resource.js");
        wp_enqueue_script('wpangularjsresource');

        wp_enqueue_script('wpangularroughtjs', WPUSER_PLUGIN_URL . "assets/angular-route.min.js");
        wp_enqueue_script('wpangularroughtjs');  
        
         wp_enqueue_script('wpangularrecaptchaapijs', 'https://www.google.com/recaptcha/api.js?onload=vcRecaptchaApiLoaded&render=explicit');
        wp_enqueue_script('wpangularrecaptchaapijs');
        
        wp_enqueue_script('wpangularrecaptchajs', WPUSER_PLUGIN_URL . "assets/angular-recaptcha.min.js");
        wp_enqueue_script('wpangularrecaptchajs');

        $isUserLogged = (is_user_logged_in()) ? 1 : 0;
        $wp_user_appearance_skin = get_option('wp_user_appearance_skin') ? get_option('wp_user_appearance_skin') : 'default';
         
        if(isset($atts['login_redirect'])){
            $login_redirect=$atts['login_redirect'];
        }else{
             $login_redirect=get_permalink(get_option('wp_user_page'));
        }
        $localize_script_data = array(
            'wpuser_ajax_url' => admin_url('admin-ajax.php'),
            'wpuser_site_url' => site_url(),
            'plugin_url' => WPUSER_PLUGIN_URL,
            'plugin_dir' => WPUSER_PLUGIN_DIR,
            'wpuser_user_templateUrl' => WPUSER_USER_TEMPLETE_URL,
            'wpuser_user_i18n' => WPUSER_USER_i18n,
            'wpuser_user_templateSkin' => $wp_user_appearance_skin,
            'user_logged_in' => $isUserLogged,
            'login_redirect' => $login_redirect,
            'wpuser_lang'=>get_option('wp_user_language')
        );
        wp_localize_script('wpuserappcontroller', 'wpuser_link', $localize_script_data);
       

        wp_enqueue_script('wpbootstraptpls', WPUSER_PLUGIN_URL . "assets/ui-bootstrap-tpls-0.10.0.min.js");
        wp_enqueue_script('wpbootstraptpls');

        wp_enqueue_script('wpdbbootstrap', WPUSER_PLUGIN_URL . "assets/bootstrap/js/bootstrap.min.js");
        wp_enqueue_script('wpdbbootstrap');

        wp_enqueue_script('wpdbraphael', "https://cdnjs.cloudflare.com/ajax/libs/raphael/2.1.0/raphael-min.js");
        wp_enqueue_script('wpdbraphael');  