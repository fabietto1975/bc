<?php

namespace baccarat\app\dao;

use Salaros\Vtiger\VTWSCLib\WSClient;
use baccarat\app\model\ContactBean;

class ContactDAO extends DAOAbstract implements DAOInterface {

    private $wsclient;
    private static $MODULE = "Contacts";

    public function __construct() {
        include(APP_ROOT . '/config/config-local.php');
        $this->wsclient = new WSClient($vtigercrm['url']);
        $this->wsclient->login($vtigercrm['username'], $vtigercrm['accesskey']);
        parent::__construct();
    }

    public function create($contactBean) {
        $valueMap = $contactBean->bindToVTigerEntity();

        $record = $this->wsclient->entityCreate($this::$MODULE, $valueMap);

        if (!$record) {
            return array(
                'status' => 'KO',
                'error' => $this->wsclient->getLastError()->getError()
            );
        }
        if ($contactBean->bindResults($record)) {
            return array(
                'status' => 'OK',
                'res' => $contactBean
            );
        } else {
            return array(
                'status' => 'KO',
                'error' => 'Unable to create entity'
            );
        }
    }

    public function delete($id) {
        
    }

    public function read() {
        
    }

    public function getModulePrefix() {
        return $this->wsclient->getType($this::$MODULE)['idPrefix'];
    }

    public function readByID($id) {

        $record = $this->wsclient->entityRetrieveByID($this::$MODULE, $id);

        if ($record == null) {
            return null;
        } else {
            $bean = new ContactBean();
            $bean->bindResults($record);

            return $bean;
        }
    }

    public function update($contactBean) {

        $valueMap = $contactBean->bindToVTigerEntity();
        /*
        print_r($valueMap);
        die();
        */
        $record = $this->wsclient->entityUpdate($this::$MODULE, $valueMap);
        if (!$record) {
            return array(
                'status' => 'OK',
                'error' => $this->wsclient->getLastError()->getError()
            );
        }
        if ($contactBean->bindResults($record)) {
            return array(
                'status' => 'OK',
                'res' => $contactBean
            );
        } else {
            return array(
                'status' => 'KO',
                'error' => 'Unable to update entity'
            );
        }
    }

    public function searchContact($keyList) {
        $query = "SELECT * FROM Contacts WHERE ";
        // dynamic build of the query string based on key list 
        $keys = array_keys($keyList);
        $added = false;
        foreach ($keys as $key) {
            if ($keyList[$key] != '') {
                if ($added == true) {
                    $query .= " AND ";
                }
                $param = $keyList[$key];
                if ($key == 'birthday') {
                    $operator = ' = ';
                } else {
                    $operator = ' LIKE ';
                }
                $query .= $key . $operator . "'" . $param . "'";
                $added = true;
            }
        }
        $records = $this->wsclient->query($query . ";");
        $contactList = array();

        if ($records) {
            foreach ($records as $record) {
                $bean = new ContactBean();
                if ($bean->bindResults($record)) {
                    $contactList[] = $bean;
                }
            }
        }

        return $contactList;
    }

    public function readKPIByBarcode($barcodeConsumer) {
        $stmt = $this->getPdo()->prepare("SELECT * FROM KPI k WHERE k.barcode = :barcode");
        $stmt->bindValue('barcode', $barcodeConsumer);
        $stmt->execute();
        return $stmt->fetch();
    }

    public function readLastTransactionsByBarcode($barcodeConsumer) {
        $stmt = $this->getPdo()->prepare("
            SELECT *,
                (case when @transaction_id != transaction_id then @sum := price else @sum := @sum + price end) as total_amount,
                     (case when @transaction_id != transaction_id then @transaction_id := transaction_id else @transaction_id end) as _
            FROM (SELECT * FROM LAST_TRANSACTION_ROW trx WHERE trx.customer_barcode = :barcode ORDER BY trx.transaction_date desc, trx.transaction_id, trx.ticket_row asc) main
            JOIN (SELECT @sum := 0) s
            JOIN (SELECT @transaction_id := '') a
            LEFT JOIN STORE s on (s.entity_id = main.store_id)
            ORDER BY transaction_date, price desc;
        		");
        $stmt->bindValue('barcode', $barcodeConsumer);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function readTransactionsByBarcode($barcodeConsumer) {
        $stmt = $this->getPdo()->prepare("
            SELECT *,
                (case when @transaction_id != transaction_id then @sum := price else @sum := @sum + price end) as total_amount,
                     (case when @transaction_id != transaction_id then @transaction_id := transaction_id else @transaction_id end) as _
            FROM (SELECT * FROM LAST_TRANSACTION_ROW trx WHERE trx.customer_barcode = :barcode ORDER BY trx.transaction_date desc, trx.transaction_id, trx.ticket_row asc) main
            JOIN (SELECT @sum := 0) s
            JOIN (SELECT @transaction_id := '') a
            LEFT JOIN STORE s on (s.entity_id = main.store_id)
            ORDER BY transaction_date, price desc;
        		");
        $stmt->bindValue('barcode', $barcodeConsumer);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function readLastCampaignByConsumerId($consumerId) {
        $stmt = $this->getPdo()->prepare("
                    SELECT 
                        cc . *,
                        c.subject,
                        c.link_creativity,
                        c.CAMPAIGN_SELECTION,
                        c.CAMPAIGN_VALUES
                    FROM
                        CUSTOMER_CAMPAIGN cc
                            join
                        CAMPAIGN c ON (cc.campaign_id = c.campaign_id
                            and cc.CELL_PACKAGE_SK = c.cell_package_sk)
                    WHERE
                        consumer_id = :consumerId
                    ORDER BY 
                        cc.received desc");
        $stmt->bindValue('consumerId', $consumerId);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function readCustomerSizes($consumerId) {
        $stmt = $this->getPdo()->prepare("
                    SELECT 
                        *
                    FROM
                        LAST_SIZE s
                    WHERE
                        s.CUSTOMER_BARCODE = :consumerId
                    ORDER BY CATEGORY_DESC");
        $stmt->bindValue('consumerId', $consumerId);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function readTransactionsByCategory($barcodeConsumer) {
        $stmt = $this->getPdo()->prepare("
            SELECT *
               
            FROM LAST_TRANSACTION_ROW_BYCAT c
            WHERE c.customer_barcode = :barcode
        		ORDER BY c.price desc
    	    ");


        $stmt->bindValue('barcode', $barcodeConsumer);
        $stmt->execute();
        return $stmt->fetchAll();
    }

}
