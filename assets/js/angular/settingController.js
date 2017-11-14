
listApp.controller('settingController', function ($scope, $http, apiUrl,wpuserLang,translationService) {
    //i18n
    $scope.selectedLanguage = wpuserLang;
    $scope.filteredItems =  [];
    $scope.groupedItems  =  [];
    $scope.itemsPerPage  =  3;
    $scope.pagedItems    =  [];
    $scope.currentPage   =  0;

    /** Scope argument specify the function by name remove and passed index of list item as a parameter , which splice the list by 1 , as click on remove button **/
            
    $scope.remove = function (index) {
        $scope.phones.splice(index,1);
    }

    $scope.funding = { startingEstimate: 0 };

    $scope.computeNeeded = function() {           
        $scope.needed = $scope.funding.startingEstimate * 10;                
    };
    /** Check if value for funding is 0 or more **/

    $scope.requestFunding = function() {               
        if( $scope.needed == "" || !$scope.needed )
       // window.alert("Sorry, please get more category first.");
        toastr["error"]("Sorry, please get more category first.", "Error");
    };
    $scope.reset = function() {
        $scope.funding.startingEstimate = 0;
        $scope.needed = 0;
    };
    /** toggleMenu Function to show menu by toggle effect , by default the stage of the menu is false as we click on toggle button, we make it to true or show and reverse on anothe click event. **/

    $scope.menuState = false;
    $scope.add_prod = true;

    $scope.toggleMenu = function() {                
        if($scope.menuState) {                    
            $scope.menuState= false;
        }
        else {
            $scope.menuState= !$scope.menuState.show;
        }
    };

    //Run translation if selected language changes
    $scope.translate = function(){
       translationService.getTranslation($scope, $scope.selectedLanguage);
   };
    $scope.translate();

    $scope.setPage = function(pageNo) {
        $scope.currentPage = pageNo;
    };
    $scope.filter = function() {
        $timeout(function() { 
            $scope.filteredItems = $scope.filtered.length;
        }, 10);
    };
    $scope.sort_by = function(predicate) {
        $scope.predicate = predicate;
        $scope.reverse = !$scope.reverse;
    };

    $scope.get_setting_general = function () {

        $http.get(apiUrl + "?action=wpuser_getSetting").success(function (data)
        {

            $scope.wp_user_disable_signup = data['wp_user_disable_signup'];
            $scope.wp_user_disable_admin_bar = data['wp_user_disable_admin_bar'];
            


        });
    }

     $scope.get_login_log = function () {

        $http.get(apiUrl + "?action=wpuser_getLoginLog").success(function (data)
        {

            $scope.pagedItems = data;    
            $scope.currentPage = 1; //current page
            $scope.entryLimit = 10; //max no of items to display in a page
            $scope.filteredItems = $scope.pagedItems.length; //Initially for no filter  
            $scope.totalItems = $scope.pagedItems.length;



        });
    }

    $scope.update_setting_general = function () {

        $http.post(apiUrl + '?action=wpuser_updateSetting',
                {
                    'wp_user_disable_signup': $scope.wp_user_disable_signup,
                    'wp_user_disable_admin_bar': $scope.wp_user_disable_admin_bar,
                   


                }
        )
                .success(function (data, status, headers, config) {
                    // $scope.get_setting_general();
                    toastr["success"]("General setting has been Updated Successfully", "Success");
                    toastr.options = {
                        "closeButton": true,
                        "positionClass": "toast-top-center"
                    }

                })
                .error(function (data, status, headers, config) {
                    toastr["error"]("Sorry, Setting not updated", "Error");
                });
    }

    $scope.get_setting_general_term = function () {

        $http.get(apiUrl + "?action=wpuser_getSetting").success(function (data)
        {

            $scope.wp_user_show_term_data = data['wp_user_show_term_data'];
            $scope.wp_user_tern_and_condition = data['wp_user_tern_and_condition'];
        });
    }
    $scope.update_setting_general_term = function () {

        $http.post(apiUrl + '?action=wpuser_updateSetting',
                {
                    'wp_user_show_term_data': $scope.wp_user_show_term_data,
                    'wp_user_tern_and_condition': $scope.wp_user_tern_and_condition,
                }
        )
                .success(function (data, status, headers, config) {
                    // $scope.get_setting_general();
                    toastr["success"]("Term and Condition setting has been Updated Successfully", "Success");
                    toastr.options = {
                        "closeButton": true,
                        "positionClass": "toast-top-center"
                    }

                })
                .error(function (data, status, headers, config) {
                    toastr["error"]("Sorry, Setting not updated", "Error");
                });
    }
    
    $scope.get_setting_general_appearance = function () {

        $http.get(apiUrl + "?action=wpuser_getSetting").success(function (data)
        {

            $scope.wp_user_appearance_skin = data['wp_user_appearance_skin'];
            $scope.wp_user_appearance_icon = data['wp_user_appearance_icon'];
            $scope.wp_user_disable_admin_bar = data['wp_user_disable_admin_bar'];
            $scope.wp_user_appearance_custom_css = data['wp_user_appearance_custom_css'];
            $scope.wp_user_language = data['wp_user_language'];
            
        });
    }
    
     $scope.update_setting_general_appearance = function () {

        $http.post(apiUrl + '?action=wpuser_updateSetting',
                {
                    'wp_user_appearance_skin': $scope.wp_user_appearance_skin,
                    'wp_user_appearance_icon': $scope.wp_user_appearance_icon,
                    'wp_user_disable_admin_bar': $scope.wp_user_disable_admin_bar,
                    'wp_user_appearance_custom_css': $scope.wp_user_appearance_custom_css,
                    'wp_user_language': $scope.wp_user_language
                }
        )
                .success(function (data, status, headers, config) {
                    // $scope.get_setting_general();
                    toastr["success"]("Appearance setting has been Updated Successfully", "Success");
                    toastr.options = {
                        "closeButton": true,
                        "positionClass": "toast-top-center"
                    }

                })
                .error(function (data, status, headers, config) {
                    toastr["error"]("Sorry, Setting not updated", "Error");
                });
    }
    
     $scope.get_setting_page = function () {

        $http.get(apiUrl + "?action=wpuser_getSetting").success(function (data)
        {

            $scope.wp_user_page=data['wp_user_page'];
            $scope.wp_user_page_title=data['wp_user_page_title'];
            $scope.wp_user_member_page=data['wp_user_member_page'];
            $scope.wp_user_member_page_title=data['wp_user_member_page_title'];
            
            
        });
    }
    
     $scope.update_setting_page = function () {

        $http.post(apiUrl + '?action=wpuser_updatePageSetting',
                {
                    'wp_user_page_title': $scope.wp_user_page_title,
                    'wp_user_member_page_title': $scope.wp_user_member_page_title                          
                }
        )
                .success(function (data, status, headers, config) {
                    // $scope.get_setting_general();
                    $scope.wp_user_page=data['wp_user_page'];
                    $scope.wp_user_member_page=data['wp_user_member_page'];
                    toastr["success"]("Pages have been rebuilt successfully.", "Success");
                    toastr.options = {
                        "closeButton": true,
                        "positionClass": "toast-top-center"
                    }

                })
                .error(function (data, status, headers, config) {
                    toastr["error"]("Sorry, Setting not updated", "Error");
                });
    }

   $scope.get_setting_security_login = function () {

        $http.get(apiUrl + "?action=wpuser_getSetting").success(function (data)
        {

            $scope.wp_user_login_limit_enable = data['wp_user_login_limit_enable'];
            $scope.wp_user_login_limit = data['wp_user_login_limit'];
            $scope.wp_user_login_limit_admin_notify = data['wp_user_login_limit_admin_notify'];            
            $scope.wp_user_login_limit_time = data['wp_user_login_limit_time'];
            $scope.wp_user_truncate_login_entries = data['wp_user_truncate_login_entries'];
        });

    }
    
     $scope.update_setting_security_login = function () {

        $http.post(apiUrl + '?action=wpuser_updateSetting',
                {
                    'wp_user_login_limit_enable': $scope.wp_user_login_limit_enable,
                    'wp_user_login_limit': $scope.wp_user_login_limit,
                    'wp_user_login_limit_admin_notify': $scope.wp_user_login_limit_admin_notify,
                    'wp_user_login_limit_time': $scope.wp_user_login_limit_time,
                    'wp_user_truncate_login_entries':$scope.wp_user_truncate_login_entries
                    
                }
        )
                .success(function (data, status, headers, config) {
                    // $scope.get_setting_general();
                    toastr["success"]("Limit Login Attempts setting has been Updated Successfully", "Success");
                    toastr.options = {
                        "closeButton": true,
                        "positionClass": "toast-top-center"
                    }

                })
                .error(function (data, status, headers, config) {
                    toastr["error"]("Sorry, Setting not updated", "Error");
                });
    }
$scope.get_setting_security_password = function () {

        $http.get(apiUrl + "?action=wpuser_getSetting").success(function (data)
        {

            $scope.wp_user_login_limit_password_enable = data['wp_user_login_limit_password_enable'];
            $scope.wp_user_login_limit_password = data['wp_user_login_limit_password']; 
            $scope.wp_user_login_password_valid_message = data['wp_user_login_password_valid_message'];  
        });
    }

$scope.update_setting_security_password = function () {

        $http.post(apiUrl + '?action=wpuser_updateSetting',
                {
                    'wp_user_login_limit_password_enable': $scope.wp_user_login_limit_password_enable,
                    'wp_user_login_limit_password': $scope.wp_user_login_limit_password,
                    'wp_user_login_password_valid_message': $scope.wp_user_login_password_valid_message
                    
                    
                }
        )
                .success(function (data, status, headers, config) {
                    // $scope.get_setting_general();
                    toastr["success"]("Setting has been Updated Successfully", "Success");
                    toastr.options = {
                        "closeButton": true,
                        "positionClass": "toast-top-center"
                    }

                })
                .error(function (data, status, headers, config) {
                    toastr["error"]("Sorry, Setting not updated", "Error");
                });
    }
    
    $scope.get_setting_security_reCAPTCHA = function () {

        $http.get(apiUrl + "?action=wpuser_getSetting").success(function (data)
        {

            $scope.wp_user_security_reCaptcha_enable = data['wp_user_security_reCaptcha_enable'];
            $scope.wp_user_security_reCaptcha_secretkey = data['wp_user_security_reCaptcha_secretkey'];           
        });
    }

$scope.update_setting_security_reCAPTCHA = function () {

        $http.post(apiUrl + '?action=wpuser_updateSetting',
                {
                    'wp_user_security_reCaptcha_enable': $scope.wp_user_security_reCaptcha_enable,
                    'wp_user_security_reCaptcha_secretkey': $scope.wp_user_security_reCaptcha_secretkey
                    
                    
                }
        )
                .success(function (data, status, headers, config) {
                    // $scope.get_setting_general();
                    toastr["success"]("Setting has been Updated Successfully", "Success");
                    toastr.options = {
                        "closeButton": true,
                        "positionClass": "toast-top-center"
                    }

                })
                .error(function (data, status, headers, config) {
                    toastr["error"]("Sorry, Setting not updated", "Error");
                });
    }
    
    $scope.get_setting_email = function () {

        $http.get(apiUrl + "?action=wpuser_getSetting").success(function (data)
        {

            $scope.wp_user_email_name = data['wp_user_email_name'];
            $scope.wp_user_email_id = data['wp_user_email_id'];
             $scope.wp_user_email_admin_register_enable = data['wp_user_email_admin_register_enable'];
            $scope.wp_user_email_admin_register_subject = data['wp_user_email_admin_register_subject'];
             $scope.wp_user_email_admin_register_content = data['wp_user_email_admin_register_content'];
              $scope.wp_user_email_user_register_enable = data['wp_user_email_user_register_enable'];
            $scope.wp_user_email_user_register_subject = data['wp_user_email_user_register_subject'];
             $scope.wp_user_email_user_register_content = data['wp_user_email_user_register_content'];
              $scope.wp_user_email_user_forgot_subject = data['wp_user_email_user_forgot_subject'];
             $scope.wp_user_email_user_forgot_content = data['wp_user_email_user_forgot_content'];
            
        });
    }
    $scope.update_setting_email = function () {

        $http.post(apiUrl + '?action=wpuser_updateSetting',
                {
                    'wp_user_email_name': $scope.wp_user_email_name,
                    'wp_user_email_id': $scope.wp_user_email_id,
                    'wp_user_email_admin_register_enable': $scope.wp_user_email_admin_register_enable,
                    'wp_user_email_admin_register_subject': $scope.wp_user_email_admin_register_subject,
                     'wp_user_email_admin_register_content': $scope.wp_user_email_admin_register_content,
                     'wp_user_email_user_register_enable': $scope.wp_user_email_user_register_enable,
                    'wp_user_email_user_register_subject': $scope.wp_user_email_user_register_subject,
                     'wp_user_email_user_register_content': $scope.wp_user_email_user_register_content,   
                      'wp_user_email_user_forgot_subject': $scope.wp_user_email_user_forgot_subject,
                     'wp_user_email_user_forgot_content': $scope.wp_user_email_user_forgot_content   
                   

                }
        )
                .success(function (data, status, headers, config) {
                    // $scope.get_setting_general();
                    toastr["success"]("Email setting has been Updated Successfully", "Success");
                    toastr.options = {
                        "closeButton": true,
                        "positionClass": "toast-top-center"
                    }

                })
                .error(function (data, status, headers, config) {
                    toastr["error"]("Sorry, Setting not updated", "Error");
                });
    }
    


});
