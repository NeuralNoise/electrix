<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of InvalidContactAddress
 *
 * @author admin
 */
class InvalidContactAddress extends Exception
{
    public function __construct($message='Contact Address is too short') {
        parent::__construct($message, 207);
    }  
    public function __toString() {
        parent::__toString();

    //put your code here
 }
}

?>
