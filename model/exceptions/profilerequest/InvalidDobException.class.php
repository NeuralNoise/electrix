<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of InvalidDobException
 *
 * @author admin
 */
class InvalidDobException extends Exception 
{
     public function __construct($message='Invalid Dob!') 
{
        parent::__construct($message, 203);
}
     public function __toString() {
        parent::__toString();
    }
    //put your code here
    //put your code here
}

?>
