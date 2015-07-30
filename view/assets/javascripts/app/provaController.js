'use strict';

app.factory('userRepository', function() {
    return {
        getAllUsers: function() {
            return [
                { country_desc: 'Jane', country_id: 'Doe', age: 29 },
                { country_desc: 'John', country_id: 'Doe', age: 32 }
            ];
        }
    };
});


app.controller('ProvaController', ['$scope','base_url',
    function ($scope, base_url) {

        $scope.country = baccaratServices.country({}, base_url);  

}]);

/*
app.controller('ProvaController', function($scope, userRepository) {
        $scope.country = userRepository.getAllUsers();
        $scope.test = "HHHHH";
        console.log($scope.country)

});
*/


