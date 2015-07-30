<?php

namespace baccarat\app\service;

use baccarat\app\dao\CountryDAO;
use baccarat\app\dao\LanguageDAO;
use baccarat\app\dao\StateProvinceDAO;

class CountryService {

    private $countryDao;
    private $languageDao;
    private $stateProvinceDao;

    public function __construct() {
        $this->countryDao = new CountryDAO();
        $this->languageDao = new LanguageDAO();
        $this->stateProvinceDao = new StateProvinceDAO();
    }

    public function readById($id) {
        $res = $this->countryDao->readByID($id);
        return $res;
    }

    public function readStateProvincesByCountry($countryId) {
        $res = $this->stateProvinceDao->readByCountryId($countryId);
        return $res;
    }

    public function readLanguagesByCountry($countryId) {
        $res = $this->languageDao->readLanguagesByCountry($countryId);
        if ($res == null) {
            return array();
        }
        return $res;
    }

    public function read() {
        $res = $this->countryDao->read();
        if ($res == null) {
            return array();
        }
        return $res;
    }

}
