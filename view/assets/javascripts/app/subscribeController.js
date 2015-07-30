'use strict';
baccaratControllers.controller('subscribeController',
        ['$translate',
            '$scope',
            '$state',
            '$location',
            '$cookieStore',
            'contacts',
            'countries',
            'stateprovinces',
            'subscribeInitialData',
            '$modal',
            function ($translate, $scope, $state, $location, $cookieStore, contacts, countries, stateprovinces, subscribeInitialData, $modal) {


                $scope.countries = [];
                $scope.nationalities = [];
                $scope.stateprovinces = [];
                $scope.cities = [];
                $scope.languages = [];
                $scope.contact = {
                    'title': '',
                    'contactMediaPref': '',
                    'agebracket': '',
                    'maritalstatus': ''
                };

                $scope.dateOptions = {
                    formatYear: 'yy',
                    startingDay: 1
                };

                subscribeInitialData.getData().then(function (data) {
                    //add an empty language
                    data.languages.unshift({"active": "1", "lang_iso2": "", "language_desc": "PREF_LANG_00", "language_id": "0", "region_id": "1"});
                    var nationalities = data.countries.slice();
                    nationalities.unshift({"country_desc": $translate.instant('NATIONALITY_00'), "country_id": "0", "country_iso2": "", "country_phone_code": ""});
                    data.countries.unshift({"country_desc": $translate.instant('COUNTRY_00'), "country_id": "1", "country_iso2": "", "country_phone_code": ""});
                    $scope.countries = data.countries;
                    $scope.languages = data.languages;
                    $scope.nationalities = nationalities;
                    $scope.contact.language = $scope.languages[0];
                    $scope.contact.country = $scope.countries[0];
                    $scope.contact.nationality = $scope.nationalities[0];
                    $scope.changeCountry();
                });


                $scope.changeCountry = function () {
                    countries.getStateProvincesByCountryId({'country_id': $scope.contact.country.country_id}, function (data) {
                        $scope.stateprovinces = data.stateprovinces;
                        $scope.contact.stateprovince = $scope.stateprovinces[0];
                        $scope.changeStateProvince();
                    })
                }

                $scope.changeStateProvince = function () {
                    stateprovinces.getCitiesByStateProvinceId({'stateprovince_id': $scope.contact.stateprovince.stateprovince_id}, function (data) {
                        $scope.cities = data.cities;
                        if ($scope.cities !== undefined) {
                            $scope.contact.city = $scope.cities[0];
                        }
                    })
                }

                $scope.addContact = function () {
                    contacts.save({}, $scope.contact, function (data) { 
                        if (data.status!='KO'){
                            var modalInstance = $modal.open({
                                templateUrl: 'registrationok.html',
                                controller: modalInstanceCtrl,
                                size: 'sm',
                            });
                        } else {
                            var modalInstance = $modal.open({
                                templateUrl: 'registrationko.html',
                                controller: modalInstanceCtrl,
                                size: 'sm',
                            });
                            
                        }
                        console.log(data);
                    });
                }

                var modalInstanceCtrl = function ($scope, $modalInstance) {
                    
                    $scope.close = function (res) {
                        $modalInstance.close(res);
                    };
                };
            }
        ]
        );


