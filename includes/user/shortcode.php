<?php
if (!defined('ABSPATH'))
    exit; // Exit if accessed directly

final class WPUserShortcode {

    public function __construct() {
        add_action('wp_enqueue_scripts', array($this, 'loadScripts'));
        add_shortcode('wp_user_angular', array($this, 'wp_user_angular'));
        add_shortcode('wp_user', array($this, 'wp_user'));
        add_shortcode('wp_user_member', array($this, 'wp_user_member'));
    }

    function loadScripts() {
         }

    function wp_user($atts) {
        $form_id = time().rand(2,999);
        $login_redirect="";
        include('assets.php');
        ob_start();
        echo '<style>' . get_option('wp_user_appearance_custom_css') . '</style>';
        echo '<div class="bootstrap-wrapper wp_user support_bs">';  
        $wp_user_appearance_skin = get_option('wp_user_appearance_skin') ? get_option('wp_user_appearance_skin') : 'default';   
        if(isset($atts['login_redirect'])){
            $login_redirect=$atts['login_redirect'];
        }else{
            // $login_redirect=get_permalink(get_option('wp_user_page'));
        }

        $str = file_get_contents(WPUSER_USER_DIR_i18n . '/' . get_option('wp_user_language') . '.json');
        $json = json_decode($str, true);         
            $login_class='';
            $register_class='';
            $forgot_class='';
            if (isset($atts['active']) && $atts['active'] == 'register') {
                 $register_class='active';
             }else  if (isset($atts['active']) && $atts['active'] == 'forgot') {
                 $forgot_class='active';
             }else{
                $login_class='active';
            }
             if (isset($atts['popup']) && $atts['popup'] == 1) {
                $form_id=$form_id.'p';
            if (is_user_logged_in()) {
                echo '<a href="' . wp_logout_url(get_permalink()) . '" title="'.$json['LOGOUT'].'">'.$json['LOGOUT'].'</a>';
            } else {
                ?>
                <div ng-app="listpp" ng-app lang="en">
                    <!-- Button trigger modal -->
                    <a type="button" data-toggle="modal" data-target="#wp_login<?php echo $form_id?>">
                    <?php if (isset($atts['active']) && $atts['active'] == 'register') {
                               echo $json['SIGN_UP'];
                     }else{
                               echo $json['SIGN_IN'];
                     } ?>
                    </a>
                    <!-- Modal -->
                    <div class="modal fade" role="dialog" id="wp_login<?php echo $form_id?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
                        <div class="modal-dialog" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                </div>
                                <div class="modal-body">
                                    <?php                    
                               include("views/templates/".$wp_user_appearance_skin."/".$wp_user_appearance_skin."View.php");   
                                include('script.php');
                        ?>
                                </div>     
                            </div>
                        </div>
                    </div>


                </div>
                <?php
            }
        } else if (is_user_logged_in()) {
               wp_enqueue_script( 'jquery' );              
    wp_enqueue_style('wpce_bootstrap', WPUSER_PLUGIN_URL . 'assets/css/bootstrap.min.css');
    wp_enqueue_style('wpdbadminltecss', WPUSER_PLUGIN_URL . "assets/dist/css/AdminLTE.css");  

    wp_enqueue_script('wpdbbootstrap', WPUSER_PLUGIN_URL . "assets/bootstrap/js/bootstrap.min.js");
    wp_enqueue_script('wpdbbootstrap');

    wp_enqueue_script('wpdbbootstraprecaptcha',"https://www.google.com/recaptcha/api.js");
    wp_enqueue_script('wpdbbootstraprecaptcha');
                include('profile.php');
            } else {  

                       include("views/templates/".$wp_user_appearance_skin."/".$wp_user_appearance_skin."View.php");   
                        include('script.php');
            }        
        echo '</div>';
        return ob_get_clean();
    }
    function wp_user_member($atts){
            
        wp_enqueue_script('jquery');
       wp_enqueue_style('wpce_bootstrap', WPUSER_PLUGIN_URL . 'assets/css/bootstrap.min.css');
       wp_enqueue_style('wpdbadminltecss', WPUSER_PLUGIN_URL . "assets/dist/css/AdminLTE.css");
  
        wp_enqueue_script('jquery');
        wp_enqueue_script('wpangularjs', WPUSER_PLUGIN_URL . "assets/angular.min.js");
        wp_enqueue_script('wpangularjs');

        wp_enqueue_script('wpuserappcontroller', WPUSER_PLUGIN_URL . "assets/userListController.js");
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

              

          wp_enqueue_script('userListController', WPUSER_PLUGIN_URL . "assets/js/angular/userListController.js");

        ob_start();
        echo '<style>' . get_option('wp_user_appearance_custom_css') . '</style>';
        echo '<div class="bootstrap-wrapper support_bs">';
        echo '<div ng-app="listpp" ng-app lang="en">
                    <div ng-view></div>                   
              </div>';
        echo '</div>';
        return ob_get_clean();
    }

    function wp_user_angular($atts) {

            

         wp_enqueue_script('wpuserlogincontroller', WPUSER_PLUGIN_URL . "assets/js/angular/loginController.js");
        wp_enqueue_script('wpuserforgotcontroller', WPUSER_PLUGIN_URL . "assets/js/angular/forgotController.js");
        wp_enqueue_script('wpuserregistercontroller', WPUSER_PLUGIN_URL . "assets/js/angular/registerController.js");

        include('assets_angular.php');
              
        ob_start();
        echo '<style>' . get_option('wp_user_appearance_custom_css') . '</style>';
        echo '<div class="bootstrap-wrapper support_bs">';
        if (isset($atts['popup']) && $atts['popup'] == 1) {
            if (is_user_logged_in()) {
                echo '<a href="' . wp_logout_url(get_permalink()) . '" title="Logout">Logout</a>';
            } else {
                ?>
                <div ng-app="listpp" ng-app lang="en">
                    <!-- Button trigger modal -->
                    <a type="button" data-toggle="modal" data-target="#wp_login">
                        Login
                    </a>
                    <!-- Modal -->
                    <div class="modal fade" id="wp_login" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
                        <div class="modal-dialog" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                </div>
                                <div class="modal-body">
                                    <div ng-view></div> 
                                </div>     
                            </div>
                        </div>
                    </div>


                </div>
                <?php
            }
        } else {
            if (is_user_logged_in()) {
                include('profile.php');
            } else {
                ?>
                <div ng-app="listpp" ng-app lang="en">
                    <div ng-view></div>                   
                </div>
                <?php
            }
        }
        echo '</div>';
        return ob_get_clean();
    }

}

$GLOBALS['WPUserShortcode'] = new WPUserShortcode();
add_filter( 'get_avatar' , 'wp_user_custom_avatar' , 1 , 5 );

function wp_user_custom_avatar( $avatar, $id_or_email, $size, $default, $alt ) {
    $user = false;


    if ( is_numeric( $id_or_email ) ) {

        $id = (int) $id_or_email;
        $user = get_user_by( 'id' , $id );

    } elseif ( is_object( $id_or_email ) ) {

        if ( ! empty( $id_or_email->user_id ) ) {
            $id = (int) $id_or_email->user_id;
            $user = get_user_by( 'id' , $id );
        }

    } else {
        $user = get_user_by( 'email', $id_or_email ); 
    }

    if ( $user && is_object( $user ) ) {

        if ( $user->data->ID == '1' ) {
           global $current_user, $wp_roles;
           $attachment_url = esc_url( get_the_author_meta( 'user_meta_image',get_current_user_id()) ); 
     $attachment_id = profileController::get_attachment_image_by_url( $attachment_url );
    // retrieve the thumbnail size of our image
    $image_thumb = wp_get_attachment_image_src( $attachment_id, 'thumbnail' );
                  if(!empty($image_thumb[0])){
              $avatar=$image_thumb[0];
            }else if(!empty($attachment_url)){
              $avatar= $attachment_url;
            }else
            $avatar =$avatar;
            if(!empty($attachment_url))
           $avatar = "<img alt='{$alt}' src='{$avatar}' class='avatar avatar-{$size} photo' height='{$size}' width='{$size}' />";
        }

    }

    return $avatar;
}

add_filter( 'ajax_query_attachments_args', "user_restrict_media_library" );
function user_restrict_media_library(  $query ) {
    global $current_user;
    $query['author'] = $current_user->ID ;
    return $query;
}