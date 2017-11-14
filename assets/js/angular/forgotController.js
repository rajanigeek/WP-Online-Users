
listApp.controller('forgotController', function ($scope, $http, apiUrl,wpuserLang,translationService) {
    
    //i18n
    $scope.selectedLanguage = wpuserLang;
    //Run translation if selected language changes
    $scope.translate = function(){
       translationService.getTranslation($scope, $scope.selectedLanguage);
   };
    $scope.translate();

    $scope.get_setting_forgor = function () {

        $http.get(apiUrl + "?action=wpuser_getSettingForgot").success(function (data)
        {

            $scope.wp_user_disable_signup = data['wp_user_disable_signup'];
            $scope.wp_user_appearance_icon = data['wp_user_appearance_icon'];
            
        });
    }
    $scope.forgot_password = function() {
        $http.post(apiUrl+'?action=wpuser_forgot_action', 
            {
                'wp_user_email'     : $scope.wp_user_email
            }
        )
        .success(function (data, status, headers, config) {
                    $scope.forgot_message=data['message'];
                     $scope.forgot_status=data['status'];
        })

        .error(function(data, status, headers, config){
           toastr["error"]("Sorry,Error", "Error");
        });

    }
});
