'use strict';
baccaratControllers.controller('surveyController',
        ['$translate',
            '$scope',
            '$location',
            '$cookieStore',
            'contacts',
            function ($translate, $scope, $location, $cookieStore, contacts) {
                var langKey = $cookieStore.get('langKey');
                
                $scope.showContent = false;
                $scope.preferencesSaved = false;
                
                $translate.use(langKey);
                $cookieStore.remove('langKey');

                $scope.contact = {};

                $scope.unsubscribe = $cookieStore.get('unsuscribed');
                $cookieStore.remove('unsuscribed');

                $scope.contactID = $cookieStore.get('contactID');
                $cookieStore.remove('contactID');
                contacts.get({'contact_id': $scope.contactID}, function (data) {

                    $scope.contact = data.contact;
                    if ($scope.unsubscribe) {
                        $scope.contact.privacyOptions['exitCommunity'] = "1";
                    }
                    $scope.showContent = true;
                });

                $scope.saveSurvey = function () {
                    
                    contacts.save({'contact_id': $scope.contactID}, $scope.contact, function (data) {
                        $scope.preferencesSaved = true;
                        $cookieStore.put('langKey', langKey);
                        $location.path("survey_landing");
                    });
                }
            }
        ]
        );







 