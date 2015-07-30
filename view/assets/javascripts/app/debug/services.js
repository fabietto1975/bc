'use strict';

var cpServices = angular.module('cpServices', ['ngResource']);

cpServices.factory('contacts',
        ['$resource', 'base_url',
            function ($resource,base_url) {
                return $resource(base_url+'rest/contacts/:contact_id',
                        {contact_id: '@contact_id'},
                {getByEncodedId: {method: 'GET', params: {encoded: 'y'}, headers: {'Content-Type': 'application/json'}}});
            }]
        );

cpServices.factory('countries',
        ['$resource', 'base_url' ,
            function ($resource,base_url) {
                return $resource(base_url+'rest/countries/:country_id/:target',
                        {country_id: '@country_id', target: '@target'},
                {
                    getStateProvincesByCountryId: {method: 'GET', params: {target: 'stateprovinces'}, headers: {'Content-Type': 'application/json'}},
                    getLanguagesByCountryId: {method: 'GET', params: {target: 'languages'}, headers: {'Content-Type': 'application/json'}}}
                );
            }]
        );

cpServices.factory('stateprovinces',
        ['$resource', 'base_url' ,
            function ($resource,base_url) {
                return $resource(base_url+'rest/stateprovinces/:stateprovince_id/:target',
                        {stateprovince_id: '@stateprovince_id', target: '@target'},
                {getCitiesByStateProvinceId: {method: 'GET', params: {target: 'cities'}, headers: {'Content-Type': 'application/json'}}});
            }]
        );

cpServices.factory('languages',
        ['$resource', 'base_url',
            function ($resource,base_url) {
                return $resource(base_url+'rest/languages/:language_id',
                        {language_id: '@language_id'},
                {getActiveLanguages: {method: 'GET', params: {active: '1'}, headers: {'Content-Type': 'application/json'}}});
            }]
        );

cpServices.factory('unsubscribeInitialData',
        ['countries', 'contacts', '$q', '$routeParams',
            function (countries, contacts, $q, $routeParams) {
                return {
                    getData: function () {
                        
                        var delay = $q.defer();
                        var calls = [];
                        console.log($routeParams);
                        
                        calls.push(countries.get().$promise);
                        calls.push(countries.get({'country_id':$routeParams.countryCode}).$promise);
                        calls.push(contacts.getByEncodedId({'contact_id':$routeParams.contactId}).$promise);
                        calls.push(countries.getLanguagesByCountryId({'country_id':$routeParams.countryCode}).$promise);
                        
                        $q.all(calls).then(
                                function (results) {
                                   delay.resolve({
                                        'countries'     : results[0].countries,
                                        'country'       : results[1].country,
                                        'contact'       : results[2].contact, 
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

cpServices.factory('subscribeInitialData',
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


