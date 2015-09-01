<?php

namespace baccarat\app\dao;

class LoginDAO extends DAOAbstract implements DAOInterface {

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
        //NOP
    }

    public function readByID($id) {
        //NOP
    }

    public function doLogout() {
        //NOP
    }

    public function checkLogin($parameters) {
        $stmt = $this->getPdo()->prepare("SELECT id, TRIM(CONCAT(u.NAME, ' ', u.SURNAME)) AS loggeduser FROM user u WHERE u.USERNAME = :username AND u.PASSWORD = :password");
        $stmt->bindValue('username', $parameters["username"]);
        $stmt->bindValue('password', $parameters["password"]);
        $stmt->execute();
        $res = $stmt->fetch();
        if ($res) {
            return array(
                'status' => 'OK', 'res' => $res
            );
        } else {
            return array(
                'status' => 'KO',
                'error_code' => -1,
                'error_message' => 'LOGIN_ERROR'
            );
        }
    }

    public function update($item) {
        //NOP
    }

}
