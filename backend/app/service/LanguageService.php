<?php

namespace baccarat\app\service;

use baccarat\app\dao\LanguageDAO;

class LanguageService {

    private $languageDao;

    public function __construct() {
        $this->languageDao = new LanguageDAO();
    }

    public function read() {
        $res = $this->languageDao->read();
        return $res;
    }

    public function readActive() {
        $res = $this->languageDao->readActive();
        return $res;
    }

}
