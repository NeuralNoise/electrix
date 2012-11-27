<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of InvalidPhoneException
 *
 * @author admin
 */
class InvalidPhoneException extends Exception
{
    public function __construct($message='Invalid Phone number') {
        parent::__construct($message, 205);
    }  
    public function __toString() {
        parent::__toString();
    }    //put your code here
}
?>
