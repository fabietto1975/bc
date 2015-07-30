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