<?php

if (isset($_POST['wpuser_ogin_submit'])) {
    
     if (!isset($_POST['wpuser_update_setting']))
        die("<br><span class='label label-danger'>Invalid form data. form request came from the somewhere else not current site! </span>");
    if (!wp_verify_nonce($_POST['wpuser_update_setting'], 'wpuser-update-setting'))
        die("<br><span class='label label-danger'>Invalid form data. form request came from the somewhere else not current site! </span>");


    $creds = array();
    $creds['user_login'] = sanitize_text_field($_POST['login_username']);
    $creds['user_password'] = sanitize_text_field($_POST['login_password']);
    $creds['remember'] = sanitize_text_field($_POST['remember_login']);

    $login_user = @wp_signon($creds, false);
    if (!is_wp_error($login_user)) {
        @wp_redirect(get_permalink());
    } elseif (is_wp_error($login_user)) {
        $login_status = $login_user->get_error_message();
        $return.='<div class="alert alert-warning alert-dismissible fade in" role="alert"> <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button>
                                   Invalid user name or Password. <a href="#timeline" data-toggle="tab" aria-expanded="false">Lost your password?</a>
                                    </div>';
    }
}
$return.='
   <div class="tab-pane active" id="activity">   
    <div class="box box-info">
                <div class="box-header with-border">
                  <h3 class="box-title">Login</h3>
                  <div class="box-tools pull-right">
                  <a href="#timeline" data-toggle="tab" aria-expanded="false">Forgot your password?</a>
                  </div>
                </div><!-- /.box-header -->
                <!-- form start -->
                <form class="form-horizontal" method="post" action="' . esc_url($_SERVER['REQUEST_URI']) . '">
                  <div class="box-body">
                    <div class="form-group">
                      <label for="inputEmail3" class="col-sm-4 control-label">Username</label>
                      <div class="col-sm-8">
                        <input type="text" required name="login_username" placeholder="Username" class="form-control" id="inputEmail3">
                      </div>
                    </div>
                    <div class="form-group">
                      <label for="inputPassword3" class="col-sm-4 control-label">Password</label>
                      <div class="col-sm-8">
                        <input type="password" required class="form-control" id="inputPassword3" name="login_password" placeholder="Password">
                      </div>
                    </div>
                    <div class="form-group">
                      <div class="col-sm-offset-2 col-sm-8">
                        <div class="checkbox">
                          <label>
                            <input type="checkbox" name="remember_login" value="true" checked="checked"> Remember me
                          </label>
                        </div>
                      </div>
                    </div>
                  </div><!-- /.box-body -->
                  <div class="box-footer">     
                   <input name="wpuser_update_setting" type="hidden" value="'.wp_create_nonce('wpuser-update-setting').'" />
                    <input type="submit" class="btn" name="wpuser_ogin_submit" value="Sign in" />';
                   if(!empty($wp_user_disable_signup) && $wp_user_disable_signup==1)
                    $return.='<a href="#settings" data-toggle="tab" aria-expanded="false" class="btn btn-default">Register</a>';
                  $return.='</div><!-- /.box-footer -->
                </form>
              </div>
              </div>';
