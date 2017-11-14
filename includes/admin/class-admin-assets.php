<?php

if (!defined('ABSPATH'))
    exit; // Exit if accessed directly
if (!class_exists('WPUserAdminAssets')) :

    class WPUserAdminAssets {

        public function __construct() {
            add_action('init', array($this, 'admin_scripts'));
        }

        // Enqueue scripts
        public function admin_scripts() {
            if (isset($_GET['page'])) {

                if ($_GET['page'] == "wp-user-setting") {

                    wp_enqueue_script('jquery');

                    wp_enqueue_script('wpdb', WPUSER_PLUGIN_URL . "assets/plugins/jQuery/jQuery-2.1.4.min.js");
                    wp_enqueue_script('wpdb');

                     wp_enqueue_script('wpinternetdownload', WPUSER_PLUGIN_URL . "assets/js/internetdownload.js");
                    wp_enqueue_script('wpinternetdownload');

                    wp_enqueue_script('wpdbjquery', "https://code.jquery.com/ui/1.11.4/jquery-ui.min.js");
                    wp_enqueue_script('wpdbjquery');
                            wp_enqueue_script('wpangularjs', WPUSER_PLUGIN_URL . "assets/angular.min.js");
                            wp_enqueue_script('wpangularjs');
                            
                            wp_enqueue_script('wpangularroughtjs', WPUSER_PLUGIN_URL . "assets/angular-route.min.js");
        wp_enqueue_script('wpangularroughtjs');
        
         //wp_enqueue_script('wpangularjsresource', "http://code.angularjs.org/1.2.14/angular-resource.js");
        wp_enqueue_script('wpangularjsresource', WPUSER_PLUGIN_URL . "assets/angular-resource.js");
        wp_enqueue_script('wpangularjsresource');
        



                   // wp_enqueue_script('wpangularjs', "http://ajax.googleapis.com/ajax/libs/angularjs/1.1.5/angular.min.js");
                    // wp_enqueue_script('wpangularjs', WPUSER_PLUGIN_URL ."assets/angular.min.js");
                  //  wp_enqueue_script('wpangularjs');

                    wp_enqueue_script('wpappcontroller', WPUSER_PLUGIN_URL . "assets/controller.js");
                    wp_enqueue_script('wpappcontroller');

                    $localize_script_data = array(
                        'wpuser_ajax_url' => admin_url('admin-ajax.php'),
                        'wpuser_site_url' => site_url(),
                        'plugin_url' => WPUSER_PLUGIN_URL,
                        'wpuser_templateUrl' => WPUSER_TEMPLETE_URL,
                        'plugin_dir' => WPUSER_PLUGIN_DIR,
                         'wpuser_user_i18n' => WPUSER_USER_i18n,         
                         'wpuser_lang'=>get_option('wp_user_language')
                    );
                    wp_localize_script('wpappcontroller', 'wpuser_link', $localize_script_data);

                    wp_enqueue_script('wpbootstraptpls', WPUSER_PLUGIN_URL . "assets/ui-bootstrap-tpls-0.10.0.min.js");
                    wp_enqueue_script('wpbootstraptpls');

                    wp_enqueue_script('wpdbbootstrap', WPUSER_PLUGIN_URL . "assets/bootstrap/js/bootstrap.min.js");
                    wp_enqueue_script('wpdbbootstrap');

                    wp_enqueue_script('wpdbapp', WPUSER_PLUGIN_URL . "assets/dist/js/app.min.js");
                    wp_enqueue_script('wpdbapp');

                   // wp_enqueue_script('wpdbpages', WPUSER_PLUGIN_URL . "assets/dist/js/pages/dashboard.js");
                   // wp_enqueue_script('wpdbpages');                  

                    wp_enqueue_style('wpdbbootstrapcss', WPUSER_PLUGIN_URL . "assets/css/bootstrap.min.css");
                    wp_enqueue_style('wpdbbootstrapcss');

                    wp_enqueue_style('wpdbbootstrapcdncss', "https://maxcdn.bootstrapcdn.com/font-awesome/4.4.0/css/font-awesome.min.css");
                    wp_enqueue_style('wpdbbootstrapcdncss');

                    wp_enqueue_script('wpdbuibutton', WPUSER_PLUGIN_URL . "assets/js/uibutton.js");
                    wp_enqueue_script('wpdbuibutton');

                    wp_enqueue_style('wpdbadminltecss', WPUSER_PLUGIN_URL . "assets/dist/css/AdminLTE.css");
                    wp_enqueue_style('wpdbadminltecss');

                    wp_enqueue_style('wpdbbskinscss', WPUSER_PLUGIN_URL . "assets/dist/css/skins/_all-skins.min.css");
                    wp_enqueue_style('wpdbbskinscss');

                    wp_enqueue_style('wpdbiCheckcss', WPUSER_PLUGIN_URL . "assets/plugins/iCheck/flat/blue.css");
                    wp_enqueue_style('wpdbiCheckcss');

                    wp_enqueue_style('wpdbtoastrcss', WPUSER_PLUGIN_URL . "assets/css/toastr.css");
                    wp_enqueue_style('wpdbtoastrcss');

                    wp_enqueue_script('wpdbtoastr', WPUSER_PLUGIN_URL . "assets/js/toastr.js");
                    wp_enqueue_script('wpdbtoastr');
                }
            }
        }

    }

    endif;

$obj = new WPUserAdminAssets();
