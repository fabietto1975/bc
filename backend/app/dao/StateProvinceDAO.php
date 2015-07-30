<?php

namespace  baccarat\app\dao;

class StateProvinceDAO extends DAOAbstract implements DAOInterface {

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
        $stmt = $this->getPdo()->prepare("SELECT * FROM STATEPROVINCE sp ORDER BY sp.stateprovince_desc");
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function readByID($id) {
        $stmt = $this->getPdo()->prepare("SELECT * FROM STATEPROVINCE sp WHERE sp.stateprovince_id = :stateprovince_id ");
        $stmt->bindValue('stateprovince_id', $id);
        $stmt->execute();
        return $stmt->fetch(); 
    }

    public function update($item) {
        //NOP
    }
    
    //
    public function readByCountryId($countryId){
        $stmt = $this->getPdo()->prepare("SELECT * FROM STATEPROVINCE sp "
               /* . "join country c on (c.country_id=sp.country_id) " */
                . "WHERE sp.country_id = :country_id ");
        $stmt->bindValue('country_id', $countryId);
        $stmt->execute();
        return $stmt->fetchAll(); 
    }

}
