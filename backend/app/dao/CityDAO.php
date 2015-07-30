<?php

namespace baccarat\app\dao;

class CityDAO extends DAOAbstract implements DAOInterface {

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
        $stmt = $this->getPdo()->prepare("SELECT * FROM CITY cy ORDER BY cy.city_desc");
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function readByID($id) {
        $stmt = $this->getPdo()->prepare("SELECT * FROM CITY cy WHERE cy.city_id = :city_id");
        $stmt->bindValue('city_id', $id);
        $stmt->execute();
        return $stmt->fetch(); 
    }

    public function update($item) {
        //NOP
    }
    
    //
    public function readByStateProvinceId($stateProvinceId){
        $stmt = $this->getPdo()->prepare("SELECT * FROM CITY cy "
                . "WHERE cy.stateprovince_id = :stateProvinceId ");
        $stmt->bindValue('stateProvinceId', $stateProvinceId);
        $stmt->execute();
        return $stmt->fetchAll(); 
    }

}
