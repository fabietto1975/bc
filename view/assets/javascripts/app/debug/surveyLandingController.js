'use strict';
cpControllers.controller('surveyLandingController',
        ['$translate',
            '$scope',
            '$location',
            '$cookieStore',
            'contacts',
            function ($translate, $scope, $location, $cookieStore, contacts) {
                $translate.use($cookieStore.get('langKey'));
                $cookieStore.remove('langKey');

            }
        ]
        );