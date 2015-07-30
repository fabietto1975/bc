<?php
namespace baccarat\app\model;

use JsonSerializable;

class ContactBean implements JsonSerializable {

    const BIND_ERROR = 0;
    const BIND_SUCCESS = 1;
    const PRIVACY_COMMUNICATIONS = 'sendCommunications';
    const PRIVACY_ANALYSIS = 'analysis';
    const PRIVACY_PERSONALDATA = 'personalData';
    const PRIVACY_POLICY = 'privacyPolicy';
    const PRIVACY_TELEMARKETING = 'telemarketing';
    /* Brand Line enum */
    const BL_DIESEL = 0;
    const BL_PROPS = 1;
    const BL_BLACKGOLD = 2;
    const BL_55DSL = 3;
    const BL_LICENSE = 4;
    const BL_INTIMATE = 5;
    const BL_KID = 6;
    /* Product cat enum */
    const PC_M5POCKETS = 0;
    const PC_F5POCKETS = 1;
    const PC_MDIESEL = 2;
    const PC_FDIESEL = 3;
    const PC_SPECIALPRJ = 4;
    const PC_SPAREPARTS = 5;
    const PC_FTWDIESEL = 6;
    const PC_BAGSDIESEL = 7;
    const PC_WALLETSGADGETS = 8;

    private $contactID;
    private $firstName;
    private $middleName;
    private $lastName;
    private $title;
    private $email;
    private $birthDay;
    private $country;
    private $countryDesc;
    private $stateProvince;
    private $stateProvinceDesc;
    private $townCity;
    private $townCityDesc;
    private $zipCode;
    private $address;
    private $mobile;
    private $homePhone;
    private $preferredLanguage;
    private $contactMediaPref;
    public $privacyOptions = array();
    private $brandLineInterested = array();
    private $productCategoryInterested = array();
    private $notes;
    private $barcodeImagePath;
    private $barcodeNumber;
    private $lastEditedBy;
    private $createdBy;
    private $countryStore;
    private $firstNameLocale;
    private $middleNameLocale;
    private $lastNameLocale;
    private $barcodeLocal;
    private $cardLocal;
    private $storeId;
    private $storeDesc;
    private $validEmail;
    private $unsubscribeDate;
    private $unsubscribeCampaign;
    private $unsubscribePackage;
    private $unsubscribeCampaignTooManyCommunications;
    private $unsubscribeCampaignBadExpInstore;
    private $unsubscribeCampaignBadExpOnline;
    private $unsubscribeCampaignNotRelevant;
    private $unsubscribeCampaignOtherReason;
    private $unsubscribeCampaignOtherReasonText;

    public function __construct() {
        $this->privacyOptions [self::PRIVACY_COMMUNICATIONS] = "0";
        $this->privacyOptions [self::PRIVACY_ANALYSIS] = "0";
        $this->privacyOptions [self::PRIVACY_PERSONALDATA] = "0";
        $this->privacyOptions [self::PRIVACY_POLICY] = "0";
        $this->privacyOptions [self::PRIVACY_TELEMARKETING] = "0";

        $this->brandLineInterested [self::BL_55DSL] = "0";
        $this->brandLineInterested [self::BL_BLACKGOLD] = "0";
        $this->brandLineInterested [self::BL_DIESEL] = "0";
        $this->brandLineInterested [self::BL_INTIMATE] = "0";
        $this->brandLineInterested [self::BL_KID] = "0";
        $this->brandLineInterested [self::BL_LICENSE] = "0";
        $this->brandLineInterested [self::BL_PROPS] = "0";

        $this->productCategoryInterested [self::PC_BAGSDIESEL] = "0";
        $this->productCategoryInterested [self::PC_F5POCKETS] = "0";
        $this->productCategoryInterested [self::PC_FDIESEL] = "0";
        $this->productCategoryInterested [self::PC_FTWDIESEL] = "0";
        $this->productCategoryInterested [self::PC_M5POCKETS] = "0";
        $this->productCategoryInterested [self::PC_MDIESEL] = "0";
        $this->productCategoryInterested [self::PC_SPAREPARTS] = "0";
        $this->productCategoryInterested [self::PC_WALLETSGADGETS] = "0";
        $this->productCategoryInterested [self::PC_SPECIALPRJ] = "0";

        $this->validEmail = "1";
    }

    public function bindResults($result) {

        include (APP_ROOT . '/config/vtiger-customfields.php');
        include (APP_ROOT . '/config/config-local.php');
        //print_r($result);
        if (count($result) < 1)
            return self::BIND_ERROR;
        $result ['birthday'] = substr($result ['birthday'], 5);
        $this->setContactID($result ['id']);
        $this->setTitle($result ['title']);
        $this->setFirstName($result ['firstname']);
        $this->setMiddleName($result [$cf_middleName]);
        $this->setLastName($result ['lastname']);
        $this->setEmail($result ['email']);
        $this->setBirthDay($result ['birthday']);
        $this->setMobile($result ['mobile']);
        $this->setHomePhone($result ['homephone']);
        $this->setPreferredLanguage($result [$cf_preferredLanguage]);
        $this->setCountry($result ['mailingcountry']);
        $this->setStateProvince($result ['mailingstate']);
        $this->setTownCity($result ['mailingcity']);
        $this->setZipCode($result ['mailingzip']);
        $this->setAddress($result ['mailingstreet']);
        $this->setContactMediaPref($result [$cf_contactMediaPref]);
        $this->setNotes($result [$cf_notes]);
        $this->setBarcodeNumber($result [$cf_barcodeNumber]);
        $this->setBarcodeImagePath($barcodeImagesURI . $this->getBarcodeNumber() . ".jpg");
        $this->setLastEditedBy($result [$cf_lastEditedBy]);
        $this->setCreatedBy($result [$cf_createdBy]);
        $this->setFirstNameLocale($result [$cf_firstname_locale]);
        $this->setMiddleNameLocale($result [$cf_middlename_locale]);
        $this->setLastNameLocale($result [$cf_lastname_locale]);

        $this->setBarcodeLocal($result [$cf_barcodeLocal]);
        $this->setCardLocal($result [$cf_cardLocal]);

        $this->setStoreId($result [$cf_storeId]);
        $this->setStoreDesc($result [$cf_storeDesc]);
        $this->setValidEmail($result [$cf_validEmail]);

        // privacy opts
        $this->privacyOptions [self::PRIVACY_COMMUNICATIONS] = $result [$cf_privacyCommunications];
        $this->privacyOptions [self::PRIVACY_ANALYSIS] = $result [$cf_privacyAnalysis];
        $this->privacyOptions [self::PRIVACY_PERSONALDATA] = $result [$cf_privacyPersonalData];
        $this->privacyOptions [self::PRIVACY_POLICY] = $result [$cf_privacyPolicy];

        // if (array_key_exists(self::PRIVACY_TELEMARKETING, $result))
        $this->privacyOptions [self::PRIVACY_TELEMARKETING] = $result [$cf_privacyTelemarketing];

        $this->brandLineInterested [self::BL_55DSL] = $result [$cf_55Dsl];
        $this->brandLineInterested [self::BL_BLACKGOLD] = $result [$cf_blackGold];
        $this->brandLineInterested [self::BL_DIESEL] = $result [$cf_diesel];
        $this->brandLineInterested [self::BL_INTIMATE] = $result [$cf_intimate];
        $this->brandLineInterested [self::BL_KID] = $result [$cf_kid];
        $this->brandLineInterested [self::BL_LICENSE] = $result [$cf_license];
        $this->brandLineInterested [self::BL_PROPS] = $result [$cf_props];

        $this->productCategoryInterested [self::PC_BAGSDIESEL] = $result [$cf_bagsDiesel];
        $this->productCategoryInterested [self::PC_F5POCKETS] = $result [$cf_f5Pockets];
        $this->productCategoryInterested [self::PC_FDIESEL] = $result [$cf_fDiesel];
        $this->productCategoryInterested [self::PC_FTWDIESEL] = $result [$cf_ftwDiesel];
        $this->productCategoryInterested [self::PC_M5POCKETS] = $result [$cf_m5Pockets];
        $this->productCategoryInterested [self::PC_MDIESEL] = $result [$cf_mDiesel];
        $this->productCategoryInterested [self::PC_SPAREPARTS] = $result [$cf_spareParts];
        $this->productCategoryInterested [self::PC_WALLETSGADGETS] = $result [$cf_walletsGadgets];
        $this->productCategoryInterested [self::PC_SPECIALPRJ] = $result [$cf_specialPrj];

        $this->unsubscribeDate = $result[$cf_unsubscribeDate];
        /*
        print_r('#'.$cf_unsubscribeDate.'#');
        print_r('#'.$result[$cf_unsubscribeDate].'#');
        print_r($result);
        */
        $this->unsubscribeCampaign = $result[$cf_unsubscribeCampaign];
        $this->unsubscribePackage = $result[$cf_unsubscribeCampaignCellPackage];
        $this->unsubscribeCampaignTooManyCommunications = $result[$cf_unsubscribeCampaignTooManyCommunications];
        $this->unsubscribeCampaignBadExpInstore = $result[$cf_unsubscribeCampaignBadExpInstore];
        $this->unsubscribeCampaignBadExpOnline = $result[$cf_unsubscribeCampaignBadExpOnline];
        $this->unsubscribeCampaignNotRelevant = $result[$cf_unsubscribeCampaignNotRelevant];
        $this->unsubscribeCampaignOtherReason = $result[$cf_unsubscribeCampaignOtherReason];
        $this->unsubscribeCampaignOtherReasonText = $result[$cf_unsubscribeCampaignOtherReasonText];

        return self::BIND_SUCCESS;
    }

    /**
     * Bind Contact Bean to VTiger Contact Array
     *
     * @return Associative Array
     */
    public function bindToVTigerEntity() {

        include (APP_ROOT . '/config/vtiger-customfields.php');

        $result = array();

        $result ['id'] = $this->getContactID();
        $result ['title'] = $this->getTitle();
        $result ['firstname'] = $this->getFirstName();
        //$result [$cf_middleName] = $this->getMiddleName();
        $result ['lastname'] = $this->getLastName();
        $result ['email'] = $this->getEmail();
        $result ['birthday'] = $this->getBirthDay();
        $result ['mobile'] = $this->getMobile();
        $result ['homephone'] = $this->getHomePhone();
        //$result [$cf_preferredLanguage] = $this->getPreferredLanguage();
        $result ['mailingcountry'] = $this->getCountry();
        $result ['mailingstate'] = $this->getStateProvince();
        $result ['mailingcity'] = $this->getTownCity();
        $result ['mailingzip'] = $this->getZipCode();
        $result ['mailingstreet'] = $this->getAddress();
        /*
        $result [$cf_contactMediaPref] = $this->getContactMediaPref();
        $result [$cf_notes] = $this->getNotes();
        $result [$cf_barcodeNumber] = $this->getBarcodeNumber();
        $result [$cf_lastEditedBy] = $this->getLastEditedBy();
        $result [$cf_createdBy] = $this->getCreatedBy();
        $result [$cf_countryStore] = $this->getCountryStore();
        $result [$cf_firstname_locale] = $this->getFirstNameLocale();
        $result [$cf_middlename_locale] = $this->getMiddleNameLocale();
        $result [$cf_lastname_locale] = $this->getLastNameLocale();

        $result [$cf_privacyCommunications] = $this->privacyOptions [self::PRIVACY_COMMUNICATIONS];
        $result [$cf_privacyAnalysis] = $this->privacyOptions [self::PRIVACY_ANALYSIS];
        $result [$cf_privacyPersonalData] = $this->privacyOptions [self::PRIVACY_PERSONALDATA];
        $result [$cf_privacyPolicy] = $this->privacyOptions [self::PRIVACY_POLICY];
        $result [$cf_privacyTelemarketing] = $this->privacyOptions [self::PRIVACY_TELEMARKETING];



        $result [$cf_55Dsl] = $this->brandLineInterested [self::BL_55DSL];
        $result [$cf_blackGold] = $this->brandLineInterested [self::BL_BLACKGOLD];
        $result [$cf_diesel] = $this->brandLineInterested [self::BL_DIESEL];
        $result [$cf_intimate] = $this->brandLineInterested [self::BL_INTIMATE];
        $result [$cf_kid] = $this->brandLineInterested [self::BL_KID];
        $result [$cf_license] = $this->brandLineInterested [self::BL_LICENSE];
        $result [$cf_props] = $this->brandLineInterested [self::BL_PROPS];

        $result [$cf_bagsDiesel] = $this->productCategoryInterested [self::PC_BAGSDIESEL];
        $result [$cf_f5Pockets] = $this->productCategoryInterested [self::PC_F5POCKETS];
        $result [$cf_fDiesel] = $this->productCategoryInterested [self::PC_FDIESEL];
        $result [$cf_ftwDiesel] = $this->productCategoryInterested [self::PC_FTWDIESEL];
        $result [$cf_m5Pockets] = $this->productCategoryInterested [self::PC_M5POCKETS];
        $result [$cf_mDiesel] = $this->productCategoryInterested [self::PC_MDIESEL];
        $result [$cf_spareParts] = $this->productCategoryInterested [self::PC_SPAREPARTS];
        $result [$cf_walletsGadgets] = $this->productCategoryInterested [self::PC_WALLETSGADGETS];
        $result [$cf_specialPrj] = $this->productCategoryInterested [self::PC_SPECIALPRJ];
        */
        /* Extra infos about country/state/city in extended mode */
        /*
        $result [$cf_countryExt] = $this->getCountryDesc();
        $result [$cf_stateExt] = $this->getStateProvinceDesc();
        $result [$cf_cityExt] = $this->getTownCityDesc();

        $result [$cf_barcodeLocal] = $this->getBarcodeLocal();
        $result [$cf_cardLocal] = $this->getCardLocal();

        $result [$cf_storeId] = $this->getStoreId();
        $result [$cf_storeDesc] = $this->getStoreDesc();
        $result [$cf_validEmail] = $this->getValidEmail();


        $result[$cf_unsubscribeDate] = $this->unsubscribeDate;
        $result[$cf_unsubscribeCampaign] = $this->unsubscribeCampaign;
        $result[$cf_unsubscribeCampaignCellPackage] = $this->unsubscribePackage;

        $result[$cf_unsubscribeCampaignTooManyCommunications] = $this->unsubscribeCampaignTooManyCommunications;
        $result[$cf_unsubscribeCampaignBadExpInstore] = $this->unsubscribeCampaignBadExpInstore;
        $result[$cf_unsubscribeCampaignBadExpOnline] = $this->unsubscribeCampaignBadExpOnline;
        $result[$cf_unsubscribeCampaignNotRelevant] = $this->unsubscribeCampaignNotRelevant;
        $result[$cf_unsubscribeCampaignOtherReason] = $this->unsubscribeCampaignOtherReason;
        $result[$cf_unsubscribeCampaignOtherReasonText] = $this->unsubscribeCampaignOtherReasonText;
        */
        return $result;
    }

    public function allowComunications() {
        return $this->privacyOptions [self::PRIVACY_COMMUNICATIONS];
    }

    public function allowAnalysis() {
        return $this->privacyOptions [self::PRIVACY_ANALYSIS];
    }

    public function allowPersonalData() {
        return $this->privacyOptions [self::PRIVACY_PERSONALDATA];
    }

    public function allowPrivacyPolicy() {
        return $this->privacyOptions [self::PRIVACY_POLICY];
    }

    public function allowTelemarketing() {
        return $this->privacyOptions [self::PRIVACY_TELEMARKETING];
    }

    public function is55Dsl() {
        return $this->brandLineInterested [self::BL_55DSL];
    }

    public function isBlackGold() {
        return $this->brandLineInterested [self::BL_BLACKGOLD];
    }

    public function isDiesel() {
        return $this->brandLineInterested [self::BL_DIESEL];
    }

    public function isIntimate() {
        return $this->brandLineInterested [self::BL_INTIMATE];
    }

    public function isKid() {
        return $this->brandLineInterested [self::BL_KID];
    }

    public function isLicense() {
        return $this->brandLineInterested [self::BL_LICENSE];
    }

    public function isProps() {
        return $this->brandLineInterested [self::BL_PROPS];
    }

    public function isBagsDiesel() {
        return $this->productCategoryInterested [self::PC_BAGSDIESEL];
    }

    public function isF5Pockets() {
        return $this->productCategoryInterested [self::PC_F5POCKETS];
    }

    public function isFDiesel() {
        return $this->productCategoryInterested [self::PC_FDIESEL];
    }

    public function isFtwDiesel() {
        return $this->productCategoryInterested [self::PC_FTWDIESEL];
    }

    public function isM5Pockets() {
        return $this->productCategoryInterested [self::PC_M5POCKETS];
    }

    public function isMDiesel() {
        return $this->productCategoryInterested [self::PC_MDIESEL];
    }

    public function isSpareparts() {
        return $this->productCategoryInterested [self::PC_SPAREPARTS];
    }

    public function isWalletsGadgets() {
        return $this->productCategoryInterested [self::PC_WALLETSGADGETS];
    }

    public function isSpecialPrj() {
        return $this->productCategoryInterested [self::PC_SPECIALPRJ];
    }

    public function getContactID() {
        return $this->contactID;
    }

    public function setContactID($contactID) {
        if(!(strpos($contactID, 'x')===false)){
            $contactID = explode('x', $contactID)[1];
        }
        $this->contactID = $contactID;
        return $this;
    }

    public function getFirstName() {
        return $this->firstName;
    }

    public function setFirstName($firstName) {
        $this->firstName = $firstName;
        return $this;
    }

    public function getMiddleName() {
        return $this->middleName;
    }

    public function setMiddleName($middleName) {
        $this->middleName = $middleName;
        return $this;
    }

    public function getLastName() {
        return $this->lastName;
    }

    public function setLastName($lastName) {
        $this->lastName = $lastName;
        return $this;
    }

    public function getTitle() {
        return $this->title;
    }

    public function setTitle($title) {
        $this->title = $title;
        return $this;
    }

    public function getEmail() {
        return $this->email;
    }

    public function setEmail($email) {
        $this->email = $email;
        return $this;
    }

    public function getBirthDay() {
        /*
        if(strlen($birthDay) > 5){
            if(is_numeric(substr($birthDay, 0, 4))){
                $birthDay = substr($birthDay, 5);
            }
        } 
        */  
        return $this->birthDay;
    }

    public function setBirthDay($birthDay) {
        $this->birthDay = $birthDay;
        return $this;
    }

    public function getCountry() {
        return $this->country;
    }

    public function setCountry($country) {
        $this->country = $country;
        return $this;
    }

    public function getStateProvince() {
        return $this->stateProvince;
    }

    public function setStateProvince($stateProvince) {
        $this->stateProvince = $stateProvince;
        return $this;
    }

    public function getTownCity() {
        return $this->townCity;
    }

    public function setTownCity($townCity) {
        $this->townCity = $townCity;
        return $this;
    }

    public function getZipCode() {
        return $this->zipCode;
    }

    public function setZipCode($zipCode) {
        $this->zipCode = $zipCode;
        return $this;
    }

    public function getAddress() {
        return $this->address;
    }

    public function setAddress($address) {
        $this->address = $address;
        return $this;
    }

    public function getMobile() {
        return $this->mobile;
    }

    public function setMobile($mobile) {
        $this->mobile = $mobile;
        return $this;
    }

    public function getHomePhone() {
        return $this->homePhone;
    }

    public function setHomePhone($homePhone) {
        $this->homePhone = $homePhone;
        return $this;
    }

    public function getPreferredLanguage() {
        return $this->preferredLanguage;
    }

    public function setPreferredLanguage($preferredLanguage) {
        $this->preferredLanguage = $preferredLanguage;
    }

    public function getContactMediaPref() {
        return $this->contactMediaPref;
    }

    public function setContactMediaPref($contactMediaPref) {
        $this->contactMediaPref = $contactMediaPref;
    }

    public function getPrivacyOtions($privacyField) {
        return $this->privacyOptions [$privacyField];
    }

    function getUnsubscribeCampaignTooManyCommunications() {
        return $this->unsubscribeCampaignTooManyCommunications;
    }

    function getUnsubscribeCampaignBadExpInstore() {
        return $this->unsubscribeCampaignBadExpInstore;
    }

    function getUnsubscribeCampaignBadExpOnline() {
        return $this->unsubscribeCampaignBadExpOnline;
    }

    function getUnsubscribeCampaignNotRelevant() {
        return $this->unsubscribeCampaignNotRelevant;
    }

    function getUnsubscribeCampaignOtherReason() {
        return $this->unsubscribeCampaignOtherReason;
    }

    function getUnsubscribeCampaignOtherReasonText() {
        return $this->unsubscribeCampaignOtherReasonText;
    }

    function setUnsubscribeCampaignTooManyCommunications($unsubscribeCampaignTooManyCommunications) {
        $this->unsubscribeCampaignTooManyCommunications = $unsubscribeCampaignTooManyCommunications;
    }

    function setUnsubscribeCampaignBadExpInstore($unsubscribeCampaignBadExpInstore) {
        $this->unsubscribeCampaignBadExpInstore = $unsubscribeCampaignBadExpInstore;
    }

    function setUnsubscribeCampaignBadExpOnline($unsubscribeCampaignBadExpOnline) {
        $this->unsubscribeCampaignBadExpOnline = $unsubscribeCampaignBadExpOnline;
    }

    function setUnsubscribeCampaignNotRelevant($unsubscribeCampaignNotRelevant) {
        $this->unsubscribeCampaignNotRelevant = $unsubscribeCampaignNotRelevant;
    }

    function setUnsubscribeCampaignOtherReason($unsubscribeCampaignOtherReason) {
        $this->unsubscribeCampaignOtherReason = $unsubscribeCampaignOtherReason;
    }

    function setUnsubscribeCampaignOtherReasonText($unsubscribeCampaignOtherReasonText) {
        $this->unsubscribeCampaignOtherReasonText = $unsubscribeCampaignOtherReasonText;
    }

    public function setPrivacyOptions($privacyOptions) {
        // consenso 4) I have read and accepted the Diesel Privacy Policy -> sempre flaggato!
        $this->privacyOptions [self::PRIVACY_POLICY] = "1";
        // $privacyOtions -> array valorizzato solo se viene flaggato almeno un check, altrimenti NULL

        if ($privacyOptions == NULL || count($privacyOptions) == 0) {
            return;
        }
        $this->privacyOptions [self::PRIVACY_ANALYSIS] = $privacyOptions[self::PRIVACY_ANALYSIS];
        $this->privacyOptions [self::PRIVACY_COMMUNICATIONS] = $privacyOptions[self::PRIVACY_COMMUNICATIONS];
        $this->privacyOptions [self::PRIVACY_PERSONALDATA] = $privacyOptions[self::PRIVACY_PERSONALDATA];
        $this->privacyOptions [self::PRIVACY_TELEMARKETING] = $privacyOptions[self::PRIVACY_TELEMARKETING];
    }

    public function setBrandLineInterested($brandLineInterested) {
        if ($brandLineInterested == NULL)
            return;

        foreach ($brandLineInterested as $option) {
            if ($option == self::BL_55DSL)
                $this->brandLineInterested [self::BL_55DSL] = "1";

            else if ($option == self::BL_BLACKGOLD)
                $this->brandLineInterested [self::BL_BLACKGOLD] = "1";

            else if ($option == self::BL_DIESEL)
                $this->brandLineInterested [self::BL_DIESEL] = "1";

            else if ($option == self::BL_INTIMATE)
                $this->brandLineInterested [self::BL_INTIMATE] = "1";

            else if ($option == self::BL_KID)
                $this->brandLineInterested [self::BL_KID] = "1";

            else if ($option == self::BL_LICENSE)
                $this->brandLineInterested [self::BL_LICENSE] = "1";

            else if ($option == self::BL_PROPS)
                $this->brandLineInterested [self::BL_PROPS] = "1";
        }
    }

    public function setProductCategoryInterested($productCategoryInterested) {
        if ($productCategoryInterested == NULL)
            return;

        foreach ($productCategoryInterested as $option) {
            if ($option == self::PC_BAGSDIESEL)
                $this->productCategoryInterested [self::PC_BAGSDIESEL] = "1";

            else if ($option == self::PC_F5POCKETS)
                $this->productCategoryInterested [self::PC_F5POCKETS] = "1";

            else if ($option == self::PC_FDIESEL)
                $this->productCategoryInterested [self::PC_FDIESEL] = "1";

            else if ($option == self::PC_FTWDIESEL)
                $this->productCategoryInterested [self::PC_FTWDIESEL] = "1";

            else if ($option == self::PC_M5POCKETS)
                $this->productCategoryInterested [self::PC_M5POCKETS] = "1";

            else if ($option == self::PC_MDIESEL)
                $this->productCategoryInterested [self::PC_MDIESEL] = "1";

            else if ($option == self::PC_SPAREPARTS)
                $this->productCategoryInterested [self::PC_SPAREPARTS] = "1";

            else if ($option == self::PC_SPECIALPRJ)
                $this->productCategoryInterested [self::PC_SPECIALPRJ] = "1";

            else if ($option == self::PC_WALLETSGADGETS)
                $this->productCategoryInterested [self::PC_WALLETSGADGETS] = "1";
        }
    }

    public function getNotes() {
        return $this->notes;
    }

    public function setNotes($notes) {
        $this->notes = $notes;
    }

    public function getBarcodeImagePath() {
        return $this->barcodeImagePath;
    }

    public function setBarcodeImagePath($barcodeImagePath) {
        $this->barcodeImagePath = $barcodeImagePath;
        return $this;
    }

    public function getBarcodeNumber() {
        return $this->barcodeNumber;
    }

    public function setBarcodeNumber($barcodeNumber) {
        $this->barcodeNumber = $barcodeNumber;
        return $this;
    }

    public function getLastEditedBy() {
        return $this->lastEditedBy;
    }

    public function setLastEditedBy($lastEditedBy) {
        $this->lastEditedBy = $lastEditedBy;
        return $this;
    }

    public function getCountryStore() {
        return $this->countryStore;
    }

    public function setCountryStore($countryStore) {
        $this->countryStore = $countryStore;
        return $this;
    }

    public function getFirstNameLocale() {
        return $this->firstNameLocale;
    }

    public function setFirstNameLocale($firstNameLocale) {
        $this->firstNameLocale = $firstNameLocale;
        return $this;
    }

    public function getMiddleNameLocale() {
        return $this->middleNameLocale;
    }

    public function setMiddleNameLocale($middleNameLocale) {
        $this->middleNameLocale = $middleNameLocale;
        return $this;
    }

    public function getLastNameLocale() {
        return $this->lastNameLocale;
    }

    public function setLastNameLocale($lastNameLocale) {
        $this->lastNameLocale = $lastNameLocale;
        return $this;
    }

    public function getCreatedBy() {
        return $this->createdBy;
    }

    public function setCreatedBy($createdBy) {
        $this->createdBy = $createdBy;
        return $this;
    }

    public function getBarcodeLocal() {
        return $this->barcodeLocal;
    }

    public function setBarcodeLocal($barcodeLocal) {
        $this->barcodeLocal = $barcodeLocal;
        return $this;
    }

    public function getCardLocal() {
        return $this->cardLocal;
    }

    public function setCardLocal($cardLocal) {
        $this->cardLocal = $cardLocal;
        return $this;
    }

    public function getStoreId() {
        return $this->storeId;
    }

    public function setStoreId($storeId) {
        $this->storeId = $storeId;
        return $this;
    }

    public function getStoreDesc() {
        return $this->storeDesc;
    }

    public function setStoreDesc($storeDesc) {
        $this->storeDesc = $storeDesc;
        return $this;
    }

    public function getValidEmail() {
        return $this->validEmail;
    }

    public function setValidEmail($validEmail) {
        $this->validEmail = $validEmail;
        return $this;
    }

    public function missingInformation() {
        if ($this->getFirstName() == '' && $this->getLastName() == '' && $this->getBirthDay() == '') {
            return true;
        } else {
            return false;
        }
    }

    function getCountryDesc() {
        return $this->countryDesc;
    }

    function setCountryDesc($countryDesc) {
        $this->countryDesc = $countryDesc;
    }

    function getStateProvinceDesc() {
        return $this->stateProvinceDesc;
    }

    function setStateProvinceDesc($stateProvinceDesc) {
        $this->stateProvinceDesc = $stateProvinceDesc;
    }

    function getTownCityDesc() {
        return $this->townCityDesc;
    }

    function setTownCityDesc($townCityDesc) {
        $this->townCityDesc = $townCityDesc;
    }

    function getUnsubscribeDate() {
        return $this->unsubscribeDate;
    }

    function getUnsubscribeCampaign() {
        return $this->unsubscribeCampaign;
    }

    function setUnsubscribeCampaign($unsubscribeCampaign) {
        $this->unsubscribeCampaign = $unsubscribeCampaign;
    }

    function getUnsubscribePackage() {
        return $this->unsubscribePackage;
    }

    function setUnsubscribeDate($unsubscribeDate) {
        $this->unsubscribeDate = $unsubscribeDate;
    }

    function setUnsubscribePackage($unsubscribePackage) {
        $this->unsubscribePackage = $unsubscribePackage;
    }

    //From JsonSerializable interface 
    public function jsonSerialize() {
        //return get_class_vars(get_class($this)); 
        return [
            'contactID' => $this->contactID,
            'firstName' => $this->firstName,
            'middleName' => $this->middleName,
            'lastName' => $this->lastName,
            'title' => $this->title,
            'email' => $this->email,
            'birthDay' => $this->birthDay,
            'country' => $this->country,
            'countryDesc' => $this->countryDesc,
            'stateProvince' => $this->stateProvince,
            'stateProvinceDesc' => $this->stateProvinceDesc,
            'townCity' => $this->townCity,
            'townCityDesc' => $this->townCityDesc,
            'zipCode' => $this->zipCode,
            'address' => $this->address,
            'mobile' => $this->mobile,
            'homePhone' => $this->homePhone,
            'preferredLanguage' => $this->preferredLanguage,
            'contactMediaPref' => $this->contactMediaPref,
            'privacyOptions' => $this->privacyOptions,
            'brandLineInterested' => $this->brandLineInterested,
            'productCategoryInterested' => $this->productCategoryInterested,
            'notes' => $this->notes,
            'barcodeImagePath' => $this->barcodeImagePath,
            'barcodeNumber' => $this->barcodeNumber,
            'lastEditedBy' => $this->lastEditedBy,
            'createdBy' => $this->createdBy,
            'countryStore' => $this->countryStore,
            'firstNameLocale' => $this->firstNameLocale,
            'middleNameLocale' => $this->middleNameLocale,
            'lastNameLocale' => $this->lastNameLocale,
            'barcodeLocal' => $this->barcodeLocal,
            'cardLocal' => $this->cardLocal,
            'storeId' => $this->storeId,
            'storeDesc' => $this->storeDesc,
            'validEmail' => $this->validEmail,
            'missingInformation' => $this->missingInformation(),
            'unsubscribeDate' => $this->unsubscribeDate,
            'unsubscribeCampaign' => $this->unsubscribeCampaign,
            'unsubscribePackage' => $this->unsubscribePackage,
            'unsubscribeCampaignTooManyCommunications' => $this->unsubscribeCampaignTooManyCommunications,
            'unsubscribeCampaignBadExpInstore' => $this->unsubscribeCampaignBadExpInstore,
            'unsubscribeCampaignBadExpOnline' => $this->unsubscribeCampaignBadExpOnline,
            'unsubscribeCampaignNotRelevant' => $this->unsubscribeCampaignNotRelevant,
            'unsubscribeCampaignOtherReason' => $this->unsubscribeCampaignOtherReason,
            'unsubscribeCampaignOtherReasonText' => $this->unsubscribeCampaignOtherReasonText
        ];
    }

}
