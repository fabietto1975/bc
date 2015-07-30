<?php

namespace baccarat\app\dao;

class StoreDAO extends DAOAbstract implements DAOInterface {

    public function __construct() {
        parent::__construct();
    }

    public function create($item) {
        //NOP
    }

    public function delete($id) {
        //NOP
    }
    
    public function readByID($id) {
        //NOP
    }

    public function read() {
        $stmt = $this->getPdo()->prepare("SELECT * FROM V_STORE v ORDER BY NOM_TIERS");
        $stmt->execute();
        return $stmt->fetchAll();
    }

    

    public function update($item) {
        //NOP
    }
    
    //
   
}
