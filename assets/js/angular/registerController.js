
listApp.controller('registerController', function ($scope, $http, apiUrl,wpuserLang,translationService,vcRecaptchaService) {
    //i18n
    $scope.selectedLanguage = wpuserLang;
     $scope.publicKey = "6LelaR8TAAAAAPVbmKrULdQcPdBkMIY8QuTG_ub4";
    //Run translation if selected language changes
    $scope.translate = function(){
       translationService.getTranslation($scope, $scope.selectedLanguage);
   };
    $scope.translate();
    $scope.get_setting_register = function () {

        $http.get(apiUrl + "?action=wpuser_getSettingRegister").success(function (data)
        {

            $scope.wp_user_tern_and_condition = data['wp_user_tern_and_condition'];
            $scope.wp_user_show_term_data = data['wp_user_show_term_data'];
            $scope.wp_user_appearance_icon = data['wp_user_appearance_icon'];
            $scope.wp_user_security_reCaptcha_enable = data['wp_user_security_reCaptcha_enable'];
            if (data['wp_user_tern_and_condition'] == 1) {
                $scope.wp_user_show_term_required = 'required';
            } else {
                $scope.wp_user_show_term_required = '';
            }
        });
    }
    
   
    $scope.wpuser_register = function () {
       //  var wp_user_vcRecaptchaService='';
        if($scope.wp_user_security_reCaptcha_enable==1 && vcRecaptchaService.getResponse() === ""){ //if string is empty
             $scope.register_message ='Please resolve the captcha and submit!';
             $scope.register_status = 'warning';
           //  var wp_user_vcRecaptchaService=vcRecaptchaService.getResponse();
				//alert("Please resolve the captcha and submit!")
	}else{           

        $http.post(apiUrl + '?action=wpuser_register_action',
                {
                    'wp_user_email_name': $scope.wp_user_email_name,
                    'wp_user_password': $scope.wp_user_password,
                    'wp_user_re_password': $scope.wp_user_re_password,
                    'wp_user_email': $scope.wp_user_email,
                    'wp_user_term_condition': $scope.wp_user_term_condition,
                    'wp_user_Recaptcha': vcRecaptchaService.getResponse()



                }
        )
                .success(function (data, status, headers, config) {
                    $scope.register_message = data['message'];
                    $scope.register_status = data['status'];
                    if (data['status'] == 'success') {
                        $scope.wp_user_email_name = '';
                        $scope.wp_user_password = '';
                        $scope.wp_user_re_password = '';
                        $scope.wp_user_email = '';
                         
                        
                    }


                })
                .error(function (data, status, headers, config) {

                });
    }
}
});
