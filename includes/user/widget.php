<?php
// Creating the widget 
class wpuserwidget extends WP_Widget {

function __construct() {
parent::__construct(
// Base ID of your widget
'wpuserwidget', 

// Widget name will appear in UI
__('WP-User', 'wp-user'), 

// Widget description
array( 'description' => __( 'Login, Register, Forgot Password Form', 'wp-user' ), ) 
);
}

// Creating widget front-end
// This is where the action happens
public function widget( $args, $instance ) {
$title = apply_filters( 'widget_title', $instance['title'] );
 $form_id= $index= $atts['id']= $instance['wp_user_title'];
// before and after widget arguments are defined by themes
echo $args['before_widget'];
if ( ! empty( $title ) )
echo $args['before_title'] . $title . $args['after_title'];
global $wpdb;
$page_id=$post_page_title_ID->ID;
 include('assets.php');       
 $str = file_get_contents(WPUSER_USER_DIR_i18n.'/'.get_option('wp_user_language').'.json');
 $json = json_decode($str, true);
        echo '<style>' . get_option('wp_user_appearance_custom_css') . '</style>';
        echo '<div class="bootstrap-wrapper wp_user support_bs">';    
            if (is_user_logged_in()) {
            	global $current_user, $wp_roles;
$attachment_url = esc_url( get_the_author_meta( 'user_meta_image',get_current_user_id()) ); 
     $attachment_id = profileController::get_attachment_image_by_url( $attachment_url );
    // retrieve the thumbnail size of our image
    $image_thumb = wp_get_attachment_image_src( $attachment_id, 'thumbnail' );
    // return the image thumbnail
    if(!empty($image_thumb[0])){
      $wp_user_profile_img=$image_thumb[0];
    }else if(!empty($attachment_url)){
      $wp_user_profile_img= $attachment_url;
    }
    else {
      $args = get_avatar_data(get_current_user_id(), $args );
       if(!empty($args['url']))
         $wp_user_profile_img=$args['url'];
       else 
      $wp_user_profile_img= WPUSER_PLUGIN_URL . 'assets/images/wpuser.png';
    }
                 echo ' <div class="box">
                             <div class="box-header">
                             <div class="row">
                             <div class="col-md-3">
                             <img class="img-responsive img-circle wp_user_profile_img" style="width:50px" src="' . $wp_user_profile_img . '" alt="User Avatar">
                             <a href="' . wp_logout_url(get_permalink()) . '" class="" title="Logout">'.$json['LOGOUT'].'</a>                 
                             </div>
                             <div class="col-md-9"> 
                            <h3 class="profile-username"> <a href="' .get_permalink(get_option('wp_user_page')) . '" title="My Account">' . $current_user->user_login . '</a></h3>
                            </button>
                              </div>                 
                             </div>
                         </div>
                         <div class="box-body">';             
                            do_action( 'wp_user_hook_widget'); 
                echo '   </div>
                        </div>'; 
            } else {  

                            $login_class='active';
                            $login_redirect="";
                            $register_class='';
                            $forgot_class='';

                        if ( isset( $instance[ 'wp_user_title' ] ) ) {
                            $wp_user_title = $instance[ 'wp_user_title' ];
                            }

                        $wp_user_appearance_skin =( isset( $instance[ 'wp_user_title' ] ) ) ? $instance[ 'wp_user_title' ] : 'default';    

                       include("views/templates/".$wp_user_appearance_skin."/".$wp_user_appearance_skin."View.php");   
                       include('script.php');
            }        

        echo '</div>';        
echo $args['after_widget'];
}
		
// Widget Backend 
public function form( $instance ) {
if ( isset( $instance[ 'title' ] ) ) {
$title = $instance[ 'title' ];
}
else {
$title = __( 'My Account ', 'wp-user' );
}
if ( isset( $instance[ 'wp_user_title' ] ) ) {
$wp_user_title = $instance[ 'wp_user_title' ];
}
// Widget admin form
?>
<p>
<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:' ); ?></label> 
<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" />
</p>
<?php 
global $wpdb;
		       
		
		$return_dp='<select name="'.$this->get_field_name( 'wp_user_title' ).' " class="widefat" id="'.$this->get_field_id( '	wp_user_title' ).'" >';               
    $select=($wp_user_title=="default") ? "selected" :"";
			$return_dp.='<option '.selected($wp_user_title,"default",false).' value="default">Default</option>';     
			$return_dp.='<option  '.selected($wp_user_title,"block",false).' value="block">Block</option>';         
			$return_dp.='</select>';       
		echo $return_dp;
		
}
	
// Updating widget replacing old instances with new
public function update( $new_instance, $old_instance ) {
$instance = array();
$instance['title'] = ( ! empty( $new_instance['title'] ) ) ? strip_tags( $new_instance['title'] ) : '';
$instance['wp_user_title'] = ( ! empty( $new_instance['wp_user_title'] ) ) ? strip_tags( $new_instance['wp_user_title'] ) : '';
return $instance;
}
} // Class wpbdpwidget ends here




// Register and load the widget
function wpuserwidget_form() {
	register_widget( 'wpuserwidget' );       
}

add_action( 'widgets_init', 'wpuserwidget_form' );