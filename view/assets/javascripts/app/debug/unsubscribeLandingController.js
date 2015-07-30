'use strict';
cpControllers.controller('unsubscribeLandingController',
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