var $ = jQuery.noConflict();
$("#wpuser_register"+wpuser_var.form_id).click(function(){
  if(wpuser_var.wp_user_security_reCaptcha_enable){
      if(grecaptcha.getResponse() == '') {
                    $('#wpuser_error_register'+wpuser_var.form_id).html("Please verify Captcah");
                    $('#wpuser_errordiv_register'+wpuser_var.form_id).removeClass().addClass('alert alert-dismissible alert-warning');
                     $('#wpuser_errordiv_register'+wpuser_var.form_id).show();
                   return false;
               } 
      }
   $.ajax({
      url: wpuser_var.wpuser_ajax_url+'?action=wpuser_register_action',
      data: $("#google_form"+wpuser_var.form_id).serialize(),
       error: function(data) {     
      },     
      success: function(data) {
        var parsed = $.parseJSON(data);
        $('#wpuser_error_register'+wpuser_var.form_id).html(parsed.message);
        $('#wpuser_errordiv_register'+wpuser_var.form_id).removeClass().addClass('alert alert-dismissible alert-'+parsed.status);
        if(parsed.status=='success') {
          $("#google_form"+wpuser_var.form_id)[0].reset(); 
        }   
        $('#wpuser_errordiv_register'+wpuser_var.form_id).show();
      },
      type: 'POST'
   });
});

$("#wpuser_login"+wpuser_var.form_id).click(function(){     
   $.ajax({
      url: wpuser_var.wpuser_ajax_url+'?action=wpuser_login_action',
      data: $("#wpuser_login_form"+wpuser_var.form_id).serialize(),
       error: function(data) {        
      },     
      success: function(data) {
        var parsed = $.parseJSON(data);
        $('#upuser_error'+wpuser_var.form_id).html(parsed.message);
        $('#wpuser_errordiv'+wpuser_var.form_id).removeClass().addClass('alert alert-dismissible alert-'+parsed.status); 
        $("#wpuser_login_form"+wpuser_var.form_id)[0].reset();   
        $('#wpuser_errordiv'+wpuser_var.form_id).show();
        if(parsed.status=='success') { 
           location.reload();
         }
      },
      type: 'POST'
   });
});

$("#wpuser_forgot"+wpuser_var.form_id).click(function(){       
   $.ajax({
      url: wpuser_var.wpuser_ajax_url+'?action=wpuser_forgot_action',
      data:  $("#wpuser_forgot_form"+wpuser_var.form_id).serialize(),
       error: function(data) {   
      },     
      success: function(data) {
        var parsed = $.parseJSON(data);
        $('#upuser_error_forgot'+wpuser_var.form_id).html(parsed.message);
        $('#wpuser_errordiv_forgot'+wpuser_var.form_id).removeClass().addClass('alert alert-dismissible alert-'+parsed.status);
        if(parsed.status=='success') { 
         $("#wpuser_forgot_form"+wpuser_var.form_id)[0].reset();   
       }
        $('#wpuser_errordiv_forgot'+wpuser_var.form_id).show();
      },
      type: 'POST'
   });
});