'use strict';
baccaratControllers.controller('storeSelectionController',
        ['$translate',
            '$scope',
            '$location',
            '$cookieStore',
            'initialData',
            '$state',
            function ($translate, $scope, $location, $cookieStore , initialData, $state) {
                
                $scope.stores = initialData[0].stores;
                $scope.stores.unshift({"nom_tiers": $translate.instant('STORES_00'), "pdv_id": "0"});
                $scope.store = $scope.stores[0];
                
                $scope.onSelect = function(){
                    console.log($scope.store != $scope.stores[0]);
                    if ($scope.store != $scope.stores[0]){
                        $cookieStore.put('store',$scope.store)
                        $state.go('subscribe');
                        
                    }
                }
                
            }
        ]
        );


