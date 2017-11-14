    <?php
    global $wpdb;
	
	$error = '';
	$success = '';
	
	// check if we're in reset form
	if( isset( $_POST['action'] ) && 'reset' == $_POST['action'] ) 
	{
             if (!isset($_POST['wpuser_update_setting']))
        die("<br><span class='label label-danger'>Invalid form data. form request came from the somewhere else not current site! </span>");
    if (!wp_verify_nonce($_POST['wpuser_update_setting'], 'wpuser-update-setting'))
        die("<br><span class='label label-danger'>Invalid form data. form request came from the somewhere else not current site! </span>");

		$email = sanitize_text_field($_POST['user_login']);
		
		if( empty( $email ) ) {
			$error = 'Enter a username or e-mail address..';
		} else if( ! is_email( $email )) {
			$error = 'Invalid username or e-mail address.';
		} else if( ! email_exists($email) ) {
			$error = 'There is no user registered with that email address.';
		} else {
		
			// lets generate our new password
			$random_password = wp_generate_password( 12, false );
			
			// Get user data by field and data, other field are ID, slug, slug and login
			$user = get_user_by( 'email', $email );
			
			$update_user = wp_update_user( array (
					'ID' => $user->ID, 
					'user_pass' => $random_password
				)
			);
			
			// if  update user return true then lets send user an email containing the new password
			if( $update_user ) {
				$to = $email;
				$subject = 'Your new password';
				$sender = get_option('name');
				 $site_url = site_url();
                                 $user_login=$user->user_login;
				include('template_email_forgot.php');				
				$headers[] = 'MIME-Version: 1.0' . "\r\n";
				$headers[] = 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
				$headers[] = "X-Mailer: PHP \r\n";
				$headers[] = 'From: '.$sender.' < '.$email.'>' . "\r\n";
				
				$mail = wp_mail( $to, $subject, $message, $headers );
				if( $mail )
					$success = 'Check your email address for your new password.';
					
			} else {
				$error = 'Oops something went wrong updaing your account.';
			}

		}
		
		if( ! empty( $error ) )
			$return.= '<div class="alert alert-warning alert-dismissible fade in" role="alert"> <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button><div class="error_login"><strong>ERROR:</strong> '. $error .'</div></div>';
		
		if( ! empty( $success ) )
			$return.= '<div class="alert alert-success alert-dismissible fade in" role="alert"> <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button><div class="updated"> '. $success .'</div></div>';
	}
    $return.='<div class="tab-pane" id="timeline">
    <div class="box box-info">
                <div class="box-header with-border">
                  <h3 class="box-title">Forgot Password</h3>                  
                </div><!-- /.box-header -->
                <!-- form start -->
                <form class="form-horizontal" method="post">
                    
                    
                  <div class="box-body">
                      <p>Please enter your email address. You will receive new password via email.</p>
			
                    <div class="form-group">
                      <label for="inputEmail3" class="col-sm-3 control-label">E-mail</label>
                      <div class="col-sm-9">';
                           $user_login = isset( $_POST['user_login'] ) ? $_POST['user_login'] : '';
				 $return.='<input type="text" class="form-control" id="inputEmail3" name="user_login" id="user_login" value="'.$user_login.'" /></p>
			
                    
                      </div>
                    </div>
                    
                    
                  </div><!-- /.box-body -->
                  <div class="box-footer"> 
                   <input name="wpuser_update_setting" type="hidden" value="'.wp_create_nonce("wpuser-update-setting").'" />
                      <input type="hidden" name="action" value="reset" />
		    <input type="submit" value="Get New Password" class="button" id="submit" />
                    
                    <a href="#activity" data-toggle="tab" aria-expanded="false" class="btn btn-default">Login</a>
                  </div><!-- /.box-footer -->
                </form>
              </div>
              </div>';
                    