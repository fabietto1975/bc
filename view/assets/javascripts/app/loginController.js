'use strict';
baccaratControllers.controller('loginController',
    ['$scope', '$rootScope', '$location', 'AuthenticationService',
    function ($scope, $rootScope, $location, AuthenticationService) {
        // reset login status
        console.log('Login Controller');
        AuthenticationService.ClearCredentials();
  
        $scope.login = function () {
            $scope.dataLoading = true;
            AuthenticationService.Login($scope.username, $scope.password, function(response) {
                console.log(response);
                if(response.status === 'OK') {
                    console.log('Login OK');
                    AuthenticationService.SetCredentials($scope.username, $scope.password, response.res.loggeduser);
                    $location.path('/storeSelection');
                } else {
                    console.log('Wrong login');
                    $scope.error = response.error_message;
                    $scope.dataLoading = false;
                }
            });
        };
    }]);
 