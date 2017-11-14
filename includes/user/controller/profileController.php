<?php
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}
final class profileController  {

    function wpuser_profile() {
        global $wpdb;
        $error=array();
        global $current_user, $wp_roles;
       // $data = array();
      //  $data = json_decode(file_get_contents("php://input"), true);
       // $creds = array();
        $str = file_get_contents(WPUSER_USER_DIR_i18n . '/' . get_option('wp_user_language') . '.json');
        $json = json_decode($str, true);

        $WP_USER_INPUT=(isset($_POST)) ? $_POST : '' ;
             if (isset($WP_USER_INPUT['wpuser_action']) && $WP_USER_INPUT['wpuser_action'] == 'update_wp_user') {

         if (!isset($WP_USER_INPUT['wpuser_update_setting'])){
                 $error[] = 'id form data. form request came from the somewhere else not current site!';
     }
      /* Update user password. */
     if (!empty($WP_USER_INPUT['pass1'])) {
          if(!empty($WP_USER_INPUT['pass2'])){
              if ($WP_USER_INPUT['pass1'] == $WP_USER_INPUT['pass2']){

                    $wp_user_login_limit_password = get_option('wp_user_login_limit_password');
                    $wp_user_login_limit_password_enable = get_option('wp_user_login_limit_password_enable');

                    if (isset($wp_user_login_limit_password_enable) && isset($wp_user_login_limit_password) && !empty($wp_user_login_limit_password) && !(preg_match($wp_user_login_limit_password, $WP_USER_INPUT['pass1']))) {

                              $wp_user_login_password_valid_message = get_option('wp_user_login_password_valid_message');
                              $error[] = !empty($wp_user_login_password_valid_message) ? $wp_user_login_password_valid_message : $json['INVALID'] . ' ' . $json['PASSWORD'];               
                            }
                    else{
                       wp_update_user(array('ID' => $current_user->ID, 'user_pass' => sanitize_text_field($WP_USER_INPUT['pass1'])));
                   }
              }else  {
             $error[] = $json['PASSWORD_IS_NOT_MATCH'];            
            } 
          }
          else{
           $error[] = $json['RETYPE_PASSWORD'];   
          }
        }  

 if (isset($WP_USER_INPUT['user_meta_image']))
    update_user_meta($current_user->ID,'user_meta_image',esc_url($WP_USER_INPUT['user_meta_image']));     
        

      /* Update user information. */
       if (!empty($WP_USER_INPUT['user_url']))
          wp_update_user(array('ID' => $current_user->ID, 'user_url' => esc_url($WP_USER_INPUT['user_url'])));
       if (!empty($WP_USER_INPUT['user_email'])) {
        if (!is_email(sanitize_text_field($WP_USER_INPUT['user_email'])))
             $error[] = $json['INVALID_EMAIL'];

        elseif (email_exists(sanitize_text_field($WP_USER_INPUT['user_email'])) ){
                if ( $user = get_user_by( 'email', $WP_USER_INPUT['user_email']) ) {
                     if($user->ID!= $current_user->ID){
                   $error[] = $json['THIS_EMAIL_IS_ALREADY_USED_BY_ANOTHER_USER'];
                  }
                  else {
            wp_update_user(array('ID' => $current_user->ID, 'user_email' => sanitize_text_field($WP_USER_INPUT['user_email'])));
              }
        }
          
        }
        else {
            wp_update_user(array('ID' => $current_user->ID, 'user_email' => sanitize_text_field($WP_USER_INPUT['user_email'])));
              }
         } 

    if (!empty($WP_USER_INPUT['first_name']))
        update_user_meta($current_user->ID, 'first_name', sanitize_text_field($WP_USER_INPUT['first_name']));
    if (!empty($WP_USER_INPUT['last_name']))
        update_user_meta($current_user->ID, 'last_name', sanitize_text_field($WP_USER_INPUT['last_name']));
    if (!empty($WP_USER_INPUT['description']))
        update_user_meta($current_user->ID, 'description', sanitize_text_field($WP_USER_INPUT['description']));

 if ( class_exists( 'WPSubscription' ) && get_option('wp_subscription_wp_user_register_list_id_enable')=='1') {
      $email=get_the_author_meta('user_email',get_current_user_id());
      $list_id=get_option('wp_subscription_wp_user_register_list_id');
      if(!empty($list_id) && !empty($email)){                 
                 
                  $values['list_id'] = $list_id; 
                  $values['form'] =$form="WP User";
                  $frist_name=$values['frist_name'] =(!empty($WP_USER_INPUT['first_name'])) ? sanitize_text_field($WP_USER_INPUT['first_name']): '';
                  $last_name=$values['last_name'] =(!empty($WP_USER_INPUT['last_name'])) ? sanitize_text_field($WP_USER_INPUT['last_name']) : '';
                  $values['email'] = $email;
                  $values['created_by'] = get_current_user_id();                  
                  
                  $is_active=isset($WP_USER_INPUT['wpuser_subscribe']) ? 1 : 0;

               $subscribers = $wpdb->get_results( "SELECT email FROM {$wpdb->prefix}wpsp_subscribers WHERE email LIKE '$email' AND list_id=$list_id" );                           
                             if($wpdb->num_rows > 0){
                                //update status
                              $wpdb->update($wpdb->prefix.'wpsp_subscribers',array('is_active'=>$is_active),array('email'=> $email,'list_id'=>$list_id));
                             }else{

                        if ($wpdb->insert("{$wpdb->prefix}wpsp_subscribers",$values)) {
                            $args = array($list_id,$email,$frist_name,$last_name,'');
                            do_action_ref_array('wp_subscription_add_subscriber', array(&$args));
                        } 
                    }
       
     }
    }

    if (count($error) == 0) {
          $result['message'] = 'Profile Updated';
            $result['status'] = 'success';          
    }else{
         $result['message'] = implode(',', $error);
            $result['status'] = 'warning';
     }   
    }
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
    $result['wp_user_profile_img']=$wp_user_profile_img;
    $result['first_name']=get_the_author_meta('first_name',get_current_user_id());
    $result['last_name']=get_the_author_meta('last_name',get_current_user_id());
    $result['user_email']=get_the_author_meta('user_email',get_current_user_id());
    $result['user_url']=get_the_author_meta('user_url',get_current_user_id());
    $result['description']=get_the_author_meta('description',get_current_user_id());  


  print_r(json_encode($result));
  die();
}
   
function wpuser_contact() {
        global $wpdb;
        $error=array();
        global $current_user, $wp_roles;
        $current_user = wp_get_current_user();

        $WP_USER_INPUT=(isset($_POST)) ? $_POST : '' ;

        if (isset($WP_USER_INPUT['wpuser_action']) && $WP_USER_INPUT['wpuser_action'] == 'contact_wp_user') {
         
          if(!empty($WP_USER_INPUT['wp_user_email_subject']) && !empty($WP_USER_INPUT['wp_user_email_content'])){
                
                  $wp_user_email_name = get_option('wp_user_email_name');
                  $wp_user_email_id = get_option('wp_user_email_id');
                  $sender = !empty($wp_user_email_name) ? $wp_user_email_name : get_option('blogname');
                  $email = !empty($wp_user_email_id) ? $wp_user_email_id : get_option('admin_email');
                  $subject = get_option('wp_user_email_admin_register_subject');
                  $site_url = site_url();
                  $headers[] = 'MIME-Version: 1.0' . "\r\n";
                  $headers[] = 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
                  $headers[] = "X-Mailer: PHP \r\n";
                  $headers[] = 'From: ' . $sender . ' < ' . $current_user->user_email . '>' . "\r\n";
                  $message=$WP_USER_INPUT['wp_user_email_content'];

                  $message.= '<br>Username: ' . $current_user->user_login . '<br />';
                  $message.= 'User email: ' . $current_user->user_email . '<br />'; 
          
                if(!(wp_mail($email,$WP_USER_INPUT['wp_user_email_subject'],$message, $headers)))
                    $error[] = "Error : Mail Not send";

           }else{
             $error[] = "All field are required";
          }           
      }else{
        $error[] = "Invalid Action";
      }

    if (count($error) == 0) {
          $result['message'] = ' Mail send to admin';
            $result['status'] = 'success';          
    }else{
         $result['message'] = implode(',', $error);
            $result['status'] = 'warning';
    }

    print_r(json_encode($result));
    die();
      } 

      function wpuser_address() {
        global $wpdb;
        $error=array();
        global $current_user, $wp_roles;       

        $WP_USER_INPUT=(isset($_POST)) ? $_POST : '' ;

        if ((isset($WP_USER_INPUT['wpuser_action']) && $WP_USER_INPUT['wpuser_action'] == 'address_wp_user')) { 
              unset($WP_USER_INPUT['wpuser_action']);
              unset($WP_USER_INPUT['wpuser_update_setting']);
              unset($WP_USER_INPUT['wpuser_address']);
          foreach ($WP_USER_INPUT as $key => $value) {             
                update_user_meta($current_user->ID,$key,sanitize_text_field($value));        
      }         

      }else{
        $error[] = "Invalid Action";
      }

      if (count($error) == 0) {
          $result['message'] = 'Address Updated';
            $result['status'] = 'success';          
    }else{
         $result['message'] = implode(',', $error);
            $result['status'] = 'warning';
    }
     

    print_r(json_encode($result));
    die();
    }   

     public static function get_attachment_image_by_url( $url ) {
 
    // Split the $url into two parts with the wp-content directory as the separator.
    $parse_url  = explode( parse_url( WP_CONTENT_URL, PHP_URL_PATH ), $url );
 
    // Get the host of the current site and the host of the $url, ignoring www.
    $this_host = str_ireplace( 'www.', '', parse_url( home_url(), PHP_URL_HOST ) );
    $file_host = str_ireplace( 'www.', '', parse_url( $url, PHP_URL_HOST ) );
 
    // Return nothing if there aren't any $url parts or if the current host and $url host do not match.
    if ( !isset( $parse_url[1] ) || empty( $parse_url[1] ) || ( $this_host != $file_host ) ) {
        return;
    }
 
    // Now we're going to quickly search the DB for any attachment GUID with a partial path match.
    // Example: /uploads/2013/05/test-image.jpg
    global $wpdb;
 
    $prefix     = $wpdb->prefix;
    $attachment = $wpdb->get_col( $wpdb->prepare( "SELECT ID FROM " . $prefix . "posts WHERE guid RLIKE %s;", $parse_url[1] ) );
 
    // Returns null if no attachment is found.
    return $attachment[0];
} 

function get_member_list(){
        global $wpdb;
         $data = array(); 
         $result= array();
              $number=(isset($_REQUEST['users_num']) && !empty($_REQUEST['users_num'])) ? $_REQUEST['users_num'] : '';               
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
                        'fields'       => array( 'ID')
                     );                


            $result = get_users($args);
              foreach ($result as $key => $value) { 
                 $wp_user_labels=apply_filters( 'wp_user_member_filter', $wp_user_member_filter,$value->ID) ;
                     $attachment_url = esc_url( get_the_author_meta( 'user_meta_image',$value->ID) ); 
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
                         $args = get_avatar_data($value->ID, $args );
                         if(!empty($args['url']))
                           $wp_user_profile_img=$args['url'];
                         else 
                        $wp_user_profile_img= WPUSER_PLUGIN_URL . 'assets/images/wpuser.png';
                      }
                      $name=get_the_author_meta('first_name',$value->ID)." ".get_the_author_meta('last_name',$value->ID);
                      if(empty(str_replace(' ','',$name))) {                        
                           $user_info = get_userdata($value->ID);
                           $name=$user_info->display_name;
                           if(empty($name)){
                            $name=$user_info->user_nicename;
                           }
                           if(empty($name)){
                            $name=$user_info->user_login;
                           }
                      }
                      
                      $data[] = array(
                            "id"            => $value->ID,                    
                            "name"     =>$name ,
                            'labels'=> $wp_user_labels ,
                            "description"     => get_the_author_meta('description',$value->ID),
                            "wp_user_profile_img"     => $wp_user_profile_img            
                    );
              }
             print_r(json_encode($data));     
             die();
}
function viewMember(){
  global $wpdb;
         $data = array(); 
         $result= array();
         $member_post=array();
         $value = json_decode(file_get_contents("php://input"));  
   $wp_user_labels=apply_filters( 'wp_user_member_filter', $wp_user_member_filter,$value->id) ;
                     $attachment_url = esc_url( get_the_author_meta( 'user_meta_image',$value->id) ); 
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
                         $args = get_avatar_data($value->id, $args );
                         if(!empty($args['url']))
                           $wp_user_profile_img=$args['url'];
                         else 
                        $wp_user_profile_img= WPUSER_PLUGIN_URL . 'assets/images/wpuser.png';
                      }
                      $name=get_the_author_meta('first_name',$value->id)." ".get_the_author_meta('last_name',$value->id);
                      if(empty(str_replace(' ','',$name))) {                        
                           $user_info = get_userdata($value->id);
                           $name=$user_info->display_name;
                           if(empty($name)){
                            $name=$user_info->user_nicename;
                           }
                           if(empty($name)){
                            $name=$user_info->user_login;
                           }
                      }
                       $authors_posts=get_posts(array( 'author' =>$value->id,'post_status'=>'publish'));
                       foreach ($authors_posts as $authors_posts) {                             
                                $member_post[] = array(
                                  "ID"=>$authors_posts->ID,
                                  "post_date"=>$authors_posts->post_date,
                                  "post_title"=>$authors_posts->post_title,
                                  "post_content"=>$authors_posts->post_content,
                                  "comment_count"=>$authors_posts->comment_count,
                                  "permalink"=>get_permalink($authors_posts->ID)
                                  );
                              }

                      $data = array(
                            "id"            => $value->id,                    
                            "name"     =>$name ,
                            'labels'=> $wp_user_labels ,
                            "description"     => get_the_author_meta('description',$value->id),
                            "wp_user_profile_img"     => $wp_user_profile_img,
                            "authors_posts" =>$member_post
                    );
                        print_r(json_encode($data));     
             die();

}
}
/*
function wp_user_member_filter($wp_user_member_filter,$id) {
    $wp_user_member_filter[] =array(
      'label'=>'Posts',
      'value'=>count_user_posts($id)
      );
    return $wp_user_member_filter;
}
add_filter( 'wp_user_member_filter', 'wp_user_member_filter',10,2 );
 */