<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of UpdateStatusVO
 *
 * @author DJ
 */
class UpdatesVOStatus {
    
    private $author;
    private $displayname;
    private $statusId;
    private $status;
    private $noOfLikes;
    private $iLiked;
    private $date;
    
    public function getAuthor() {
        return $this->author;
    }

    public function setAuthor($profileId) {
        $this->author = $profileId;
    }

    public function getDisplayname() {
        return $this->displayname;
    }

    public function setDisplayname($displayname) {
        $this->displayname = $displayname;
    }

    public function getStatusId() {
        return $this->statusId;
    }

    public function setStatusId($statusId) {
        $this->statusId = $statusId;
    }

    public function getStatus() {
        return $this->status;
    }

    public function setStatus($status) {
        $this->status = $status;
    }

    public function getDate() {
        return $this->date;
    }

    public function setDate($date) {
        $this->date = $date;
    }
    
    public function getNoOfLikes() {
        return $this->noOfLikes;
    }

    public function setNoOfLikes($noOfLikes) {
        $this->noOfLikes = $noOfLikes;
    }

    public function getILiked() {
        return $this->iLiked;
    }

    public function setILiked($iLiked) {
        $this->iLiked = $iLiked;
    }

    function __construct($author, $displayname, $statusId, $status, $noOfLikes, $iLiked, $date) {
        $this->author = $author;
        $this->displayname = $displayname;
        $this->statusId = $statusId;
        $this->status = $status;
        $this->noOfLikes = $noOfLikes;
        $this->iLiked = $iLiked;
        $this->date = $date;
    }

    public static $constArgs = array(
                                'author',
                                'displayname',
                                'statusId',
                                'status',
                                'noOfLikes',
                                'iLiked',
                                'date'
                                );
}
?>
