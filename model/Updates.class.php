<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Updates
 *
 * @author DJ
 */
class Updates 
{
    private $updateId;
    private $type;
    private $itemId;
    private $targetStatusId;
    private $author;
    private $date;
    
    public function getUpdateId() {
        return $this->updateId;
    }

    public function setUpdateId($updateId) {
        $this->updateId = $updateId;
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

    public function getTargetStatusId() {
        return $this->targetStatusId;
    }

    public function setTargetStatusId($targetStatusId) {
        $this->targetStatusId = $targetStatusId;
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
     * @param Integer $targetStatusId
     * @param String $author
     * @param String $date 
     * @param Integer $updateId
     */
    function __construct($type=NULL, $itemId=NULL, $targetStatusId= NULL,$author=NULL, $date=NULL,$updateId=NULL) {
        $this->updateId = $updateId;
        $this->type = $type;
        $this->itemId = $itemId;
        $this->targetStatusId = $targetStatusId;
        $this->author = $author;
        $this->date = $date;
    }
    
    public static $constArgs = array(
                        'type',
                        'itemId',
                        'targetStatusId',
                        'author',
                        'date',
                        'updateId');
}
?>
