<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of InvalidEmailException
 *
 * @author admin
 */
class InvalidEmailException extends Exception 
{
     public function __construct($message='Invalid Email address!') {
        parent::__construct($message, 204);
}
     public function __toString() {
        parent::__toString();
    }
    //put your code here
    //put your code here
}

?>
