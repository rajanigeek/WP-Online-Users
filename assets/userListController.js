    var listApp = angular.module('listpp',['ngRoute','ngResource','vcRecaptcha','ui.bootstrap']);    
    listApp.constant('apiUrl', wpuser_link.wpuser_ajax_url);  
    listApp.constant('wpuserLang', wpuser_link.wpuser_lang);  
     listApp.constant('login_redirect', wpuser_link.login_redirect);  
    listApp.filter('startFrom', function() {
    return function(input, start) {
        if(input) {
            start = +start; //parse to int
            return input.slice(start);
        }
        return [];
    }
    });
    
    listApp.service('translationService', function($resource) {  

        this.getTranslation = function($scope, language) {
            var languageFilePath = wpuser_link.wpuser_user_i18n+'/' + language + '.json';
            console.log(languageFilePath);
            $resource(languageFilePath).get(function (data) {
                $scope.translation = data;
            });
        };
    });
    
    
    listApp .config(function($routeProvider) {
    $routeProvider.      
       when('/userListcontroller', {
        templateUrl: wpuser_link.wpuser_user_templateUrl+'templates/userListView.html',
        controller: 'userListcontroller'
      }).  
      otherwise({
         templateUrl: wpuser_link.wpuser_user_templateUrl+'templates/userListView.html',
        controller: 'userListcontroller'
      });
  });
  
  
   
