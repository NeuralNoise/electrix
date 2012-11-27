<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of msg
 *
 * @author DJ
 */
class Msg 
{
    private $messageId;
    private $subject;
    private $body;
    private $date;
    
    public function getMessageId() {
        return $this->messageId;
    }

    public function setMessageId($messageId) {
        $this->messageId = $messageId;
    }

    public function getSubject() {
        return $this->subject;
    }

    public function setSubject($subject) {
        $this->subject = $subject;
    }

    public function getBody() {
        return $this->body;
    }

    public function setBody($body) {
        $this->body = $body;
    }

    public function getDate() {
        return $this->date;
    }

    public function setDate($date) {
        $this->date = $date;
    }

    /**
     *
     * @param String $subject
     * @param String $body
     * @param String $date
     * @param Integer $messageId 
     */    
    function __construct($subject=NULL, $body=NULL, $date=NULL,$messageId=NULL) 
    {
        $this->messageId = $messageId;
        $this->subject = $subject;
        $this->body = $body;
        $this->date = $date;
    }
    
    public static $const_args = array(
                                'subject',
                                'body',
                                'date',
                                'messageId');
}

?>
