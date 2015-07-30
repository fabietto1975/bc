<?php

namespace baccarat\app\rest\controllers;

use baccarat\common\rest\controllers\AbstractController;
use baccarat\app\service\ContactService;
use Logger;

class ContactsController extends AbstractController {

    private $contactService;
    private $logger;
    private $loggerAC;
    
    public function __construct($request) {
        parent::__construct($request);

        $this->loggerAC = Logger::getLogger("ACCESS-CONTROL");
        $this->logger = Logger::getLogger(get_class());
        $this->contactService = new ContactService ();
    }

    public function getAction() {
                
        if (isset($this->request->url_elements [2])) {
            $barcode = $this->request->url_elements [2];
            if (isset($this->request->url_elements [3])) {
                $param = $this->request->url_elements [3];
                if ($param === 'kpi') {
                    $this->getResultData($this->contactService->getCustomerKPI($barcode), 'consumerkpi', null);
                } else if ($param === 'transactions') {
                    $this->getResultData($this->contactService->getCustomerLastTransactions($barcode), 'consumertransactions', null);
                } else if ($param === 'transactionprice') {
                    $this->getResultData($this->contactService->getCustomerTransactions($barcode), 'consumertransactionprice', null);
                } else if ($param === 'sizes') {
                    $this->getResultData($this->contactService->getCustomerSizes($barcode), 'customersizes', null);
                } else if ($param === 'campaigns') {
                    // NB it is actually customerId not barcode
                    $this->getResultData($this->contactService->getCustomerCampaigns($barcode), 'consumercampaigns', null);
                } else if ($param === 'transactionsByCategory') {
                    $this->getResultData($this->contactService->getTransactionsByCategory($barcode), 'transactioncategory', null);
                }
            } else {
                // NB it is actually customerId not barcode
                $this->getResultData($this->contactService->readById($barcode), 'consumer', null);
            }
        } else if (isset($this->request->parameters ['q'])) {

            $keylist = array();
            $query = $this->request->parameters ['q'];

            if ($query != '') {
                $pairs = explode(';', $query);
                foreach ($pairs as $pair) {
                    $item = explode(':', $pair);
                    $keylist [$item [0]] = $item [1];
                }
            }
            if ($this->request->parameters ['mode'] == 'staff') {
                $this->getResultData($this->contactService->searchContact($keylist), 'contacts', null);
            } else if ($this->request->parameters ['mode'] == 'customer') {
                $countryDesc = $this->request->parameters ['countryDesc'];
                $this->getResultData($this->contactService->searchCustomerContact($keylist, $countryDesc), 'contacts', null);
            }
        }
        return $this->data;
    }

    public function postAction() {
        
        $parameters = $this->request->parameters;
        //if (isset($parameters ['customer'] ['contactID']) && ($parameters ['customer'] ['contactID'] != '')) {
        if (isset($parameters ['contactID']) && ($parameters ['contactID'] != '')) {
            $res = $this->contactService->update($parameters );
            if ($res['status']=='OK'){
                $this->loggerAC->info("User ".$parameters ['firstName']." ".$parameters ['lastName']." updated by "  .$parameters ['lastEditedBy']);
            }
        } else {
            //$res = $this->contactService->create($parameters ['customer']);
            $res = $this->contactService->create($parameters);
            if ($res['status']=='OK'){
                $this->loggerAC->info("User ".$parameters ['firstName']." ".$parameters ['lastName']." created by "  .$parameters ['createdBy']);
            }
        }
        return $res;
    }

}
