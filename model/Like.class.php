<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Likes
 *
 * @author DJ
 */
class Like {
    private $type;
    private $itemId;
    private $profileId;
    
    public function getType() {
        return $this->type;
    }

    public function setType($type) {
        $this->type = $type;
    }

    public function getItemId() {
        return $this->itemId;
    }

    public function setItemId($itemId) {
        $this->itemId = $itemId;
    }

    public function getProfileId() {
        return $this->profileId;
    }

    public function setProfileId($profileId) {
        $this->profileId = $profileId;
    }

    /**
     *
     * @param String $type
     * @param Integer $itemId
     * @param Integer $profileId 
     */
    function __construct($type, $itemId, $profileId=null) {
        $this->type = $type;
        $this->itemId = $itemId;
        $this->profileId = $profileId;
    }
    
    public static $const_args = array(
                                    'type',
                                    'itemId',
                                    'profileId'
                                    );
}

?>
