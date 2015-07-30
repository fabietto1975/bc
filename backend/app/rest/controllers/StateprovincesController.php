<?php

namespace baccarat\app\rest\controllers;

use baccarat\common\rest\controllers\AbstractController;

use baccarat\app\service\StateProvinceService;

class StateprovincesController extends AbstractController {

    private $stateProvinceService;

    public function __construct($request) {
        parent::__construct($request);
        $this->stateProvinceService = new StateProvinceService();
    }

    public function getAction() {

        $result = null;
        if (isset($this->request->url_elements[2])) {
            $stateProvinceId = $this->request->url_elements[2];
            if (isset($this->request->url_elements[3])) {
                $function = $this->request->url_elements[3];
                if ($function = 'cities') {
                    $result = $this->stateProvinceService->readCitiesByStateProvince($stateProvinceId);
                    $this->getResultData($result, 'cities',null);
                    return $this->data;
                }
            }
            $result = $this->stateProvinceService->readById($stateProvinceId);
            $this->getResultData($result, 'stateProvince', null);
        } else {
            $this->getResultData($this->stateProvinceService->read(), 'stateProvinces',null);
        }
        return $this->data;
    }

    public function postAction() {
        
    }

}
