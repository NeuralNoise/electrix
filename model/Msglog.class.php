<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Msglog
 *
 * @author DJ
 */
class Msglog 
{
   private $messageId;
   private $msgfrom;
   private $msgto;
   private $replyto;
   private $visibility;
   
   public function getMessageId() {
       return $this->messageId;
   }

   public function setMessageId($messageId) {
       $this->messageId = $messageId;
   }

   public function getMsgfrom() {
       return $this->msgfrom;
   }

   public function setMsgfrom($msgfrom) {
       $this->msgfrom = $msgfrom;
   }

   public function getMsgto() {
       return $this->msgto;
   }

   public function setMsgto($msgto) {
       $this->msgto = $msgto;
   }

   public function getReplyto() {
       return $this->replyto;
   }

   public function setReplyto($replyto) {
       $this->replyto = $replyto;
   }

   public function getVisibility() {
       return $this->visibility;
   }

   public function setVisibility($visibility) {
       $this->visibility = $visibility;
   }

      
   /**
    *
    * @param Integer $messageId
    * @param Integer $msgfrom
    * @param Integer $msgto
    * @param Integer $replyto
    * @param Integer $visibility    
    */
   function __construct($messageId=NULL,$msgfrom=NULL, $msgto=NULL, $replyto=NULL, $visibility=11) 
    {
       $this->messageId = $messageId;
       $this->msgfrom = $msgfrom;
       $this->msgto = $msgto;
       $this->replyto = $replyto;
       $this->visibility = $visibility;
   }

   public static $const_args = array(                                
                                'messageId',
                                'msgfrom',
                                'msgto',
                                'replyto',
                                'visibility'                            
                                  );
}

?>
