<?php

namespace baccarat\common\rest\controllers;

abstract class AbstractController {
	
        
        protected $data;
        protected $request;
        
        public abstract function getAction();
	    public abstract function postAction();
        
        public function __construct($request) {
            $this->request = $request;
            $this->data ['request_time'] = date ( "D M j G:i:s T Y", $this->request->datetime );
            $this->data['code'] = 200;
        }
        
        protected function getResultData($result, $entity , $message) { 

            if ($result===null){
                $this->data ['status'] = "fail";
                if ($message!=null){
                    $this->data ['message'] = $message;
                }
               
            } else { 
                $this->data ['status'] = "success";
                $this->data [$entity] = $result;
            }
        } 
}

