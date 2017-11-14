
listApp.controller('loginController', function ($scope, $http, apiUrl,wpuserLang, translationService ,login_redirect) {
    //i18n
    $scope.selectedLanguage = wpuserLang;
    //Run translation if selected language changes
    $scope.translate = function(){
       translationService.getTranslation($scope, $scope.selectedLanguage);
   };
    $scope.translate();
    
    $scope.get_setting_login = function () {

        $http.get(apiUrl + "?action=wpuser_getSettingLogin").success(function (data)
        {

            $scope.wp_user_disable_signup = data['wp_user_disable_signup'];
            $scope.wp_user_appearance_icon = data['wp_user_appearance_icon'];           
            
        });
    }
    
   
  
  

    $scope.wpuser_login = function () {

        $http.post(apiUrl + '?action=wpuser_login_action',
                {
                    
                    'wp_user_email_name': $scope.wp_user_email_name,                    
                    'wp_user_password': $scope.wp_user_password      
                   


                }
        )
                .success(function (data, status, headers, config) { 
                     $scope.login_message=data['message'];
                     $scope.login_status=data['status'];
                     $scope.wp_user_disable_signup=data['wp_user_disable_signup'];
                     if(data['status']=='success'){
                     window.location=login_redirect;
                 }
                })
                .error(function (data, status, headers, config) {
                    
                });
    }
});

