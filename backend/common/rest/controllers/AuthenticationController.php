<?php

namespace baccarat\common\rest\controllers;

use baccarat\common\rest\controllers\AbstractController;
use Logger;

class AuthenticationController extends AbstractController {

    private $logger;

    public function __construct($request) {
        parent::__construct($request);
        $this->logger = Logger::getLogger(get_class());
        var_dump($request);
    }

    public function getAction() {
       
    }

    public function postAction() {
 
        $result = null;
        $message = null;
        $parameters = $this->request->parameters;
        if (isset($parameters['username']) && isset($parameters['password'])) {
            $username = $parameters['username'];
            $password = $parameters['password'];
            if ($username=='fabio' && $password=='pippo'){
                $result = 'ok';
            } else {
                $result = 'ko';
                //$this->data['code'] = 401;
            }    
        } else {
            $message = 'Please provide username AND password';
        }
        $this->getResultData($result, 'loginresult', $message);
        return $this->data;
    }

}
