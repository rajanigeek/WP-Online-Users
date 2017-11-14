<script>
var $ = jQuery.noConflict();

$("#wpuser_register<?php echo $form_id?>").click(function(){
  <?php if(get_option('wp_user_security_reCaptcha_enable') && !empty(get_option('wp_user_security_reCaptcha_secretkey'))){ ?> 

      if(grecaptcha.getResponse() == '') {
                    $('#wpuser_error_register<?php echo $form_id ?>').html("Please verify Captcah");$('#wpuser_errordiv_register<?php echo $form_id ?>').removeClass().addClass('alert alert-dismissible alert-warning');
                     $('#wpuser_errordiv_register<?php echo $form_id ?>').show();
                   return false;
               } 
      <?php } ?>
   $.ajax({
      url: '<?php echo admin_url('admin-ajax.php')?>?action=wpuser_register_action',
      data: $("#google_form<?php echo $form_id ?>").serialize(),
       error: function(data) {     
      },     
      success: function(data) {
        var parsed = $.parseJSON(data);
        $('#wpuser_error_register<?php echo $form_id ?>').html(parsed.message);
        $('#wpuser_errordiv_register<?php echo $form_id ?>').removeClass().addClass('alert alert-dismissible alert-'+parsed.status);
        if(parsed.status=='success') {
          $("#google_form<?php echo $form_id ?>")[0].reset(); 
        }   
        $('#wpuser_errordiv_register<?php echo $form_id ?>').show();
      },
      type: 'POST'
   });
});

$("#wpuser_login<?php echo $form_id?>").click(function(){     
   $.ajax({
      url: '<?php echo admin_url('admin-ajax.php')?>?action=wpuser_login_action',
      data: $("#wpuser_login_form<?php echo $form_id ?>").serialize(),
       error: function(data) {        
      },     
      success: function(data) {
        var parsed = $.parseJSON(data);
        $('#upuser_error<?php echo $form_id ?>').html(parsed.message);
        $('#wpuser_errordiv<?php echo $form_id ?>').removeClass().addClass('alert alert-dismissible alert-'+parsed.status);        
        $('#wpuser_errordiv<?php echo $form_id ?>').show();
        if(parsed.status=='success') { 
           $("#wpuser_login_form<?php echo $form_id ?>")[0].reset();   
           <?php if(empty($login_redirect)){
           echo 'location.reload();';
         }else{
          echo "window.location.href =' $login_redirect '";
         }?>

         }
      },
      type: 'POST'
   });
});

$("#wpuser_forgot<?php echo $form_id?>").click(function(){       
   $.ajax({
      url: '<?php echo admin_url('admin-ajax.php')?>?action=wpuser_forgot_action',
      data:  $("#wpuser_forgot_form<?php echo $form_id ?>").serialize(),
       error: function(data) {   
      },     
      success: function(data) {
        var parsed = $.parseJSON(data);
        $('#upuser_error_forgot<?php echo $form_id ?>').html(parsed.message);
        $('#wpuser_errordiv_forgot<?php echo $form_id ?>').removeClass().addClass('alert alert-dismissible alert-'+parsed.status);
        if(parsed.status=='success') { 
         $("#wpuser_forgot_form<?php echo $form_id ?>")[0].reset();   
       }
        $('#wpuser_errordiv_forgot<?php echo $form_id ?>').show();
      },
      type: 'POST'
   });
});
</script>