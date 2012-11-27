<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of BirthdayVO
 *
 * @author DJ
 */
class BirthdayVO 
{
    private $displayname;
    
    public function getDisplayname() {
        return $this->displayname;
    }

    public function setDisplayname($displayname) {
        $this->displayname = $displayname;
    }

    function __construct($displayname) {
        $this->displayname = $displayname;
    }
    
    public static $constArgs = array('displayname');
}
?>
