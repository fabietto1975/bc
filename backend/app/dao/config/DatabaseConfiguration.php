<?php

namespace baccarat\app\dao\config;

class DatabaseConfiguration {

    private $connectionParams;

    public function __construct() {
        $this->connectionParams = parse_ini_file(APP_ROOT . '/config/database.ini');
    }

    public function getConnectionParameters() {
        return $this->connectionParams;
    }

}
