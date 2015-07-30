'use strict';
var app = angular.module('baccaratAPP', ['ui.bootstrap', 'ngResource', 'uiRouterStyles', 'ngRoute', 'ngCookies', 'pascalprecht.translate', 'ui.router', 'baccaratControllers', 'baccaratServices']);

app.config(['$translateProvider', '$resourceProvider', function ($translateProvider, $resourceProvider) {
        $resourceProvider.defaults.stripTrailingSlashes = true;
        $translateProvider.useStaticFilesLoader({
            prefix: 'resource/messages-',
            suffix: '.json'
        });
        $translateProvider.preferredLanguage('en');
        $translateProvider.fallbackLanguage('en');
    }]);

/*
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
 */


app.config(function ($stateProvider) {

    $stateProvider
            .state('storeSelection', {
                url: "/storeSelection",
                resolve : {
                    initialData : function ($q,stores) {
                        var storeData = stores.get();
                        return $q.all([storeData.$promise]);
                        
                        
                    }
                },
                views: {
                    'pageBody': {
                        controller: 'storeSelectionController',
                        templateUrl: 'partials/storeSelection.html',
                    },
                    'header': {
                        templateUrl: "partials/headerNotLogged.html"
                    },
                    'footer': {
                        templateUrl: "partials/footer.html"
                    }

                }
            })
            .state('subscribe', {
                url: "/subscribe",
                views: {
                    'pageBody': {
                        controller: 'subscribeController',
                        templateUrl: 'partials/subscribe.html',
                    },
                    'header': {
                        templateUrl: "partials/header.html"
                    },
                    'footer': {
                        templateUrl: "partials/footer.html"
                    }
                }
            })
            .state('profile', {
                url: "/profile?contactId&countryCode",
                views: {
                    'pageBody': {
                        controller: 'profileController',
                        templateUrl: 'partials/profile.html',
                    },
                    'header': {
                        templateUrl: "partials/header.html"
                    },
                    'footer': {
                        templateUrl: "partials/footer.html"
                    }
                }

            })

            .state('search', {
                url: "/search",
                views: {
                    'pageBody': {
                        controller: 'searchController',
                        templateUrl: 'partials/search.html',
                    },
                    'header': {
                        templateUrl: "partials/header.html"
                    },
                    'footer': {
                        templateUrl: "partials/footer.html"
                    }
                }

            })

});


app.run(['$rootScope', '$state', function ($rootScope, $state) {
        
        $rootScope.toSearch = function () {
            $state.go('search');
        }
        $rootScope.toHome = function () {
            $state.go('subscribe'); //TBD
        }
        $rootScope.toSubscribe = function () {
            $state.go('subscribe');
        }
        $rootScope.toProfile = function () {
            $state.go('profile');
        }

    }]);








