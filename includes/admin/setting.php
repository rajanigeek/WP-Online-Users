<?php
if (!defined('ABSPATH'))
    exit; // Exit if accessed directly
if (isset($_POST['update'])) {
    if (!isset($_POST['wpuser_update_setting']))
        die("<br><span class='label label-danger'>Invalid form data. form request came from the somewhere else not current site! </span>");
    if (!wp_verify_nonce($_POST['wpuser_update_setting'], 'wpuser-update-setting'))
        die("<br><span class='label label-danger'>Invalid form data. form request came from the somewhere else not current site! </span>");

    if (isset($_POST['wp_user_disable_signup'])) {
        update_option('wp_user_disable_signup', '1');
    } else {
        update_option('wp_user_disable_signup', '0');
    }
	 if (isset($_POST['wp_user_recaptch_sitekey'])) {
        update_option('wp_user_recaptch_sitekey',$_POST['wp_user_recaptch_sitekey']);
    } else {
        update_option('wp_user_recaptch_sitekey', '');
    }

    if (isset($_POST['wp_user_disable_admin_bar'])) {
        update_option('wp_user_disable_admin_bar', '1');
    } else {
        update_option('wp_user_disable_admin_bar', '0');
    }
    ?>
    <div class="row">
        <div class="col-xs-12 col-sm-12 col-md-12">           
            <div class="alert alert-success alert-dismissible fade in" role="alert"> <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button>
                <?php _e('Setting Updated Successfully', 'wpuser'); ?>
            </div>
        </div>
    </div>
    <?php
}
?>

<div class="row">
    <div class="col-xs-12 col-sm-12 col-md-12"> 

        <!-- Single button Author-->
        <div class="btn-group">
            <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <span class="glyphicon glyphicon-user" aria-hidden="true"></span> <span class="caret"></span>
            </button>
            <ul class="dropdown-menu">
                <li class="list-group-item list-group-item-warning">
                    <a href="https://walkeprashant.wordpress.com/about-me/" target="_blank" >
                        <h5 ><?php _e('Plugin Author', 'wpuser'); ?></h5>
                        <p><?php _e('Prashant Walke', 'wpuser'); ?></p>
                    </a>
                </li>
                <li role="separator" class="divider"></li>
                <li >
                    <a href="http://www.wpseeds.com/wp-user/" target="_blank" >
                        <h5 ><?php _e('Plugin URL', 'wpuser'); ?></h5>
                    </a>
                </li>
                <li >
                    <a href="http://www.wpseeds.com/blog/category/update/wp-user/" target="_blank" >
                        <h5 ><?php _e('Change Log', 'wpuser'); ?> </h5>
                    </a>
                </li>
                <li >
                    <a href="http://www.wpseeds.com/wp-user/" target="_blank" >
                        <h5 ><?php _e('Documentation', 'wpuser'); ?></h5>
                    </a>
                </li>
                <li >
                    <a href="http://www.wpseeds.com/support/" target="_blank" >
                        <h5 ><?php _e('Support', 'wpuser'); ?></h5>
                    </a>
                </li>
                <li >
                    <a href="http://www.wpseeds.com/product/profile-pro/" target="_blank" >
                        <h5 ><?php _e('Pro Feature', 'wpuser'); ?></h5>
                    </a>
                </li>

            </ul>
        </div>

    </div>
</div>
<div class="panel panel-success">
    <div class="panel-heading">
        <div class="panel-title"><h3><span class="glyphicon glyphicon-cog" aria-hidden="true"></span> WP User Setting</h3></div>
    </div>
    <div class="panel-body">
        <form action="" method="post">
            <div class="col-xs-12 col-sm-12 col-md-12">
                <input type="checkbox" <?php checked(get_option('wp_user_disable_signup'), '1'); ?>  name="wp_user_disable_signup"> <?php _e('Enable Signup Form', 'wpuser'); ?>  
            </div>

		<hr>  <div class="col-xs-12 col-sm-12 col-md-12">
              <hr>   <?php _e('Recaptcha Site Key', 'wpuser'); ?>  <input type="text"  name="wp_user_recaptch_sitekey" value="<?php echo get_option('wp_user_recaptch_sitekey');?>" > 
            </div>	
            <div class="col-xs-12 col-sm-12 col-md-12"><hr>
                <input type="checkbox" <?php checked(get_option('wp_user_disable_admin_bar'), '1'); ?>  name="wp_user_disable_admin_bar"> <?php _e('Disable Admin Bar', 'wpuser'); ?>  
                <br>Disable WordPress Admin Bar for All Users Except Admin
        
        <hr>
                Use [wp_user] shortcode for display login, registration, forgot password form.
	            </div>

	


            <div class="col-xs-12 col-sm-12 col-md-12"><hr>
                <input type="submit" class="button-primary" name="update" value="<?php _e('Update', 'wpallbkp'); ?>">
            <hr></div>
            <input name="wpuser_update_setting" type="hidden" value="<?php echo wp_create_nonce('wpuser-update-setting'); ?>" />
        </form>
        
        <div class="col-xs-6 col-sm-6 col-md-6">
            <ul class="list-group">
                <li class="list-group-item list-group-item-success"><a href="http://www.wpseeds.com/membership-account/membership-levels/" target="_blank">Join Membership</a></li>
                <li class="list-group-item list-group-item-warning">Join <a href="http://www.wpseeds.com/membership-account/membership-levels/" target="_blank">WPSeeds Membership</a> to get complete access to all themes and plugins.</li>
                <li class="list-group-item list-group-item-warning"><b><a href="http://www.wpseeds.com/product/wp-all-backup/" target="_blank">WP All Backup</a></b> will backup and restore your entire site.</li>
                <li class="list-group-item list-group-item-warning"><b><a href="http://www.wpseeds.com/product/profile-pro/" target="_blank">Profile Pro</a></b> creating Sign up & Sign in forms,Profilepro comes with flexible User Registration and Login forms. Adding custom fields is really easy by using the unique Field Customizer tool.</li>
                <li class="list-group-item list-group-item-warning"><b><a href="http://www.wpseeds.com/product/wp_subscription/" target="_blank">WP Subscribe</a></b> is a simple but powerful subscription plugin which supports MailChimp, Aweber and Campaign Monitor.</li>
                <li class="list-group-item list-group-item-warning"><b><a href="http://www.wpseeds.com/product/popuppro/" target="_blank">Popuppro</a></b> plugin helps you to create easy popup for your website to attaract your visiter and also it help you to captuer visiter user email id for send him laterst news or link with amazing post on your blog,Offer.</li>
                <li class="list-group-item list-group-item-warning"><b><a href="http://www.wpseeds.com/product/wpunderconstruction/" target="_blank">WP Under Construction</a></b> theme allows you to connect with your visitors and sign them up to your mailing list so you can let them know when you are ready.</li>
                <li class="list-group-item list-group-item-warning">New plugin and theme releases....</li>
            </ul>
        </div>
        <div class="col-xs-6 col-sm-6 col-md-6">
            <ul>
                <li class="list-group-item list-group-item-success"><a target="_blank" href="http://www.wpseeds.com/product/profile-pro/">Get Profile Pro</a></li>
                <li class="list-group-item list-group-item-warning">Front-end user registration</li> 
                <li class="list-group-item list-group-item-warning">Front-end user login</li> 
                <li class="list-group-item list-group-item-warning">Front-end user profiles</li> 
                <li class="list-group-item list-group-item-warning">Member directories</li> 
                <li class="list-group-item list-group-item-warning">Custom form fields</li> 
                <li class="list-group-item list-group-item-warning">Social Login/Register</li> 
                <li class="list-group-item list-group-item-warning">Profile Background Image</li> 
                <li class="list-group-item list-group-item-warning">Approve/Deny User Registration</li> 
                <li class="list-group-item list-group-item-warning">Fully Optimized For Speed</li> 
                <li class="list-group-item list-group-item-warning">Register success message</li> 
                <li class="list-group-item list-group-item-warning">Responsive</li> 
                <li class="list-group-item list-group-item-warning">Drag and drop form builder</li> 
                <li class="list-group-item list-group-item-warning">And More....</li>
            </ul>
        </div>
    </div>
    <div class="panel-footer"><div role="alert" class="alert alert-success"><h4>Get Flat 25% off on <a target="_blank" href="http://www.wpseeds.com/product/profile-pro/">Profile Pro.</a> Use Coupon code 'WPSEEDS25'</h4></div></div>
</div>

