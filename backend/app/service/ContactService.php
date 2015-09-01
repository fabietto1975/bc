<?php

namespace baccarat\app\service;

use baccarat\app\dao\ContactDAO;
use baccarat\app\dao\CountryDAO;
use baccarat\app\dao\StateProvinceDAO;
use baccarat\app\dao\CityDAO;
use baccarat\app\model\ContactBean;

class ContactService {

    private $countryDao;
    private $stateProvinceDao;
    private $cityDao;
    private $contactDao;

    public function __construct() {
        $this->countryDao = new CountryDAO();
        $this->contactDao = new ContactDAO();
        $this->stateProvinceDao = new StateProvinceDAO();
        $this->cityDao = new CityDAO();
    }

    private function checkFields($parameters) {
        include (APP_ROOT . '/config/config-local.php');

        $missingFields = array();
        $existingEmail = false;
        $invalidEmail = false;
        $invalidDate = false;
        if (empty($parameters['firstName'])) {
            array_push($missingFields, 'firstName');
        }
        if (empty($parameters['lastName'])) {
            array_push($missingFields, 'lastName');
        }

        if (empty($parameters['title'])) {
            array_push($missingFields, 'title');
        }
        /*
        list($birthDayMonth, $birthDayDay) = split("-", $parameters['birthDay']);
        if (is_numeric($birthDayMonth) && is_numeric($birthDayDay)) {
            if (!checkdate($birthDayMonth, $birthDayDay, 1970)) {
                $invalidDate = true;
            }
        } else {
            $invalidDate = true;
        }
        */

        if (empty($parameters['email'])) {
            //array_push($missingFields, 'email');
        } else {
            $email = $parameters['email'];
            if ($email != $defaultEmail) {
                $mailContact = $this->emailCheck($email);

                if (filter_var($email, FILTER_VALIDATE_EMAIL) === false) {
                    $invalidEmail = true;
                } else {
                    if (!empty($parameters['contactID']) &&
                            (isset($mailContact[0])) && ($mailContact[0]->getContactID() == $parameters['contactID'])) {
                        $existingEmail = false;
                    } else if (count($mailContact) > 0) {
                        $existingEmail = true;
                    }
                }
            }
        }
        if (empty($parameters['mobile'])) {
            array_push($missingFields, 'mobile');
        }
        if (empty($parameters['country'])) {
            array_push($missingFields, 'country');
        }
        if (empty($parameters['stateprovince'])) {
            array_push($missingFields, 'stateprovince');
        }
        if (empty($parameters['city'])) {
            array_push($missingFields, 'city');
        }
        /*
         * FABIO, a cosa serve?
        if (empty($parameters['language'])) {
            array_push($missingFields, 'language');
        }
        */

        if ((count($missingFields) > 0) || ($existingEmail) || ($invalidEmail) || ($invalidDate)) {
            return array(
                'status' => 'KO',
                'missingFields' => $missingFields,
                'existingEmail' => $existingEmail,
                'invalidEmail' => $invalidEmail,
                'invalidDate' => $invalidDate
            );
        } else {
            return array(
                'status' => 'OK'
            );
        };
    }

    public function isValidEmail($email) {
        if (filter_var($email, FILTER_VALIDATE_EMAIL) === false)
            return false;

        return true;
    }

    public function isValidDate($date) {
        $mdy = date_parse($date);
        if ($mdy != false && checkdate($mdy['month'], $mdy['day'], $mdy['year']))
            return true;

        return false;
    }

    public function create($parameters) {
        include (APP_ROOT . '/config/config-local.php');


        $check = $this->checkFields($parameters);
        if ($check['status'] == 'KO') {
            return $check;
        }
        $contactBean = $this->createContactBean($parameters);

        //Add contact to VTiger
        /*
          return array(
          'data' => $contactBean
          );
         */
        $res = $this->contactDao->create($contactBean);

        return $this->createReturnDataArray($res);
    }

    public function readContactByEncodedId($encodedId) {
        $id = $this->contactDao->readContactByEncodedId($encodedId);

        $res = '';
        if ($id == null) {
            return null;
        } else {
            $prefix = $this->contactDao->getModulePrefix();
            $res = $this->readByID($prefix . 'x' . $id);
        }

        return $res;
    }

    public function readById($id) {

        $res = $this->contactDao->readByID($id);
        if ($res != null) {
            $this->setExtendedInfo($res);
        }
        return $res;
    }

    public function update($parameters) {
        include (APP_ROOT . '/config/config-local.php');


        $check = $this->checkFields($parameters, 'UPDATE');
        if ($check['status'] == 'KO') {
            return $check;
        }
        $contactBean = $this->createContactBean($parameters);
        $res = $this->contactDao->update($contactBean);
        return $this->createReturnDataArray($res);
    }

    public function searchCustomerContact($keyList, $countryDesc) {
        $check = true;
        $email = '';
        $checkDate = true;
        $missingFields = array();


           
        if (($keyList['lastname']) == '') {
            array_push($missingFields, 'lastname');
        }

        /*
        if (empty($keyList['mailingcountry'])) {
            array_push($missingFields, 'mailingcountry');
        }

        if ($countryDesc != 'CANADA' && $countryDesc != 'CHINA' && $countryDesc != 'HONK KONG') {
            if (($keyList['mailingzip'] == '')) {
                array_push($missingFields, 'zipcode');
            }
        }

        if ($countryDesc != 'CANADA') {
            if (empty($keyList['birthday'])) {
                array_push($missingFields, 'birthday');
            }
        }
         */
        if (isset($keyList['birthday']))
            $checkDate = $this->isValidDate($keyList['birthday']);
        
        if ($checkDate == false) {
            return array(
                'status' => 'KO',
                'msg' => 'invalidDate'
            );
        }

        if (isset($keyList['email'])) {
            $email = $keyList['email'];
            $check = $this->isValidEmail($email);
        }

        if ($check == false) {
            return array(
                'status' => 'KO',
                'msg' => 'invalidEmail'
            );
        }

        if ((count($missingFields) > 0)) {
            return array(
                'status' => 'KO',
                'missingFields' => $missingFields
            );
        }

        $contacts = $this->contactDao->searchContact($keyList);

        foreach ($contacts as $contact) {
            $this->setExtendedInfo($contact);
        }
        return $contacts;
    }

    public function searchContact($keyList) {
        $check = true;
        $email = '';
        $checkDate = true;

        if (count($keyList) == 0) {
            return array(
                'status' => 'KO',
                'msg' => 'noparameters'
            );
        }


        if (isset($keyList['email'])) {
            $email = $keyList['email'];
            $check = $this->isValidEmail($email);
        }

        if ($check == false) {
            return array(
                'status' => 'KO',
                'msg' => 'invalidemail'
            );
        }

        if (isset($keyList['birthday']))
            $checkDate = $this->isValidDate($keyList['birthday']);

        if ($checkDate == false) {
            return array(
                'status' => 'KO',
                'msg' => 'invaliddate'
            );
        }


        $contacts = $this->contactDao->searchContact($keyList);

        foreach ($contacts as $contact) {

            $this->setExtendedInfo($contact);
        }

        return $contacts;
    }

    //Private utility functions
    private function createReturnDataArray($res) {

        if (
                ($res['res'] != null) &&
                ($res['status'] == 'OK')) {
            $this->setExtendedInfo($res['res']);
            return array(
                'status' => 'OK',
                'res' => $res['res']
            );
        } else {
            return array(
                'status' => 'ko',
                'error_code' => $res['code'],
                'error_message' => $res['message'],
            );
        }
    }

    private function setExtendedInfo($contact) {
        $country = $this->countryDao->readByID($contact->getCountry());
        if ($country != null) {
            $contact->setCountryDesc($country['country_desc']);
        }
        $stateProvince = $this->stateProvinceDao->readByID($contact->getStateProvince());
        if ($stateProvince != null) {
            $contact->setStateProvinceDesc($stateProvince['stateprovince_desc']);
        }
        $city = $this->cityDao->readByID($contact->getTownCity());
        if ($city != null) {
            $contact->setTownCityDesc($city['city_desc']);
        }
    }

    private function createContactBean($req) {

        $contactID = isset($req["contactID"]) ? $req["contactID"] : NULL;

        $firstName = isset($req["firstName"]) ? $req["firstName"] : "";
        $middleName = isset($req["middleName"]) ? $req["middleName"] : "";
        $lastName = isset($req["lastName"]) ? $req["lastName"] : "";

        $firstNameLocale = isset($req["firstNameLocale"]) ? $req["firstNameLocale"] : "";
        $middleNameLocale = isset($req["middleNameLocale"]) ? $req["middleNameLocale"] : "";
        $lastNameLocale = isset($req["lastNameLocale"]) ? $req["lastNameLocale"] : "";

        $country = isset($req["country"]["country_id"]) ? $req["country"]["country_id"] : "";
        $countryDesc = isset($req["country"]["country_desc"]) ? $req["country"]["country_desc"] : "";
        $stateProvince = isset($req["stateprovince"]["stateprovince_id"]) ? $req["stateprovince"]["stateprovince_id"] : "";
        $townCity = isset($req["city"]["city_id"]) ? $req["city"]["city_id"] : "";
        $townCityDesc = isset($req["city"]["city_desc"]) ? $req["city"]["city_desc"] : "";
        $zipCode = isset($req["zipCode"]) ? $req["zipCode"] : "";
        $address = isset($req["address"]) ? $req["address"] : "";

        $title = isset($req["title"]) ? $req["title"] : "";

        $email = isset($req["email"]) ? $req["email"] : "";

        $birthDay = isset($req["birthDay"]) ? $req["birthDay"] : "";
        $birthMonth = isset($req["birthMonth"]) ? $req["birthMonth"] : "";
        $mobile = isset($req["mobile"]) ? $req["mobile"] : "";
        $homePhone = isset($req["phone"]) ? $req["phone"] : "";

        $preferredLanguage = isset($req["preferredLanguage"]["language_id"]) ? $req["preferredLanguage"]["language_id"] : "";
        $preferredLanguageDesc = isset($req["preferredLanguage"]["language_desc"]) ? $req["preferredLanguage"]["language_desc"] : "";
        $contactMediaPref = isset($req["contactMediaPref"]) ? $req["contactMediaPref"] : "";

        $privacy = isset($req["privacyPreferences"]) ? $req["privacyPreferences"] : NULL;
        $barcodeNumber = isset($req["barcodeId"]) ? $req["barcodeId"] : "";
        $barcodeImg = isset($req["barcodeImg"]) ? $req["barcodeImg"] : "";
        $brandLine = isset($req["brandLine"]) ? $req["brandLine"] : "";
        $createdBy = isset($req["createdBy"]) ? $req["createdBy"] : "";
        $lastEditedBy = isset($req["lastEditedBy"]) ? $req["lastEditedBy"] : "";

        $autoBarcode = isset($req["autoBarcode"]) ? $req["autoBarcode"] : "";

        if ($autoBarcode == "auto") {
            $barcodeNumber = "";
        } else {
            $barcodeNumber = trim($barcodeNumber);
        }

        $contactBean = new ContactBean();
        $contactBean->setTitle($title);
        $contactBean->setFirstName($firstName);
        $contactBean->setMiddleName($middleName);
        $contactBean->setLastName($lastName);
        $contactBean->setEmail($email);
        $contactBean->setBirthDay($birthDay);
        $contactBean->setBirthMonth($birthMonth);
        $contactBean->setMobile($mobile);
        $contactBean->setHomePhone($homePhone);
        $contactBean->setPreferredLanguage($preferredLanguage);
        $contactBean->setPreferredLanguageDesc($preferredLanguageDesc);
        $contactBean->setCountry($country);
        $contactBean->setCountryDesc($countryDesc);
        $contactBean->setStateProvince($stateProvince);
        $contactBean->setTownCity($townCity);
        $contactBean->setTownCityDesc($townCityDesc);
        $contactBean->setZipCode($zipCode);
        $contactBean->setAddress($address);
        $contactBean->setContactMediaPref($contactMediaPref);
        $contactBean->setPrivacyOptions($privacy);
        $contactBean->setLastEditedBy($lastEditedBy);

        $contactBean->setBarcodeNumber($barcodeNumber);
        //$contactBean->setBrandLineInterested($brandLine);
        //$contactBean->setProductCategoryInterested($productCategory);
        //$contactBean->setFirstNameLocale($firstNameLocale);
        //$contactBean->setMiddleNameLocale($middleNameLocale);
        //$contactBean->setLastNameLocale($lastNameLocale);


        if (isset($req['preferredStore'])) {
            $contactBean->setStoreId($req['preferredStore']['store_id']);
            $contactBean->setStoreDesc($req['preferredStore']['store_desc']);
        }
        // Only for UPDATE operation.
        if (isset($contactID)) {
            $contactBean->setContactID($contactID);
        }
        $contactBean->setCreatedBy($createdBy);

        return $contactBean;
    }

    private function emailCheck($email) {
        $keylist = [];
        $keylist['email'] = $email;
        return $this->contactDao->searchContact($keylist);
    }

    public function getCustomerKPI($barcode) {
        return $this->contactDao->readKPIByBarcode($barcode);
    }

    public function getCustomerLastTransactions($barcode) {
        $ticketRows = $this->contactDao->readLastTransactionsByBarcode($barcode);

        $transactions = array();
        $currTotalAmount = null;
        foreach ($ticketRows as $ticketRow) {

            $tmpTransactionId = $ticketRow['transaction_id'];
            if (isset($transactions[$tmpTransactionId])) {
                $transaction = $transactions[$tmpTransactionId];
            } else {
                $transaction = array();
                $transaction['transaction_id'] = $tmpTransactionId;
                $transaction['transaction_date'] = $ticketRow['transaction_date'];
                unset($ticketRow['transaction_date']);
                $transaction['transaction_store_label_cod_desc'] = $ticketRow['store_label_cod_desc'];
                unset($ticketRow['store_label_cod_desc']);
                $transaction['transaction_store_type_cod_desc'] = $ticketRow['store_type_cod_desc'];
                unset($ticketRow['store_type_cod_desc']);
                $transaction['transaction_entity_desc'] = $ticketRow['entity_desc'];
                unset($ticketRow['entity_desc']);
                $transaction['currency'] = $ticketRow['currency'];
                unset($ticketRow['currency']);
                $transaction['amount'] = 0;

                $transaction['transaction_rows'] = array();
            }


            $transaction['total_amount'] = round($ticketRow['total_amount']);
            unset($ticketRow['total_amount']);

            $transactionRows = $transaction['transaction_rows'];
            $ticketRow['price'] = round($ticketRow['price']);
            array_push($transactionRows, $ticketRow);

            $transaction['transaction_rows'] = $transactionRows;
            $transactions[$tmpTransactionId] = $transaction;
        }
        return $transactions;
    }

    public function getCustomerTransactions($barcode) {
        $ticketRows = $this->contactDao->readTransactionsByBarcode($barcode);

        $transactions = array();
        $currTotalAmount = null;
        foreach ($ticketRows as $ticketRow) {

            $tmpTransactionId = $ticketRow['transaction_id'];
            if (isset($transactions[$tmpTransactionId])) {
                $transaction = $transactions[$tmpTransactionId];
            } else {
                $transaction = array();
                $transaction['transaction_id'] = $tmpTransactionId;
                $transaction['transaction_date'] = $ticketRow['transaction_date'];
                unset($ticketRow['transaction_date']);
                $transaction['transaction_store_label_cod_desc'] = $ticketRow['store_label_cod_desc'];
                unset($ticketRow['store_label_cod_desc']);
                $transaction['transaction_store_type_cod_desc'] = $ticketRow['store_type_cod_desc'];
                unset($ticketRow['store_type_cod_desc']);
                $transaction['transaction_entity_desc'] = $ticketRow['entity_desc'];
                unset($ticketRow['entity_desc']);
                $transaction['currency'] = $ticketRow['currency'];
                unset($ticketRow['currency']);
                $transaction['amount'] = 0;

                $transaction['transaction_rows'] = array();
            }


            $transaction['total_amount'] = round($ticketRow['total_amount']);
            unset($ticketRow['total_amount']);

            $transactionRows = $transaction['transaction_rows'];
            $ticketRow['price'] = round($ticketRow['price']);
            array_push($transactionRows, $ticketRow);

            $transaction['transaction_rows'] = $transactionRows;
            $transactions[$tmpTransactionId] = $transaction;
        }
        return $transactions;
    }

    public function getTransactionsByCategory($barcode) {
        $ticketRows = $this->contactDao->readTransactionsByCategory($barcode);

        $totalTransactionPerCategory = array();
        foreach ($ticketRows as $ticketRow) {
            $tmpcategoryDesc = $ticketRow['category_desc'];
            if (isset($totalTransactionPerCategory[$tmpcategoryDesc])) {
                $transactionPerCategory = $totalTransactionPerCategory[$tmpcategoryDesc];
            } else {
                $transactionPerCategory = array();
                $transactionPerCategory['category_desc'] = $tmpcategoryDesc;
                $transactionPerCategory['amount'] = 0;
                $transactionPerCategory['transaction_rows'] = array();
            }
            $transactionRows = $transactionPerCategory['transaction_rows'];
            $transactionPerCategory['amount'] += round($ticketRow['price'] / $ticketRow['conversion']);
            array_push($transactionRows, $ticketRow);

            $transactionPerCategory['transaction_rows'] = $transactionRows;
            $totalTransactionPerCategory[$tmpcategoryDesc] = $transactionPerCategory;
        }
        return $totalTransactionPerCategory;
    }

    public function getCustomerCampaigns($customerId) {
        return $this->contactDao->readLastCampaignByConsumerId($customerId);
    }

    public function getCustomerSizes($customerId) {
        return $this->contactDao->readCustomerSizes($customerId);
    }

}
