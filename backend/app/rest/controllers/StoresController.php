<?php

namespace baccarat\app\rest\controllers;

use baccarat\common\rest\controllers\AbstractController;

use baccarat\app\service\StoreService;

class StoresController extends AbstractController {

    private $storeService;

    public function __construct($request) {
        parent::__construct($request);
        $this->storeService = new StoreService();
    }

    public function getAction() {

        if (isset($this->request->url_elements[2])) {
            //Read by id
        } else {
            $this->getResultData($this->storeService->read(), 'stores',null);
        }
        return $this->data;
    }

    public function postAction() {
        
    }

}
