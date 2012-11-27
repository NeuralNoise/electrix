<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Login
 *
 * @author nancy
 */
class Login 
{
    private $username;
    private $password;
    private $profileId;
    
    public function getUsername() {
        return $this->username;
    }

    public function setUsername($username) {
        $this->username = $username;
    }

    public function getPassword() {
        return $this->password;
    }

    public function setPasswd($password) {
        $this->password = $password;
    }

    public function getProfileId() {
        return $this->profileId;
    }

    public function setProfileId($profileId) {
        $this->profileId = $profileId;
    }

    /**
     *
     * @param Integer $username
     * @param String $password
     * @param Integer $profileId 
     */
    function __construct($username='', $password='', $profileId=null) {
        $this->username = $username;
        $this->password = $password;
        $this->profileId = $profileId;
    }

}

?>
