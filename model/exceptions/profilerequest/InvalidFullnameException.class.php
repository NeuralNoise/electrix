<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of InvalidFullnameException
 *
 * @author admin
 */
class InvalidFullnameException extends Exception 
{
     public function __construct($message='Invalid Fullname!') {
        parent::__construct($message, 202);
}
     public function __toString() {
        parent::__toString();
    }
    //put your code here
}
?>
