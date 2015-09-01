'use strict';
baccaratControllers.controller('profileController',
        ['$translate',
            '$scope',
            '$location',
            '$cookieStore',
            '$stateParams',
            'countries',
            'stateprovinces',
            'contacts',
            'profileInitialData',
            function ($translate, $scope, $location, $cookieStore, $stateParams, countries, stateprovinces, contacts, profileInitialData) {
                $scope.currentCountry;
                $scope.currentLanguage;

                $scope.countries = [];
                $scope.nationalities = [];
                $scope.stateprovinces = [];
                $scope.cities = [];
                $scope.languages = [];
                $scope.contact = {};

                $scope.showKOModal = false;
                profileInitialData.getData().then(function (data) {
                    $scope.countries = data.countries;
                    $scope.contact = data.contact;

                    jQuery.each($scope.countries, function (index, item) {
                        //console.log(item.country_desc , data.contact.countryDesc);
                        if (item.country_desc === data.contact.countryDesc) {
                            $scope.contact.country = $scope.countries[index];
                        }
                    });
                    //SOLO PER DEBUG
                    $scope.contact.stateProvinceDesc = 'MI';//SOLO PER DEBUG
                    //$scope.contact.country = $scope.countries[110];
                    $scope.changeCountry();

                    // OLD (DELETE) getLanguagesCallback(data);


                    //console.log($scope.contact);
                    $scope.languages = data.languages;
                    console.log(data.languages);
                    console.log($scope.contact.preferredLanguage);
                    
                    /* set default language */
                    jQuery.each($scope.languages, function (index, item) {
                        if (item.language_desc === $scope.contact.preferredLanguageDesc) {  
                            $scope.contact.preferredLanguage = $scope.languages[index];
                        }
                    });

                    if ($scope.contact === null) {
                        $scope.showKOModal = true;

                        return;
                    }
                });

                $scope.changeCountry = function () {
                    countries.getStateProvincesByCountryId({'country_id': $scope.contact.country.country_id}, function (data) {
                        $scope.stateprovinces = data.stateprovinces;
                        jQuery.each($scope.stateprovinces, function (index, item) {
                            if (item.stateprovince_desc === $scope.contact.stateProvinceDesc) {
                                $scope.contact.stateprovince = $scope.stateprovinces[index];
                            }
                        });
                        //$scope.contact.stateprovince = $scope.stateprovinces[0];
                        $scope.changeStateProvince();
                    })
                }

                $scope.changeStateProvince = function () {
                    stateprovinces.getCitiesByStateProvinceId({'stateprovince_id': $scope.contact.stateprovince.stateprovince_id}, function (data) {
                        $scope.cities = data.cities;
                        jQuery.each($scope.cities, function (index, item) {
                            if (item.city_desc === $scope.contact.townCityDesc) {
                                $scope.contact.city = $scope.cities[index];
                            }
                        });
                        //$scope.contact.city = $scope.cities[0];
                    })
                }

                $scope.saveContact = function () {
                    //$scope.contact.unsubscribeCampaign = $stateParams.campaignId;
                    contacts.save({'contact_id': $scope.contact.contactID}, $scope.contact, function (data) {
                        //$cookieStore.put('unsuscribed', unsubscribed);
                        //$cookieStore.put('contactID', $scope.contact.contactID);
                        //$location.path('survey');                     
                    });
                }

            }
        ]
        );


