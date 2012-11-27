<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of InvalidUnivRegnNoException
 *
 * @author admin
 */
class InvalidUnivRegnNoException extends Exception
{
    public function __construct($message='Invalid University Registration No!') {
        parent::__construct($message, 201);
    }
    
    public function __toString() {
        parent::__toString();
    }
}

?>
