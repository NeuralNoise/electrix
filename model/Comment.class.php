<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Comment
 *
 * @author Vagabond
 */
class Comment {    
    private $commentId;
    private $type;
    private $itemId;
    private $comment;
    private $author;
    private $date;
    
    public function getCommentId() {
        return $this->commentId;
    }

    public function setCommentId($commentId) {
        $this->commentId = $commentId;
    }

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

    public function getComment() {
        return $this->comment;
    }

    public function setComment($comment) {
        $this->comment = $comment;
    }

    public function getAuthor() {
        return $this->author;
    }

    public function setAuthor($author) {
        $this->author = $author;
    }

    public function getDate() {
        return $this->date;
    }

    public function setDate($date) {
        $this->date = $date;
    }
    
    /**
     *
     * @param String $type
     * @param Integer $itemId
     * @param String $comment
     * @param Integer $author
     * @param String $date 
     * @param Integer $commentId
     */
    
    function __construct($type=NULL, $itemId=NULL, $comment=NULL, $author=NULL, $date=NULL,$commentId=NULL) {
        $this->commentId = $commentId;
        $this->type = $type;
        $this->itemId = $itemId;
        $this->comment = $comment;
        $this->author = $author;
        $this->date = $date;
    }
    
    public static $const_args = array(
                                    'type',
                                    'itemId',
                                    'comment',
                                    'author',
                                    'date',
                                    'commentId'
                                    );
}
?>
