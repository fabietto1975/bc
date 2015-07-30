'use strict';
baccaratControllers.controller('searchController',
        ['$translate',
            '$scope',
            '$location',
            '$cookieStore',
            'searchInitialData',
            'contacts',
            function ($translate, $scope, $location, $cookieStore, searchInitialData, contacts) {
                
                
                $scope.contactData = {
                    'lastname' : '',
                    'firstname' : '',
                    'email': ''
                };
                $scope.searchResult = {};
                
                searchInitialData.getData().then(
                    function(data){
                        $scope.countries = data.countries;
                        $scope.nationalities = data.countries;
                    }
                );
                $scope.searchContact = function () { 
                    var params = '';
                    jQuery.each($scope.contactData, function (index, item) {
                        if(item !== ''){
                            params = params+index+':'+item+';';
                        }
                    });
                    contacts.searchByName({'q':params}, function (data) {
                        $scope.searchResults = data.contacts;  
                    });
                }
            }
        ]
        );







 