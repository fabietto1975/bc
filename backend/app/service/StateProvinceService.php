<?php

namespace baccarat\app\service;

use baccarat\app\dao\StateProvinceDAO;
use baccarat\app\dao\CityDAO;

class StateProvinceService {

    private $stateProvinceDao;
    private $cityDao;

    public function __construct() {
        $this->stateProvinceDao = new StateProvinceDAO();
        $this->cityDao = new CityDAO();
    }

    public function readById($id) {
        $res = $this->stateProvinceDao->readByID($id);
        return $res;
    }

    public function readCitiesByStateProvince($stateProvinceId) {
        $res = $this->cityDao->readByStateProvinceId($stateProvinceId);
        return $res;
    }

    public function read() {
        $res = $this->stateProvinceDao->read();
        return $res;
    }

}
