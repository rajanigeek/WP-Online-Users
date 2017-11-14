<?php
/* Allow users to update their profiles from Frontend.
 */
 wp_enqueue_style('jquery');
 wp_enqueue_style('wpsp_bootstrap', WPUSER_PLUGIN_URL . 'assets/css/bootstrap.min.css');
 wp_enqueue_style('wpdbadminltecss', WPUSER_PLUGIN_URL . "assets/dist/css/AdminLTE.css");
 wp_enqueue_script('wpdbbootstrap', WPUSER_PLUGIN_URL . "assets/bootstrap/js/bootstrap.min.js");
 wp_enqueue_script('wpdbbootstrap');
 wp_enqueue_script('wpdbvalidate', WPUSER_PLUGIN_URL . "assets/js/jquery.validate.min.js");
 wp_enqueue_script('wpdbvalidate');
 wp_deregister_style('wpce_bootstrap');        
 wp_enqueue_media();
global $current_user, $wp_roles;
            $wp_user_profile['my_account']=array(
                  'class'=>'WPUserProfile',
                  'function'=>'my_account',
                  'tab'=>'My Acount',
                  'icon'=>'glyphicon glyphicon-dashboard',
                  'order'=>'0',
                  'parent'=>'0'
                  );
            $wp_user_profile['edit_profile']=array(
                  'class'=>'WPUserProfile',
                  'function'=>'edit_profile',
                  'tab'=>'Edit Profile',
                  'icon'=>'glyphicon glyphicon-edit',
                  'order'=>'0',
                  'parent'=>'0'
                  );
            if ( in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ) { 
              $wp_user_profile['address']=array(
                    'class'=>'WPUserProfile',
                    'function'=>'address',
                    'tab'=>'Address',
                    'icon'=>'glyphicon glyphicon-map-marker',
                    'order'=>'0',
                    'parent'=>'0'
                    );
            }

           if (class_exists( 'WPSupportPlus' )) { 
            $wp_user_profile['support']=array(
                  'class'=>'WPUserProfile',
                  'function'=>'support',
                  'tab'=>'Support',
                  'icon'=>'glyphicon glyphicon-tasks',
                  'order'=>'0',
                  'parent'=>'0'
                  );
          }

            $wp_user_profile['contact_us']=array(
                  'class'=>'WPUserProfile',
                  'function'=>'contact_us',
                  'tab'=>'Contact Us Form',
                  'icon'=>'glyphicon glyphicon-envelope',
                  'order'=>'0',
                  'parent'=>'0'
                  );
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

echo '<div class="row">
             <div class="col-md-3">
          <!-- Profile Image -->
          <div class="box box-primary">
            <div class="box-body box-profile">
            <img class="profile-user-img img-responsive img-circle wp_user_profile_img" id="wp_user_profile_img" src="'.$wp_user_profile_img .'" alt="User Avatar"><h3 class="profile-username text-center"><span class="first_name" >'.$current_user->user_firstname . '</span> <span class="last_name" >' . $current_user->user_lastname . '</span></h3>            
           <p class="text-muted text-center">' . $current_user->user_login .'</p><a href="' . wp_logout_url(get_permalink()) . '" class="btn btn-default btn-block" title="Logout">'.$json['LOGOUT'].'</a>
           <br>
              <ul class="list-group list-group-unbordered nav-tabs-custom">';

        foreach ($wp_user_profile as $tab=>$user_profile ) {        
            echo ' <a class="list-group-item" href="#'.$tab.'" data-toggle="tab" aria-expanded="true"> <i class="'.$user_profile['icon'].'"> </i>  '.$user_profile['tab'].'</a>';       
      }

      echo '</ul>
               </div>
          </div>
          </div>

          <div class="col-md-9">
          <div class="nav-tabs-custom">           
            <div class="tab-content">';

             foreach ($wp_user_profile as $tab=>$user_profile ) {   
                   $active_class=($tab=='my_account') ? 'active' : ''; 
                  echo '<div class="tab-pane '.$active_class.'" id="'.$tab.'">
                     <h3>'.$user_profile['tab'].'</h3>';
                         $user_profile['class']::$user_profile['function']();
                  echo'</div>';  
             }
      echo '</div>
         </div>
        </div>';        