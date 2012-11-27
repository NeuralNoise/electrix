<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Profile
 *
 * @author nancy
 */
class Profile 
{
    private $profileId;
    private $fullname;
    private $displayname;
    private $dob;
    private $email1;
    private $email2;
    private $phone1;
    private $phone2;
    private $currentLocation;
    private $contactAddress;
    private $notify;
    
    public function getProfileId() {
        return $this->profileId;
    }

    public function setProfileId($profileId) {
        $this->profileId = $profileId;
    }

    public function getFullname() {
        return $this->fullname;
    }

    public function setFullname($fullname) {
        $this->fullname = $fullname;
    }

    public function getDisplayname() {
        return $this->displayname;
    }

    public function setDisplayname($displayname) {
        $this->displayname = $displayname;
    }

    public function getDob() {
        return $this->dob;
    }

    public function setDob($dob) {
        $this->dob = $dob;
    }

    public function getEmail1() {
        return $this->email1;
    }

    public function setEmail1($email1) {
        $this->email1 = $email1;
    }

    public function getEmail2() {
        return $this->email2;
    }

    public function setEmail2($email2) {
        $this->email2 = $email2;
    }

    public function getPhone1() {
        return $this->phone1;
    }

    public function setPhone1($phone1) {
        $this->phone1 = $phone1;
    }

    public function getPhone2() {
        return $this->phone2;
    }

    public function setPhone2($phone2) {
        $this->phone2 = $phone2;
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
    public function getNotify() {
        return $this->notify;
    }
    public function setNotify($notify){
        $this->notify=$notify;
    }
    
    
    public static $const_args = array(
            'fullname', 
            'displayname',
            'dob',
            'email1',
            'email2',
            'phone1',
            'phone2',
            'currentLocation',
            'contactAddress',
            'notify',
            'profileId'
        );
    
    /**
     *
     * @param String $fullname
     * @param String $displayname
     * @param String $dob
     * @param String $email1
     * @param String $email2
     * @param Integer $phone1
     * @param Integer $phone2
     * @param String $currentLocation
     * @param String $contactAddress
     * @param Integer $notify
     * @param Integer $profileId 
     */
    function __construct($fullname='', $displayname='', $dob='', $email1='', $email2='', $phone1='', $phone2='', $currentLocation='', $contactAddress='', $notify='', $profileId=NULL) 
    {
        $this->profileId = $profileId;
        $this->fullname = $fullname;
        $this->displayname = $displayname;
        $this->dob = $dob;
        $this->email1 = $email1;
        $this->email2 = $email2;
        $this->phone1 = $phone1;
        $this->phone2 = $phone2;
        $this->currentLocation = $currentLocation;
        $this->contactAddress = $contactAddress;
        $this->notify = $notify;
    }
}
?>
