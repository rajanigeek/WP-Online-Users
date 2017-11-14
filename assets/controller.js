    var listApp = angular.module('listpp', ['ngRoute','ngResource','ui.bootstrap']);    
     listApp.constant('apiUrl', wpuser_link.wpuser_ajax_url); 
      listApp.constant('wpuserLang', wpuser_link.wpuser_lang);       
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
    listApp .config(['$routeProvider',
  function($routeProvider) {
    $routeProvider.
      when('/loginController', {
        templateUrl: wpuser_link.wpuser_templateUrl+'templates/views-wpbdp-category.html',
        controller: 'loginController'
      }). 
         when('/registerController', {
        templateUrl: wpuser_link.wpuser_templateUrl+'templates/registerView.html',
        controller: 'registerController'
      }). 
      otherwise({
        templateUrl: wpuser_link.wpuser_templateUrl+'templates/settingView.html',
        controller: 'settingController'
      });
  }]);
  
  
   
