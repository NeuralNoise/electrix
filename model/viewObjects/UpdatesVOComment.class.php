<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of UpdatesVOComments
 *
 * @author DJ
 */
class UpdatesVOComment {
    private $itemId;
    private $commentId;
    private $displayname;
    private $author;
    private $comment;
    private $noOfLikes;
    private $iLiked;
    private $date;
    
    public function getItemId() {
        return $this->itemId;
    }

    public function setItemId($itemId) {
        $this->itemId = $itemId;
    }

    public function getCommentId() {
        return $this->commentId;
    }

    public function setCommentId($commentId) {
        $this->commentId = $commentId;
    }
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

    public function getComment() {
        return $this->comment;
    }

    public function setComment($comment) {
        $this->comment = $comment;
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

    function __construct($itemId, $commentId, $displayname, $author, $comment, $noOfLikes, $iLiked, $date) {
        $this->itemId = $itemId;
        $this->commentId = $commentId;
        $this->displayname = $displayname;
        $this->author = $author;
        $this->comment = $comment;
        $this->noOfLikes = $noOfLikes;
        $this->iLiked = $iLiked;
        $this->date = $date;
    }
    
    public static $constArgs = array(
                                    'itemId',
                                    'commentId',
                                    'author',
                                    'displayname',
                                    'comment',  
                                    'noOfLikes',
                                    'iLiked',
                                    'date',
                                        );
}
?>
