<?php

namespace baccarat\app\dao;

class CountryDAO extends DAOAbstract implements DAOInterface {

    public function __construct() {
        parent::__construct();
    }

    public function create($item) {
        //NOP
    }

    public function delete($id) {
        //NOP
    }

    public function read() {
        $stmt = $this->getPdo()->prepare("SELECT * FROM COUNTRY c ORDER BY c.country_desc");
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function readByID($id) {
        $stmt = $this->getPdo()->prepare("SELECT * FROM COUNTRY c WHERE c.country_id = :country_id");
        $stmt->bindValue('country_id', $id);
        $stmt->execute();
        return $stmt->fetch(); 
    }

    public function readByISOCode($iso) {
        $stmt = $this->getPdo()->prepare("SELECT * FROM COUNTRY c WHERE c.country_iso2 = :iso LIMIT 1");
        $stmt->bindValue('iso', $iso);
        $stmt->execute();
        return $stmt->fetch(); 
    }
    
    public function update($item) {
        //NOP
    }

}
