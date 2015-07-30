<?php

namespace baccarat\app\rest\controllers;

use baccarat\common\rest\controllers\AbstractController;

use baccarat\app\service\LanguageService;

class LanguagesController extends AbstractController {

    private $languageService;

    public function __construct($request) {
        parent::__construct($request);
        $this->languageService = new LanguageService();
    }

    public function getAction() {

        if (isset($this->request->url_elements[2])) {
            //Read by id
        } else {
            if (isset($this->request->parameters['active']) && ($this->request->parameters['active'] === '1')) {
                $this->getResultData($this->languageService->readActive(), 'languages',null);
            } else {
                $this->getResultData($this->languageService->read(), 'languages',null);
            }
        }
        return $this->data;
    }

    public function postAction() {
        
    }

}
