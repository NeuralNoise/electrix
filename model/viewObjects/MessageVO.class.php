<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of MessageVO
 *
 * @author DJ
 */
class MessageVO 
{
    private $messageId;
    private $subject;
    private $body;
    private $date;
    private $displayname;
    private $msgto;
    private $msgfrom;
    private $replyto;
    private $profileId;
    private $visibility;
    
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

    public function getDisplayname() {
        return $this->displayname;
    }

    public function setDisplayname($displayname) {
        $this->displayname = $displayname;
    }

    public function getMsgto() {
        return $this->msgto;
    }

    public function setMsgto($msgto) {
        $this->msgto = $msgto;
    }

    public function getMsgfrom() {
        return $this->msgfrom;
    }

    public function setMsgfrom($msgfrom) {
        $this->msgfrom = $msgfrom;
    }

    public function getReplyto() {
        return $this->replyto;
    }

    public function setReplyto($replyto) {
        $this->replyto = $replyto;
    }

    public function getProfileId() {
        return $this->profileId;
    }

    public function setProfileId($profileId) {
        $this->profileId = $profileId;
    }

    public function getVisibility() {
        return $this->visibility;
    }

    public function setVisibility($visibility) {
        $this->visibility = $visibility;
    }

    
    function __construct($messageId, $subject, $body, $date, $displayname, $msgto, $msgfrom, $replyto, $profileId, $visibility)
    {
        $this->messageId = $messageId;
        $this->subject = $subject;
        $this->body = $body;
        $this->date = $date;
        $this->displayname = $displayname;
        $this->msgto = $msgto;
        $this->msgfrom = $msgfrom;
        $this->replyto = $replyto;
        $this->profileId = $profileId;
        $this->visibility = $visibility;
    }

    public static $constArgs = array('messageId','subject','body','date','displayname','msgto','msgfrom','replyto','profileId','visibility');    
}

?>
