<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of InvalidCurrentLocation
 *
 * @author admin
 */
class InvalidCurrentLocation extends Exception
{
    public function __construct($message='Invalid Current Location!') {
        parent::__construct($message, 206);
    }  
    public function __toString() {
        parent::__toString();
    }
    //put your code here
}
?>
