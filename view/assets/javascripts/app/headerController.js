'use strict';
baccaratControllers.controller('headerController',
        ['$translate',
            '$scope',
            '$rootScope',
            '$location',
            '$cookieStore',
            'languages',
            function ($translate, $scope, $rootScope,$location, $cookieStore, languages) {
                console.log($rootScope);
                if ($rootScope.languages == null){
                    languages.getActiveLanguages(function(data){

                        console.log(data.languages);
                        $rootScope.languages = data.languages;
                        //var langKey = $cookieStore.get('lang');
                        console.log('$rootScope.language',$rootScope.language);
                        if ($rootScope.language){
                            $translate.use($rootScope.language.lang_iso2);
                        } else {
                            $rootScope.language = $scope.languages[0]; //EN
                            $scope.changeLanguage();
                        }
                    })
                    
                }
                
                $scope.changeLanguage = function(){
                    var langKey = $rootScope.language.lang_iso2;
                    console.log('$rootScope.language',$rootScope.language);
                    console.log(langKey);
                    $translate.use(langKey);
                    //$cookieStore.put('lang',langKey);
                }
                
                
            }
        ]
        );







 