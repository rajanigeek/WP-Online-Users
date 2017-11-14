<?php
class WPUserProfile {
  public function __construct() {

  }
  static function my_account (){
          global $current_user, $wp_roles;
          $str = file_get_contents(WPUSER_USER_DIR_i18n.'/'.get_option('wp_user_language').'.json');
          $json = json_decode($str, true);

     $wp_user_profile_field['basic']=
           array(
        'title' => 'Basic Information',
        'fields' => array(
          'first_name' => array(
            'label'       =>$json['FIRST_NAME'],
            'icon' => '',
            'description' => ''
          ),
          'last_name' => array(
            'label'       =>$json['LAST_NAME'],
            'icon' => '',
            'description' => ''
          ),
          'user_email' => array(
            'label'       =>$json['EMAIL'],
            'icon' => '',
            'description' => '',
            'type' =>'email',
            'required'=>'required'
          ),
          'user_url' => array(
            'label'       =>$json['WEBSITE'],
            'icon' => '',
            'description' => ''
          ),
          'pass1' => array(
            'label'       =>$json['PASSWORD'],
            'icon' => '',
            'description' => $wp_user_login_password_valid_message,
            'type' =>'password'
          ),
          'pass2' => array(
            'label'       =>$json['RETYPE_PASSWORD'],
            'description' => '',
            'icon' => '',
            'type' =>'password'
          ),
          'description' => array(
            'label'       =>$json['BIOLOGICAL_INFORMATION'],
            'description' => '',
            'icon' => '',
            'type' =>'textarea'
          )          
        )
      );

  do_action('wp_user_profile_my_account_header');

   echo '<div class="text-right">'; 
      if ( in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ) {        
         $myaccount_page_id = get_option( 'woocommerce_myaccount_page_id' );
        if ( $myaccount_page_id ) {
          $myaccount_page_url = get_permalink( $myaccount_page_id );
           echo '<a href="'.$myaccount_page_url.'/orders" target="_blank" class="btn btn-default btn-flat">Orders</a> ';
        }
      } 
  echo '</br></br></div>';

$wp_user_profile_field_filter=apply_filters( 'wp_user_profile_field_filter', $wp_user_profile_field) ;

           foreach ($wp_user_profile_field_filter as $key => $array) {
     
        echo '<div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">
        <div class="panel panel-default">
          <div class="panel-heading" role="tab" id="headingOne">
            <label class="panel-title">
              <a role="button" data-toggle="collapse" data-parent="#accordion" href="#my_accout_collapse'.$key.'" aria-expanded="true" aria-controls="collapseOne">';
         echo $array['title'];
         echo '</a>
            </label>
          </div>
          <div id="my_accout_collapse'.$key.'" class="panel-collapse collapse in" role="tabpanel" aria-labelledby="headingOne">
                    <div class="panel-body">
                    <div class="row">
                    ';
              echo '<div class="col-md-6">   
                <label>'.$json['USERNAME'].' </label> <p class="text-muted">' . $current_user->user_login . ' </p> </div>
                <div class="col-md-6"> <label>'.$json['DISPLAY_USERNAME'].'</label> <p class="text-muted">' . $current_user->display_name .' </p> </div>';
          foreach ($array['fields'] as $key => $value) {
             $textValue=($fieldType!='password') ? get_the_author_meta($key,get_current_user_id()) : '';
            if($value['type']!='password' && !empty($textValue)){
              echo '<div class="col-md-6">';
              if(!get_option('wp_user_appearance_icon') && !empty($value['icon'])){
                    echo '<i class="'.$value['icon'].'"> </i> ';
                } 
              echo '<label for="'.$key.'"> '.$value['label'].'</label>';
               
                echo '<p class="text-muted '.$key.'" id="'.$key.'">'.$textValue.'</p>'; 
                 echo '</div>';
                              
            }
          }            
          echo '</div>
          </div>
          </div>
          </div>
          </div>';
//Check if woocomerce
}
if ( class_exists( 'WC_Admin_Profile' ) ) {
 $array =  WC_Admin_Profile::get_customer_meta_fields();
 echo '<div class="row">';
 $i=1;
foreach ($array as $array) {  
 echo '<div class="col-md-6 panel-group" id="customer_meta_accordion'. $i.'" role="tablist" aria-multiselectable="true">
        <div class="panel panel-default">
          <div class="panel-heading" role="tab" id="headingOne'. $i.'">
            <label class="panel-title">
              <a role="button" data-toggle="collapse" data-parent="#customer_meta_accordion'. $i.'" href="#customer_meta_collapse'. $i.'" aria-expanded="true" aria-controls="collapseOne'. $i.'">';
         echo $array['title'];
         echo '</a>
            </label>
          </div>
          <div id="customer_meta_collapse'. $i.'" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingOne'. $i.'">
                    <div class="panel-body">
                    ';

       foreach ($array['fields'] as $key => $value) {
       $textValue=get_user_meta( get_current_user_id(),$key,true);
            if(!empty($textValue)){ 
                echo '<span class="text-muted" id="'.$key.'">'.$textValue.'</span> '; 
             }
      }

        echo '</div>
            </div>
          </div>
           </div>';
            $i++;
   }
    echo '</div>';

  }         
   do_action('wp_user_profile_my_account_footer');
}

static function edit_profile (){
  global $wpdb;
      global $current_user, $wp_roles;
       $str = file_get_contents(WPUSER_USER_DIR_i18n.'/'.get_option('wp_user_language').'.json');
          $json = json_decode($str, true);
                    
                    $wp_user_login_limit_password = get_option('wp_user_login_limit_password');
                    $wp_user_login_limit_password_enable = get_option('wp_user_login_limit_password_enable');
                    $wp_user_login_password_valid_message=(isset($wp_user_login_limit_password_enable) && isset($wp_user_login_limit_password)) ? 
                      get_option('wp_user_login_password_valid_message') : '';  


          $wp_user_profile_field['basic']=
           array(
        'title' => 'Basic Information',
        'fields' => array(
          'first_name' => array(
            'label'       =>$json['FIRST_NAME'],
            'icon' => '',
            'description' => ''
          ),
          'last_name' => array(
            'label'       =>$json['LAST_NAME'],
            'icon' => '',
            'description' => ''
          ),
          'user_email' => array(
            'label'       =>$json['EMAIL'],
            'icon' => '',
            'description' => '',
            'type' =>'email',
            'required'=>'required'
          ),
          'user_url' => array(
            'label'       =>$json['WEBSITE'],
            'icon' => '',
            'description' => ''
          ),
          'pass1' => array(
            'label'       =>$json['PASSWORD'],
            'icon' => '',
            'description' => $wp_user_login_password_valid_message,
            'type' =>'password'
          ),
          'pass2' => array(
            'label'       =>$json['RETYPE_PASSWORD'],
            'description' => '',
            'icon' => '',
            'type' =>'password'
          ),
          'description' => array(
            'label'       =>$json['BIOLOGICAL_INFORMATION'],
            'description' => '',
            'icon' => '',
            'type' =>'textarea'
          )          
        )
      );

$wp_user_profile_field_filter=apply_filters( 'wp_user_profile_field_filter', $wp_user_profile_field) ;

  echo ' <div class="row">';
  echo '<div class="col-md-12">
   <div style="display: none;" id="wp_user_profile_div" class="alert alert-dismissible fade in" role="alert"><label id="wp_user_profile_label"></label>
   <button id="wp_user_profile_div_close" class="close" type="button">
  <span aria-hidden="true">&times;</span>
</button>
   </div>
  <form  id="wp_user_profile_field_form" name="wp_user_profile_field_form" method="post" action="">';
   echo '<div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">
        <div class="panel panel-default">
          <div class="panel-heading" role="tab" id="headingcollapse_avatar">
            <label class="panel-title">
              <a role="button" data-toggle="collapse" data-parent="#accordion" href="#collapse_avatar" aria-expanded="true" aria-controls="collapseOne">
              User Avatar
              </a>
            </label>
          </div>
          <div id="collapse_avatar" class="panel-collapse collapse in" role="tabpanel" aria-labelledby="headingcollapse_avatar">
                    <div class="panel-body">
                    
                    <div class="input-group input-group-sm">
  <input type="text" name="user_meta_image" id="user_meta_image" value="'.get_user_meta( get_current_user_id(),'user_meta_image',true).'" class="form-control" />               
                    <span class="input-group-btn">
                      <input type="button" class="additional-user-image btn btn-info btn-flat" value="Upload Image" id="uploadimage"/>
                    </span>
              </div>
                <span class="profile_description">Upload an image or enter url for your user profile.</span></div>
            </div>
          </div>
              </div>';
                $script_update=''; 
    foreach ($wp_user_profile_field_filter as $key => $array) {
     
        echo '<div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">
        <div class="panel panel-default">
          <div class="panel-heading" role="tab" id="headingOne">
            <label class="panel-title">
              <a role="button" data-toggle="collapse" data-parent="#accordion" href="#collapse'.$key.'" aria-expanded="true" aria-controls="collapseOne">';
         echo $array['title'];
         echo '</a>
            </label>
          </div>
          <div id="collapse'.$key.'" class="panel-collapse collapse in" role="tabpanel" aria-labelledby="headingOne">
                    <div class="panel-body">
                    ';

          foreach ($array['fields'] as $key => $value) {
              echo '<div class="form-group  has-feedback">
                    <label for="'.$key.'">'.$value['label'].'</label>';
            
                if(($value['type']=='select')){
                echo '<select class="form-control" id="'.$key.'"  name="'.$key.'">'; 
                       foreach ($value['options'] as $optionKey => $optionValue) {
                        $selected= (get_user_meta( get_current_user_id(),$key,true)==$optionKey) ? 'selected' : '';
                        echo '<option id="'.$optionKey.'" '.$selected.' value="'.$optionKey.'">'.$optionValue.'</option>';
                     }
                 echo '</select>';

               }else{
                $fieldType=(!empty($value['type'])) ? $value['type'] : 'text';
                 $required=(!empty($value['required'])) ? $value['required'] : '';
                $textValue=($fieldType!='password') ? get_the_author_meta($key,get_current_user_id()) : '';
                $script_update .='$(".'.$key.'").html(parsed.'.$key.');';
                echo '<input type="'.$fieldType.'" class="form-control" id="'.$key.'" placeholder="'.$value['label'].'" name="'.$key.'" value="'.$textValue.'" '.$required.'>'; 
                if(!get_option('wp_user_appearance_icon') && !empty($value['icon'])){
                    echo '<span class="'.$value['icon'].' form-control-feedback"></span>';
                }               
               }  

           echo (!empty($value['description'])) ? '<p>'.$value['description'].'</p>' : '';
            echo '</div>';
          }
       echo '</div>
            </div>
          </div>
              </div>';
  }
   if ( class_exists( 'WPSubscription' )  && get_option('wp_subscription_wp_user_register_list_id_enable')=='1') {
     $checked='';  
     $email=get_the_author_meta('user_email',get_current_user_id());
      $list_id=get_option('wp_subscription_wp_user_register_list_id');
      if(!empty($list_id) && !empty($email)){  
                  $is_active=isset($WP_USER_INPUT['wpuser_subscribe']) ? 1 : 0;

               $subscribers = $wpdb->get_results( "SELECT email FROM {$wpdb->prefix}wpsp_subscribers WHERE email LIKE '$email' AND is_active=1 AND list_id=$list_id" );                           
                             if($wpdb->num_rows > 0){
                                 $checked='checked';                                 
                             }
     }
    echo ' <div class="form-group  has-feedback">
       <input name="wpuser_subscribe" '.$checked.' type="checkbox" > Join our e-mail list
       </div>';
 }

    //wp_nonce_field('update-user');       
     echo ' <input name="wpuser_action" type="hidden" value="update_wp_user">
     <input name="wpuser_update_setting" type="hidden" value="'.wp_create_nonce("wpuser-update-setting").'"/> <input type="submit" id="wp_user_profile_field_submit" class="btn btn-primary" name="wpuser_login"  value="Save">';
     echo '</form></div>';
     echo '</div>';
    echo '<script>
    var $ = jQuery.noConflict();

    $(function() {
      $("#wp_user_profile_field_form").validate({
          rules: {          
              user_email: {required: true}         
          },
          submitHandler: function(form) {
              $.ajax({
                type: "POST",
                url: "'.admin_url("admin-ajax.php").'?action=wpuser_profile",
                data: $(form).serialize(),          
                error: function(data) {     
          },     
          success: function(data) {
            var parsed = $.parseJSON(data);
             $("#wp_user_profile_label").html(parsed.message);
            $("#wp_user_profile_div").removeClass().addClass("alert alert-dismissible alert-"+parsed.status);
             $("#wp_user_profile_div").show(); 
              $("#pass1").val("");
              $("#pass2").val("");
              $(".wp_user_profile_img").attr("src",parsed.wp_user_profile_img);
              '.$script_update.'


                  
          }
              });
              return false;
          }
      });
    });';

    echo '$("#wp_user_profile_div_close").click(function(){
       $("#wp_user_profile_div").hide(); 
    });
 $(function() {
    var file_frame;
 
  $(".additional-user-image").on("click", function( event ){
 
    event.preventDefault();
 
    // If the media frame already exists, reopen it.
    if ( file_frame ) {
      file_frame.open();
      return;
    }
 
    // Create the media frame.
    file_frame = wp.media.frames.file_frame = wp.media({
      title: $( this ).data( "uploader_title" ),
      button: {
        text: $( this ).data( "uploader_button_text" ),
      },
        multiple: false
    });
 
    // When an image is selected, run a callback.
    file_frame.on( "select", function() {
      // We set multiple to false so only get one image from the uploader
      attachment = file_frame.state().get("selection").first().toJSON();
      $("#user_meta_image").val(attachment.url);
      $("#user_meta_image_attachment_id").val(attachment.id);
      
 
      // Do something with attachment.id and/or attachment.url here
    });
 
    // Finally, open the modal
    file_frame.open();
  });
 
});
    </script>';        
}

static function address (){
    if ( class_exists( 'WC_Admin_Profile' ) ) {
   echo ' 
   <div style="display: none;" id="wp_user_address_div" class="alert alert-dismissible fade in" role="alert"><label id="wp_user_address_label"></label>
                        <button id="wp_user_address_div_close" class="close" type="button">
                          <span aria-hidden="true">&times;</span>
                      </button>
                         </div>
                          <form  id="wp_user_address_field_form" class="" name="wp_user_address_field_form" method="post" action="">
                          <div class="row">';
      $array =  WC_Admin_Profile::get_customer_meta_fields();
      foreach ($array as $array) {
        echo '<div class="col-md-6">';
         echo '<div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">
        <div class="panel panel-default">
          <div class="panel-heading" role="tab" id="headingOne">
            <label class="panel-title">
              <a role="button" data-toggle="collapse" data-parent="#accordion" href="#collapse'.$key.'" aria-expanded="true" aria-controls="collapseOne">';
         echo $array['title'];
         echo '</a>
            </label>
          </div>
          <div id="collapse'.$key.'" class="panel-collapse collapse in" role="tabpanel" aria-labelledby="headingOne">
            <div class="panel-body">';
        foreach ($array['fields'] as $key => $value) {
          echo '<div class="form-group"> <label>'.$value['label'].'</label>';
          if(empty($value['type'])){
          echo '<input type="text" class="form-control" id="'.$key.'" placeholder="'.$value['label'].'" name="'.$key.'" value="'.get_user_meta( get_current_user_id(),$key,true).'">'; 
        }
         if(($value['type']=='select')){
          echo '<select class="form-control" id="'.$key.'"  name="'.$key.'">'; 
                 foreach ($value['options'] as $optionKey => $optionValue) {
                  $selected= (get_user_meta( get_current_user_id(),$key,true)==$optionKey) ? 'selected' : '';
                  echo '<option id="'.$optionKey.'" '.$selected.' value="'.$optionKey.'">'.$optionValue.'</option>';
          }
          echo '</select>';

         }
         echo '<p>'.$value['description'].'</p>';
        echo '</div>';
        }
        echo '</div>
          </div>
        </div>
                </div>';
        echo '</div>';
      }
      echo '</div>
      <input name="wpuser_action" type="hidden" value="address_wp_user">
        <input name="wpuser_update_setting" type="hidden" value="'.wp_create_nonce("wpuser-update-setting").'"/> <input type="submit" id="wp_user_address_field_submit" class="btn btn-primary" name="wpuser_address" value="Save">      
      </form>';

       echo '<script>
    var $ = jQuery.noConflict();

    $(function() {
      $("#wp_user_address_field_form").validate({
          rules: {          
                   
          },
          submitHandler: function(form) {
              $.ajax({
                type: "POST",
                url: "'.admin_url("admin-ajax.php").'?action=wpuser_address",
                data: $(form).serialize(),          
                error: function(data) {     
          },     
          success: function(data) {
            var parsed = $.parseJSON(data);
             $("#wp_user_address_label").html(parsed.message);
            $("#wp_user_address_div").removeClass().addClass("alert alert-dismissible alert-"+parsed.status);
             $("#wp_user_address_div").show(); 
              $("#pass1").val("");
              $("#pass2").val("");

                  
          }
              });
              return false;
          }
      });
    });';

    echo '$("#wp_user_address_div_close").click(function(){
       $("#wp_user_address_div").hide(); 
    });
    </script>';    
   }
}

static function contact_us (){
          echo '<div style="display: none;" id="wp_user_contact_div" class="alert alert-dismissible fade in" role="alert"><label id="wp_user_contact_label"></label>
                        <button id="wp_user_contact_div_close" class="close" type="button">
                          <span aria-hidden="true">&times;</span>
                      </button>
                         </div>
          <form  id="wp_user_profile_contact_form" class="form-horizontal" name="wp_user_profile_contact_form" method="post" action="">
                  <div class="form-group">                  

                    <div class="col-sm-12">
                      <input type="text" class="form-control" id="wp_user_email_subject" name="wp_user_email_subject" placeholder="Subject" required>
                    </div>
                  </div>

                  <div class="form-group">
                    <div class="col-sm-12">
                      <textarea placeholder="Mail Content" id="wp_user_email_content" name="wp_user_email_content" required> </textarea>
                    </div>
                    </div>
             
                  <div class="form-group">
                    <div class="col-sm-offset-10 col-sm-2">
                     <input name="wpuser_action" type="hidden" value="contact_wp_user">
        <input name="wpuser_update_setting" type="hidden" value="'.wp_create_nonce("wpuser-update-setting").'"/> <input type="submit" id="wp_user_profile_field_submit" class="btn btn-primary" name="wpuser_login"  value="Send">
                    </div>
                  </div>
                </form>';

      echo '<script>
                          var $ = jQuery.noConflict();
                          $(function() {
                            $("#wp_user_profile_contact_form").validate({
                                rules: {          
                                    wp_user_email_subject: {required: true},
                                    wp_user_email_content : {required: true}        
                                },
                                submitHandler: function(form) {
                                    $.ajax({
                                      type: "POST",
                                      url: "'.admin_url("admin-ajax.php").'?action=wpuser_contact",
                                      data: $(form).serialize(),          
                                      error: function(data) {     
                                },     
                                success: function(data) {
                                  var parsed = $.parseJSON(data);
                                   $("#wp_user_contact_label").html(parsed.message);
                                  $("#wp_user_contact_div").removeClass().addClass("alert alert-dismissible alert-"+parsed.status);
                                   $("#wp_user_contact_div").show();                                   
                                        
                                }
                                    });
                                    return false;
                                }
                            });
                          });';

                          echo '$("#wp_user_contact_div_close").click(function(){
                             $("#wp_user_contact_div").hide(); 
                          });
                    </script>';

  }

  static function support (){
    echo do_shortcode('[wp_support_plus]');
  }
}