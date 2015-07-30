'use strict';
baccaratControllers.controller('unsubscribeController',
        ['$translate',
            '$scope',
            '$location',
            '$cookieStore',
            '$stateParams',
            'countries',
            'contacts',
            'unsubscribeInitialData',
            function ($translate, $scope, $location, $cookieStore, $stateParams, countries, contacts, unsubscribeInitialData) {
                $scope.currentCountry;
                $scope.currentLanguage;
                $scope.contact;
                
                $scope.showKOModal = false;
                unsubscribeInitialData.getData().then(function (data) {
                    $scope.countries = data.countries;

                    jQuery.each($scope.countries, function (index, item) {
                        if (item.country_desc === data.countries.country_desc) {
                            $scope.currentCountry = $scope.countries[index];
                        }
                    });
                    getLanguagesCallback(data);
                    
                    $scope.contact = data.contact;
                    console.log(data.contact);
                    if ($scope.contact==null){
                        $scope.showKOModal = true;

                        return;
                    }
                });

                $scope.changeCountry = function () {
                    countries.getLanguagesByCountryId({'country_id': $scope.currentCountry.country_id}, function (data) {
                        getLanguagesCallback(data);
                    });
                }

                $scope.changeLanguage = function () {
                    //var langKey = $scope.currentLanguage.lang_iso2 + '-' + $scope.currentCountry.country_iso2;
                    var langKey = $scope.currentLanguage.lang_iso2;
                    $translate.use(langKey);
                }

                $scope.saveContact = function () {
                    
                    
                    $scope.contact.unsubscribeCampaign = $stateParams.campaignId;
                    $scope.contact.unsubscribePackage = $stateParams.package;
                    contacts.save({'contact_id': $scope.contact.contactID}, $scope.contact, function (data) {
                        var unsubscribed = ($scope.contact.privacyOptions.exitCommunity == 1);
                        
                        if (unsubscribed){
                            $cookieStore.put('unsuscribed', unsubscribed);
                            $cookieStore.put('contactID', $scope.contact.contactID);
                            $location.path('survey');
                        } else {
                            $location.path('unsubscribe_landing');
                        }
                       
                    });
                }


                //Callbacks
                function getLanguagesCallback(data) {
                    $scope.languages = data.languages;
                    console.log(data.languages);
                    //if no languages--> english
                    var langKey = 'en'; 
                    if ($scope.languages.length != 0) {
                        jQuery.each($scope.languages, function (index, item) {
                            if (item.is_default === '1') {
                                $scope.currentLanguage = $scope.languages[index];
                                langKey = $scope.currentLanguage.lang_iso2;
                            }
                        });
                        //langKey = $scope.currentLanguage.lang_iso2 + '-' + $scope.currentCountry.country_iso2;
                        //   DIMA langKey = $scope.currentLanguage.lang_iso2;
                    }
                    $translate.use(langKey);
                    console.log('langKey',langKey);
                    $cookieStore.put('langKey', langKey);
                }
            }
        ]
        );


