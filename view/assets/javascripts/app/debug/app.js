'use strict';
var app = angular.module('dieselCPapp', ['ngRoute', 'ngResource', 'ngCookies', 'pascalprecht.translate', 'cpControllers', 'cpServices',  'ui.bootstrap']);

app.config(['$translateProvider', '$resourceProvider',function ($translateProvider, $resourceProvider) {
        $resourceProvider.defaults.stripTrailingSlashes = true;
        $translateProvider.useStaticFilesLoader({
            prefix: 'resource/messages-',
            suffix: '.json'
        });
        $translateProvider.preferredLanguage('en');
        $translateProvider.fallbackLanguage('en');
    }]);


app.config(['$routeProvider',
    function ($routeProvider) {
        $routeProvider.
                when('/unsubscribe', {
                    templateUrl: 'partials/unsubscribe.html',
                    controller: 'unsubscribeController'
                }).
                when('/unsubscribe_landing', {
                    templateUrl: 'partials/unsubscribe_landing.html',
                    controller: 'unsubscribeLandingController'
                }).
                when('/subscribe', {
                    templateUrl: 'partials/subscribe.html',
                    controller: 'subscribeController'
                }). 
                when('/survey', {
                    templateUrl: 'partials/survey.html',
                    controller: 'surveyController'
                }). 
                when('/survey_landing', {
                    templateUrl: 'partials/survey_landing.html',
                    controller: 'surveyLandingController'
                }).        
                otherwise({ 
                    redirectTo: '/unsubscribe'
                });
               
    }]);



