
listApp.controller('userListcontroller', function ($scope, $http, apiUrl,wpuserLang, translationService ,login_redirect) {
    //i18n
    $scope.selectedLanguage = wpuserLang;

    $scope.filteredItems =  [];
    $scope.groupedItems  =  [];
    $scope.itemsPerPage  =  3;
    $scope.pagedItems    =  [];
    $scope.currentPage   =  0;

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

    $scope.viewList = true;

     //Run translation if selected language changes
    $scope.translate = function(){
       translationService.getTranslation($scope, $scope.selectedLanguage);
   };
    $scope.translate();
    
    $scope.get_user_list = function () {
        $scope.viewmember = false;
        $scope.viewList = true;  
        $http.get(apiUrl + "?action=wpuser_getMemberList").success(function (data)
        {

            $scope.pagedItems = data;    
            $scope.currentPage = 1; //current page
            $scope.entryLimit = 10; //max no of items to display in a page
            $scope.filteredItems = $scope.pagedItems.length; //Initially for no filter  
            $scope.totalItems = $scope.pagedItems.length;
            
        });
    }
    $scope.viewMember = function(index) {  
      $scope.viewmember = true;
      $scope.viewList = false;      
      
      $http.post(apiUrl+'?action=wpuser_viewMember', 
            {
                'id'     : index
            }
        )      
        .success(function (data, status, headers, config) { 
            $scope.id          =   data["id"];
            $scope.name        =   data["name"];
            $scope.labels       =   data["labels"];
            $scope.authors_posts =   data["authors_posts"];
            $scope.wp_user_profile_img=data["wp_user_profile_img"];
            $scope.description       =   data["description"];  
            $scope.currentPage = 1; //current page
            $scope.entryLimit = 10; //max no of items to display in a page
            $scope.filteredItems = $scope.authors_posts.length; //Initially for no filter  
            $scope.totalItems = $scope.authors_posts.length;          
        })
        .error(function(data, status, headers, config){           
        });
    }
});