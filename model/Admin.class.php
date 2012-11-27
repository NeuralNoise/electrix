<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Admin
 *
 * @author nancy
 */
class Admin 
{
    private $username;
    private $password;
    
    public function getUsername() {
        return $this->username;
    }

    public function setUsername($username) {
        $this->username = $username;
    }

    public function getPassword() {
        return $this->password;
    }

    public function setPassword($password) {
        $this->password = $password;
    }
   
    function __construct($username, $password) {
        $this->username = $username;
        $this->password = $password;
    }

}

?>
