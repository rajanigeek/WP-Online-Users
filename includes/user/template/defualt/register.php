<?php
if (isset($_POST['reg_submit'])) {

    if (!isset($_POST['wpuser_update_setting']))
        die("<br><span class='label label-danger'>Invalid form data. form request came from the somewhere else not current site! </span>");
    if (!wp_verify_nonce($_POST['wpuser_update_setting'], 'wpuser-update-setting'))
        die("<br><span class='label label-danger'>Invalid form data. form request came from the somewhere else not current site! </span>");

    $registration_status = "";
    $login_status = "";
     if(isset($_POST['g-recaptcha-response']))
          $captcha=$_POST['g-recaptcha-response'];
	
    if (isset($_POST['registration_username'])) {
        $username = sanitize_text_field($_POST['registration_username']);
    } else {
        $username = "";
    }
    if (isset($_POST['registration_password'])) {
        $password = sanitize_text_field($_POST['registration_password']);
    } else {
        $password = "";
    }
	
    $email = sanitize_text_field($_POST['registration_email']);
	
	
    if(!empty($captcha) || get_option('wp_user_recaptch_sitekey')=='')
	{	
    	$register_user = wp_create_user($username, $password, $email); 
	wpuser_auto_login($username,true);
	wp_redirect(get_permalink()); exit; 
	}
	else
	$return.='<div class="alert alert-warning alert-dismissible fade in" role="alert"> <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button>
                                  Please select google reCAPTCHA 
                                 </div>';
	

    if ($register_user && !is_wp_error($register_user)) {

        $return.=$registration_status = '<div class="alert alert-success alert-dismissible fade in" role="alert"> <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button>Registration completed.</div>';
    } elseif (is_wp_error($register_user)) {
        $return.='<div class="alert alert-warning alert-dismissible fade in" role="alert"> <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button>
                                   ' . $registration_status = $register_user->get_error_message() . '</div>';
    }
}

$return.='
    <div class="tab-pane" id="settings">
    <div class="box box-info">
                <div class="box-header with-border">
                  <h3 class="box-title">Register</h3>                  
                </div><!-- /.box-header -->
                <!-- form start -->
		<script src="https://www.google.com/recaptcha/api.js"></script>
                <form class="form-horizontal" method="post" onsubmit="validate()" action="' . esc_url($_SERVER['REQUEST_URI']) . '">
                  <div class="box-body">
                  
                    <div class="form-group">
                      <label for="inputEmail3" class="col-sm-4 control-label">Username</label>
                      <div class="col-sm-8">
                        <input required class="form-control" type="text" name="registration_username" placeholder="Username" />
                      </div>
                    </div>
                    
<div class="form-group">
                      <label for="inputEmail3" class="col-sm-4 control-label">Password</label>
                      <div class="col-sm-8">
                      <input required class="form-control"  type="password" name="registration_password" placeholder="Password" />
                      </div>
                    </div>
                    
                   
<div class="form-group">
                      <label for="inputEmail3" class="col-sm-4 control-label">Email</label>
                      <div class="col-sm-8">
                        <input required class="form-control" type="email" name="registration_email" placeholder="Email" />
                   
                      </div>
                    </div>';
                  if(get_option('wp_user_recaptch_sitekey')!='')
			{	
			$return.=' <label for="inputEmail3" class="col-sm-4 control-label">reCAPTCHA</label>
		 <div class="col-sm-8">	
                   <div required id="recaptcha" class="g-recaptcha" data-sitekey="'.get_option('wp_user_recaptch_sitekey').'"></div>
			</div>';
			}
			
                  $return.='</div><!-- /.box-body -->
                  <div class="box-footer">         
                   <input name="wpuser_update_setting" type="hidden" value="'. wp_create_nonce('wpuser-update-setting').'" />
                    <input type="submit" onsubmit="validate()" class="btn" name="reg_submit" value="Sign Up" />
                    <a href="#activity" data-toggle="tab" aria-expanded="false" class="btn btn-default">Login</a>
                  </div><!-- /.box-footer -->
                </form>
              </div>
              </div>';
