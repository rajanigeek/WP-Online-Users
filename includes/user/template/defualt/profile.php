<?php
/* Allow users to update their profiles from Frontend.
 */
/* Get user info. */
global $current_user, $wp_roles;
//get_currentuserinfo(); //deprecated since 3.1

/* Load the registration file. */
//require_once( ABSPATH . WPINC . '/registration.php' ); //deprecated since 3.1
$error = array();
/* If profile was saved, update profile. */
if ('POST' == $_SERVER['REQUEST_METHOD'] && !empty($_POST['action']) && $_POST['action'] == 'update-user') {

     if (!isset($_POST['wpuser_update_setting']))
        die("<br><span class='label label-danger'>Invalid form data. form request came from the somewhere else not current site! </span>");
    if (!wp_verify_nonce($_POST['wpuser_update_setting'], 'wpuser-update-setting'))
        die("<br><span class='label label-danger'>Invalid form data. form request came from the somewhere else not current site! </span>");

    /* Update user password. */
    if (!empty($_POST['pass1']) && !empty($_POST['pass2'])) {
        if ($_POST['pass1'] == $_POST['pass2'])
            wp_update_user(array('ID' => $current_user->ID, 'user_pass' => sanitize_text_field($_POST['pass1'])));
        else
            $error[] = __('The passwords you entered do not match.  Your password was not updated.', 'profile');
    }
		
    /* Update user information. */
    if (!empty($_POST['url']))
        wp_update_user(array('ID' => $current_user->ID, 'user_url' => esc_url($_POST['url'])));
    if (!empty($_POST['email'])) {
        if (!is_email(sanitize_text_field($_POST['email'])))
            $error[] = __('The Email you entered is not valid.  please try again.', 'profile');
        elseif (email_exists(sanitize_text_field($_POST['email'])) != $current_user->ID)
            $error[] = __('This email is already used by another user.  try a different one.', 'profile');
	     }
        else {
            wp_update_user(array('ID' => $current_user->ID, 'user_email' => sanitize_text_field($_POST['email'])));
        }
    

    if (!empty($_POST['first-name']))
        update_user_meta($current_user->ID, 'first_name', sanitize_text_field($_POST['first-name']));
    if (!empty($_POST['last-name']))
        update_user_meta($current_user->ID, 'last_name', sanitize_text_field($_POST['last-name']));
    if (!empty($_POST['description']))
        update_user_meta($current_user->ID, 'description', sanitize_text_field($_POST['description']));

    /* Redirect so the page will show updated info. */
    /* I am not Author of this Code- i dont know why but it worked for me after changing below line to if ( count($error) == 0 ){ */
    if (count($error) == 0) {
        //action hook for plugins and extra fields saving
        do_action('edit_user_profile_update', $current_user->ID);
        wp_redirect(get_permalink());
        exit;
    }
}

echo '<div class="box box-widget widget-user">
                <!-- Add the bg color to the header using any of the bg-* classes -->
                <div class="widget-user-header bg-aqua-active">
                  <h3 class="widget-user-username">' . $current_user->user_login . '</h3>
                  <h5 class="widget-user-desc">' . $current_user->display_name . '</h5>
                </div>
                <div class="widget-user-image">
                  <img class="img-circle" src="' . WPUSER_PLUGIN_URL . 'assets/images/wpuser.png" alt="User Avatar">
                </div>
                <div class="box-footer">
                  <div class="row">
                    <div class="col-sm-4 border-right">
                      <div class="description-block">
                        <h5 class="description-header"><a href="#viewprofile" data-toggle="tab" aria-expanded="false">View</a></h5>                        
                      </div><!-- /.description-block -->
                    </div><!-- /.col -->
                    <div class="col-sm-4 border-right">
                      <div class="description-block">
                        <h5 class="description-header"><a href="#editprofile" data-toggle="tab" aria-expanded="false">Edit</a></h5>                        
                      </div><!-- /.description-block -->
                    </div><!-- /.col -->
                    <div class="col-sm-4">
                      <div class="description-block">
                        <h5 class="description-header"><a href="' . wp_logout_url(get_permalink()) . '" title="Logout">Logout</a></h5>
                      </div><!-- /.description-block -->
                    </div><!-- /.col -->
                  </div><!-- /.row -->
                </div>
              ';
echo' <div class="tab-content">'
 . '<div class="tab-pane active" id="viewprofile">';
echo '<ul class="list-group list-group-unbordered">
           <li class="list-group-item">
                      <b>Name:</b>' . $current_user->user_firstname . ' ' . $current_user->user_lastname . '
                    </li>
                    <li class="list-group-item">
                      <b>Username: </b>' . $current_user->user_login . '
                    </li>
                    <li class="list-group-item">
                      <b>Email:</b>' . $current_user->user_email . '
                    </li>
                    <li class="list-group-item">
                      <b>Display name:</b>' . $current_user->display_name . '
                    </li>
                  </ul>';
echo'</div>';
echo'<div class="tab-pane" id="editprofile">';
?> <?php if (count($error) > 0) echo '<p class="error">' . implode("<br />", $error) . '</p>'; ?>
<form method="post" id="adduser" action="<?php the_permalink(); ?>">
    <p class="form-username">
        <label for="first-name"><?php _e('First Name', 'profile'); ?></label>
        <input class="form-control" name="first-name" type="text" id="first-name" value="<?php the_author_meta('first_name', $current_user->ID); ?>" />
    </p><!-- .form-username -->
    <p class="form-username">
        <label for="last-name"><?php _e('Last Name', 'profile'); ?></label>
        <input class="form-control" name="last-name" type="text" id="last-name" value="<?php the_author_meta('last_name', $current_user->ID); ?>" />
    </p><!-- .form-username -->
    <p class="form-email">
        <label for="email"><?php _e('E-mail *', 'profile'); ?></label>
        <input class="form-control" name="email" type="text" id="email" value="<?php the_author_meta('user_email', $current_user->ID); ?>" />
    </p><!-- .form-email -->
    <p class="form-url">
        <label for="url"><?php _e('Website', 'profile'); ?></label>
        <input class="form-control" name="url" type="text" id="url" value="<?php the_author_meta('user_url', $current_user->ID); ?>" />
    </p><!-- .form-url -->
    <p class="form-password">
        <label for="pass1"><?php _e('Password *', 'profile'); ?> </label>
        <input class="form-control" name="pass1" type="password" id="pass1" />
    </p><!-- .form-password -->
    <p class="form-password">
        <label for="pass2"><?php _e('Repeat Password *', 'profile'); ?></label>
        <input class="form-control" name="pass2" type="password" id="pass2" />
    </p><!-- .form-password -->
    <p class="form-textarea">
        <label for="description"><?php _e('Biographical Information', 'profile') ?></label>
        <textarea name="description" id="description" rows="3" cols="50"><?php the_author_meta('description', $current_user->ID); ?></textarea>
    </p><!-- .form-textarea -->

    <?php
    //action hook for plugin and extra fields
    do_action('edit_user_profile', $current_user);
    ?>
    <p class="form-submit">
        <input name="wpuser_update_setting" type="hidden" value="<?php echo wp_create_nonce('wpuser-update-setting'); ?>" />
        <input name="updateuser" type="submit" id="updateuser" class="submit button" value="<?php _e('Update', 'profile'); ?>" />
        <?php wp_nonce_field('update-user') ?>
        <input name="action" type="hidden" id="action" value="update-user" />
    </p><!-- .form-submit -->
</form><!-- #adduser -->
<?php
echo'</div></div>';
echo'</div>';
