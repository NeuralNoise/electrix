<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Statusmsg
 *
 * @author nancy
 */
class Statusmsg 
{
    private $statusId;
    private $author;
    private $status;
    private $date;
    
    public function getStatusId() {
        return $this->statusId;
    }

    public function setStatusId($statusId) {
        $this->statusId = $statusId;
    }

    public function getAuthor() {
        return $this->author;
    }

    public function setAuthor($author) {
        $this->author = $author;
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

    function __construct($status='', $author='', $date='', $statusId=NULL)
    {
        $this->statusId = $statusId;
        $this->author = $author;
        $this->status = $status;
        $this->date = $date;
    }
}

?>
