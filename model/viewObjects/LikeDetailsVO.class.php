<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of GetLikesVO
 *
 * @author DJ
 */
class LikeDetailsVO {
    private $profileId;
    private $displayname;
    
    public function getProfileId() {
        return $this->profileId;
    }

    public function setProfileId($profileId) {
        $this->profileId = $profileId;
    }

    public function getDisplayname() {
        return $this->displayname;
    }

    public function setDisplayname($displayname) {
        $this->displayname = $displayname;
    }

    function __construct($profileId, $displayname) {
        $this->profileId = $profileId;
        $this->displayname = $displayname;
    }
    
    public static $constArgs = array(
                                    'profileId',
                                    'displayname'
                                    );
}

?>
