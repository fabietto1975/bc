'use strict';

var baccaratServices = angular.module('baccaratServices', ['ngResource']);

baccaratServices.factory('contacts',
        ['$resource', 'base_url',
            function ($resource,base_url) {
                return $resource(base_url+'rest/contacts/:contact_id',
                    {contact_id: '@contact_id'},
                    {
                        getByEncodedId: {method: 'GET', params: {encoded: 'y'}, headers: {'Content-Type': 'application/json'}},
                        searchByName: {method: 'GET', params: {mode: 'staff'}, headers: {'Content-Type': 'application/json'}}   
                    }
                );
            }]
        );


baccaratServices.factory('countries',
    ['$resource', 'base_url',
        function ($resource, base_url) {
            return $resource(base_url + 'rest/countries/:country_id/:target',
                {country_id: '@country_id', target: '@target'},
                {
                    getStateProvincesByCountryId: {method: 'GET', params: {target: 'stateprovinces'}, headers: {'Content-Type': 'application/json'}},
                    getLanguagesByCountryId: {method: 'GET', params: {target: 'languages'}, headers: {'Content-Type': 'application/json'}}}
            );
    }]
);


baccaratServices.factory('stateprovinces',
    ['$resource', 'base_url',
        function ($resource, base_url) {
            return $resource(base_url + 'rest/stateprovinces/:stateprovince_id/:target',
                {stateprovince_id: '@stateprovince_id', target: '@target'},
                {getCitiesByStateProvinceId: {method: 'GET', params: {target: 'cities'}, headers: {'Content-Type': 'application/json'}}});
    }]
);


baccaratServices.factory('languages',
    ['$resource', 'base_url',
        function ($resource,base_url) {
            return $resource(base_url+'rest/languages/:language_id',
                {language_id: '@language_id'},
                {getActiveLanguages: {method: 'GET', params: {active: '1'}, headers: {'Content-Type': 'application/json'}}});
    }]
);

baccaratServices.factory('stores',
    ['$resource', 'base_url',
        function ($resource,base_url) {
            return $resource(base_url+'rest/stores/:store_id',
                {store_id: '@store_id'});
    }]
);

baccaratServices.factory('profileInitialData',
    ['countries', 'contacts', 'languages', '$q', '$stateParams',
        function (countries, contacts, languages, $q, $stateParams) {
            return {
                getData: function () {

                    var delay = $q.defer();
                    var calls = [];
                    
                    console.log($stateParams);

                    calls.push(countries.get().$promise);
                    calls.push(countries.get({'country_id':$stateParams.countryCode}).$promise);
                    calls.push(contacts.getByEncodedId({'contact_id':$stateParams.contactId}).$promise);
                    //calls.push(countries.getLanguagesByCountryId({'country_id':$stateParams.countryCode}).$promise);
                    calls.push(languages.getActiveLanguages().$promise);

                    $q.all(calls).then(
                        function (results) {
                            delay.resolve({
                                'countries'     : results[0].countries,
                                'country'       : results[1].country,
                                'contact'       : results[2].consumer, 
                                'languages'     : results[3].languages  
                            })
                        },
                        function (errors) {
                            delay.reject(errors);
                        },
                        function (updates) {
                            delay.update(updates);
                    });

                    return delay.promise;
                }
            }
}]);

baccaratServices.factory('subscribeInitialData',
    ['countries', 'languages', '$q', '$routeParams',
        function (countries, languages, $q, $routeParams) {
            return {
                getData: function () {

                    var delay = $q.defer();
                    var calls = [];

                    calls.push(countries.get().$promise);
                    calls.push(countries.get({'country_id':$routeParams.countryCode}).$promise);
                    calls.push(languages.getActiveLanguages().$promise);

                    $q.all(calls).then(
                        function (results) {
                            delay.resolve({
                                'countries'     : results[0].countries,
                                'country'       : results[1].country,
                                'languages'     : results[2].languages
                            })
                        },
                        function (errors) {
                            delay.reject(errors);
                        },
                        function (updates) {
                            delay.update(updates);
                    });

                    return delay.promise;
                }
            }
}]);



baccaratServices.factory('searchInitialData',
    ['countries', '$q', 
        function (countries, $q) {
            return {
                getData: function () {

                    var delay = $q.defer();
                    var calls = [];

                    calls.push(countries.get().$promise);

                    $q.all(calls).then(
                        function (results) {
                            delay.resolve({
                                'countries'     : results[0].countries
                            })
                        },
                        function (errors) {
                            delay.reject(errors);
                        },
                        function (updates) {
                            delay.update(updates);
                    });

                    return delay.promise;
                }
            }
}]);



  
baccaratServices.factory('AuthenticationService',
    ['Base64', '$http', '$cookieStore', '$rootScope', '$timeout', 'base_url',
    function (Base64, $http, $cookieStore, $rootScope, $timeout, base_url) {
        var service = {};
 
        service.Login = function (username, password, callback) {
 
            /* Dummy authentication for testing, uses $timeout to simulate api call
             ----------------------------------------------*/
            /*
            $timeout(function(){
                var response = { success: username === 'admin' && password === 'admin', loggedUser: 'Admin Admin' };
                if(!response.success) {
                    response.message = 'Username or password is incorrect';
                }
                callback(response);
            }, 1000);
            */
 
            /* Use this for real authentication
             ----------------------------------------------*/
            $http.post(base_url+'/rest/login', { username: username, password: password })
                .success(function (response) {
                    callback(response);
            });
 
        };
  
        service.SetCredentials = function (username, password, loggedUser) {
            var authdata = Base64.encode(username + ':' + password);
  
            $rootScope.globals = {
                currentUser: {
                    username: username,
                    authdata: authdata,
                    loggedUser: loggedUser
                }
            };
  
            $http.defaults.headers.common['Authorization'] = 'Basic ' + authdata; // jshint ignore:line
            $cookieStore.put('globals', $rootScope.globals);
        };
  
        service.ClearCredentials = function () {
            $rootScope.globals = {};
            $cookieStore.remove('globals');
            $http.defaults.headers.common.Authorization = 'Basic ';
        };
  
        return service;
    }]);
  
baccaratServices.factory('Base64', function () {
    /* jshint ignore:start */
  
    var keyStr = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789+/=';
  
    return {
        encode: function (input) {
            var output = "";
            var chr1, chr2, chr3 = "";
            var enc1, enc2, enc3, enc4 = "";
            var i = 0;
  
            do {
                chr1 = input.charCodeAt(i++);
                chr2 = input.charCodeAt(i++);
                chr3 = input.charCodeAt(i++);
  
                enc1 = chr1 >> 2;
                enc2 = ((chr1 & 3) << 4) | (chr2 >> 4);
                enc3 = ((chr2 & 15) << 2) | (chr3 >> 6);
                enc4 = chr3 & 63;
  
                if (isNaN(chr2)) {
                    enc3 = enc4 = 64;
                } else if (isNaN(chr3)) {
                    enc4 = 64;
                }
  
                output = output +
                    keyStr.charAt(enc1) +
                    keyStr.charAt(enc2) +
                    keyStr.charAt(enc3) +
                    keyStr.charAt(enc4);
                chr1 = chr2 = chr3 = "";
                enc1 = enc2 = enc3 = enc4 = "";
            } while (i < input.length);
  
            return output;
        },
  
        decode: function (input) {
            var output = "";
            var chr1, chr2, chr3 = "";
            var enc1, enc2, enc3, enc4 = "";
            var i = 0;
  
            // remove all characters that are not A-Z, a-z, 0-9, +, /, or =
            var base64test = /[^A-Za-z0-9\+\/\=]/g;
            if (base64test.exec(input)) {
                window.alert("There were invalid base64 characters in the input text.\n" +
                    "Valid base64 characters are A-Z, a-z, 0-9, '+', '/',and '='\n" +
                    "Expect errors in decoding.");
            }
            input = input.replace(/[^A-Za-z0-9\+\/\=]/g, "");
  
            do {
                enc1 = keyStr.indexOf(input.charAt(i++));
                enc2 = keyStr.indexOf(input.charAt(i++));
                enc3 = keyStr.indexOf(input.charAt(i++));
                enc4 = keyStr.indexOf(input.charAt(i++));
  
                chr1 = (enc1 << 2) | (enc2 >> 4);
                chr2 = ((enc2 & 15) << 4) | (enc3 >> 2);
                chr3 = ((enc3 & 3) << 6) | enc4;
  
                output = output + String.fromCharCode(chr1);
  
                if (enc3 != 64) {
                    output = output + String.fromCharCode(chr2);
                }
                if (enc4 != 64) {
                    output = output + String.fromCharCode(chr3);
                }
  
                chr1 = chr2 = chr3 = "";
                enc1 = enc2 = enc3 = enc4 = "";
  
            } while (i < input.length);
  
            return output;
        }
    };
    /* jshint ignore:end */
});