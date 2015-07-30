<?php

namespace baccarat\app\rest\controllers;

use baccarat\common\rest\controllers\AbstractController;
use baccarat\app\service\CountryService;

class CountriesController extends AbstractController {

    private $countryService;

    public function __construct($request) {
        parent::__construct($request);
        $this->countryService = new CountryService();
    }

    public function getAction() {
        $result = null;
        if (isset($this->request->url_elements[2])) {                
            $countryId = $this->request->url_elements[2];
            if (isset($this->request->url_elements[3])) {
                $function = $this->request->url_elements[3];
                if ($function == 'stateprovinces') {
                    $result = $this->countryService->readStateProvincesByCountry($countryId);
                    $this->getResultData($result, 'stateprovinces',null);
                    return $this->data;
                }
                if ($function == 'languages') {
                    $result = $this->countryService->readLanguagesByCountry($countryId);
                    $this->getResultData($result, 'languages',null);
                    return $this->data;
                }
            }
            $result = $this->countryService->readById($countryId);
            $this->getResultData($result, 'country', null);
        } else {

            $this->getResultData($this->countryService->read(), 'countries', null);
        }
        return $this->data;
    }

    public function postAction() {
        
    }

}
