<?php

namespace baccarat\common\rest\views;

class JsonView {
    
   private function _requestStatus($code) {
        $status = array(  
            200 => 'OK', 
            401 => 'Unauthorized',   
            404 => 'Not Found',   
            405 => 'Method Not Allowed',
            500 => 'Internal Server Error',
        ); 
        return ($status[$code])?$status[$code]:$status[500]; 
    }
    
    public function render($content) {
        $status = 200;
        if (isset($content['code'])){
            $status = $content['code'];
        }
        header('Content-Type: application/json; charset=utf8');
        header("HTTP/1.1 " . $status . " " . $this->_requestStatus($status));
        echo trim(json_encode($content, JSON_PRETTY_PRINT)); /*, JSON_PRETTY_PRINT */
        return true;
    }
}
?>