<?php

namespace  baccarat\app\dao;

class LanguageDAO extends DAOAbstract implements DAOInterface {

    public function __construct() {
        parent::__construct();
    }

    public function create($item) {
        //NOP
    }

    public function delete($id) {
        //NOP
    }

    public function read() {
        $stmt = $this->getPdo()->prepare("SELECT * FROM LANGUAGE l ORDER BY l.picklist_order asc");
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function readActive() {
        $stmt = $this->getPdo()->prepare("SELECT * FROM LANGUAGE l WHERE l.active=1 ORDER BY l.picklist_order asc");
        $stmt->execute();
        return $stmt->fetchAll();
    }
    


    public function readByID($id) {
        $stmt = $this->getPdo()->prepare("SELECT * FROM LANGUAGE l WHERE l.language_id = :language_id");
        $stmt->bindValue('language_id', $id);
        $stmt->execute();
        return $stmt->fetch();
    }

    public function readLanguagesByCountry($country_id) {
        $stmt = $this->getPdo()->prepare("SELECT 
                l . *, cl.is_default, c.country_id, c.COUNTRY_DESC
            FROM
                LANGUAGE l
                    join
                COUNTRY_LANGUAGE cl ON (cl.language_id = l.language_id)
                    join
                COUNTRY c ON (cl.country_id = c.country_id)
            WHERE
                l.active = 1 and c.country_id = :country_id 
            UNION SELECT 
                l . *, 0, c.country_id, c.COUNTRY_DESC
            FROM
                LANGUAGE l
                    join
                COUNTRY c ON (l.language_id = 1)
            WHERE
                l.active = 1 and c.country_id = :country_id and not exists (select * from COUNTRY_LANGUAGE cl where cl.language_id =1 and cl.country_id = :country_id)
                                and c.country_id not in(

                select country_id from COUNTRY where country_desc in ('Germany',
                'Austria',
                'Portugal',
                'Spain',
                'France',
                'Italy',
                'Switzerland'))
            ");
        $stmt->bindValue('country_id', $country_id);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function update($item) {
        //NOP
    }

}
