<?php

namespace baccarat\app\service;

use baccarat\app\dao\StoreDAO;

class StoreService {

    private $storeDao;

    public function __construct() {
        $this->storeDao = new StoreDAO();
    }

    public function read() {
        $res = $this->storeDao->read();
        return $res;
    }

    
}
