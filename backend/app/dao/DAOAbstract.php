<?php

namespace baccarat\app\dao;

use PDO;
use baccarat\app\dao\config\DatabaseConfiguration;


class DAOAbstract {

    private $dbp;
    private $dsn;
    private $pdo;
    private $errCode;
    private $errMessage;

 
    public function __construct() {
        
        $this->dbp = (new DatabaseConfiguration())->getConnectionParameters();
        
        $dbName = $this->dbp['dbname'];
    
        $this->dsn = "mysql:host=" . $this->dbp['host'] .
                ";port=" . $this->dbp['port'] .
                ";dbname=" . $dbName.
                ";charset=utf8";
        
        $this->pdo = new PDO($this->dsn, $this->dbp['user'], $this->dbp['password'], array(PDO::ATTR_PERSISTENT => false));
        $this->pdo->setAttribute ( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );
	$this->pdo->setAttribute ( PDO::ATTR_CASE, PDO::CASE_LOWER );
        $this->pdo->setAttribute ( PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC );
        $this->errCode = "";
        $this->errMessage = "";
    }

    public function getErrCode() {
        return $this->errCode;
    }

    public function getErrMessage() {
        return $this->errMessage;
    }

    public function setErrCode($errCode) {
        $this->errCode = $errCode;
    }

    public function setErrMessage($errMessage) {
        $this->errMessage = $errMessage;
    }
    
    public function getPdo() {
        return $this->pdo;
    }


}
