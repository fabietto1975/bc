<?php

namespace baccarat\app\rest\controllers;

use baccarat\common\rest\controllers\AbstractController;
use baccarat\app\service\LoginService;
use Logger;

class LoginController extends AbstractController {

    private $LoginService;
    private $loggerAC;

    public function __construct($request) {
        parent::__construct($request);
        $this->loggerAC = Logger::getLogger("ACCESS-CONTROL");
        $this->LoginService = new LoginService();
    }

    public function getAction() {
        //NOP
    }

    public function postAction() {
        $parameters = $this->request->parameters;
        $res = $this->LoginService->checkLogin($parameters);
        if ($res['status'] == 'OK') {
            $this->loggerAC->info("User " . $parameters ['username'] . " is logged in " . date("Y-n-d H:i:s"));
        }
        return $res;
    }

}
