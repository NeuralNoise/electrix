
<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Profilerequest
 *
 * @author nancy
 */
class Profilerequest 
{
    private $univregnno;
    private $fullname;
    private $dob;
    private $email;
    private $phone;
    private $currentLocation;
    private $contactAddress;
    
    
    public function getUnivregnno() {
        return $this->univregnno;
    }

    public function setUnivregnno($univregnno) {
        $this->univregnno = $univregnno;
    }

    public function getFullname() {
        return $this->fullname;
    }

    public function setFullname($fullname) {
        $this->fullname = $fullname;
    }

    public function getdob() {
        return $this->dob;
    }

    public function setdob($dob) {
        $this->dob = $dob;
    }

    public function getEmail() {
        return $this->email;
    }

    public function setEmail($email) {
        $this->email = $email;
    }

    public function getPhone() {
        return $this->phone;
    }

    public function setPhone($phone) {
        $this->phone = $phone;
    }

    public function getCurrentLocation() {
        return $this->currentLocation;
    }

    public function setCurrentLocation($currentLocation) {
        $this->currentLocation = $currentLocation;
    }

    public function getContactAddress() {
        return $this->contactAddress;
    }

    public function setContactAddress($contactAddress) {
        $this->contactAddress = $contactAddress;
    }
    
    /**
     *
     * @param Integer $univregnno
     * @param String $fullname
     * @param String $dob
     * @param String $email
     * @param Integer $phone
     * @param String $currentLocation
     * @param String $contactAddress 
     */
    function __construct($univregnno='', $fullname='', $dob='', $email='', $phone='', $currentLocation='', $contactAddress='') {
        $this->univregnno = $univregnno;
        $this->fullname = $fullname;
        $this->dob = $dob;
        $this->email = $email;
        $this->phone = $phone;
        $this->currentLocation = $currentLocation;
        $this->contactAddress = $contactAddress;
    }
     
    /**
     *
     * @param Integer $univregnno 
     * @throws InvalidUnivRegnNoException
     */
    public static function validateUniversityRegnNo($univregnno)
    {
        loadException('profilerequest/InvalidUnivRegnNoException');
        
        if(strlen($univregnno) < 10)
            throw new InvalidUnivRegnNoException('Invalid University Regn No: Should be minimum 10 digits!');
        
        if(strlen($univregnno) > 15)
            throw new InvalidUnivRegnNoException('Invalid University Regn No: Cannot exceed 15 digits!');
        
        if(!preg_match('/^[0-9]+$/', $univregnno))
            throw new InvalidUnivRegnNoException('Invalid University Regn No: Only numeric digits are allowed!');
    }
    
    /**
     *
     * @param String $fullname 
     * @throws InvalidFullnameException
     */
    public function validateFullname($fullname)
    {
        loadException('profilerequest/InvalidFullnameException');
        
        if(!preg_match('/^[A-Za-z\.\' ]{6,64}$/',$fullname))
                throw new InvalidFullnameException('Invalid fullname: Only alphabets are allowed');
    }
    
    /**
     *
     * @param String $dateofbirth 
     * @throws InvalidDobException
     */
    public function validateDob($dob)
    {
        loadException('profilerequest/InvalidDobException');
        
        if(!preg_match('/^[0-9]{4}-[0-9]{1,2}-[0-9]{1,2}$/',$dob))
                throw new InvalidDobException('Invalid Dob');
    }
   /**
     *
     * @param string $email 
     * @throws InvalidEmailException
     */
    public function validateEmail($email)
    {
        loadException('profilerequest/InvalidEmailException');
        if(!filter_var($email,FILTER_VALIDATE_EMAIL))
            throw new InvalidEmailException('Invalid Email format');            
    }
   /**
     *
     * @param Integer $phone 
     * @throws InvalidPhoneException
     */
    public function validatePhone($phone)
    {
        loadException('profilerequest/InvalidPhoneException');
        
        if(!preg_match('/^[0-9]{2,3}[0-9]{8,10}$/',$phone))
                throw new InvalidPhoneException('Invalid phone number');
    }
    /**
     *
     * @param string $currentLocation 
     * @throws InvalidCurrentLocationException
     */
    public function validateCurrentlocation($currentLocation)
    {
        loadException('profilerequest/InvalidCurrentLocation');
        
        if(!preg_match('/^[A-Za-z]{3,20}$/',$currentLocation))
                throw new InvalidCurrentLocation('Invalid Location');
    }
    /**
     *
     * @param String $contactAddress 
     * @throws InvalidContactAddressException
     */
    public function validateContactAddress($contactAddress)
    {
        loadException('profilerequest/InvalidContactAddress');
        
        if(strlen($contactAddress)<10)
            throw new InvalidContactAddress('Address too Short');              
    }
}
?>
