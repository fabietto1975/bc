<?php

namespace baccarat\app\service;

use baccarat\app\dao\LoginDAO;

class LoginService {

    private $LoginDao;

    public function __construct() {
        $this->LoginDao = new LoginDAO();
    }

    public function checkLogin($parameters) {
        $res = $this->LoginDao->checkLogin($parameters);
        return $this->createReturnDataArray($res);
    }

    public function doLogout() {
        $res = $this->LoginDao->doLogout();
        return $this->createReturnDataArray($res);
    }

    //Private utility functions
    private function createReturnDataArray($res) {
        if (($res['res'] != null) && ($res['status'] == 'OK')) {
            return array(
                'status' => 'OK',
                'res' => $res['res']
            );
        } else {
            return array(
                'status' => 'KO',
                'error_code' => $res['error_code'],
                'error_message' => $res['error_message'],
            );
        }
    }

}
