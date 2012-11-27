<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of messageController
 *
 * @author Arun
 * @copyright Copyrights (c) 2012 Ding Apps - Magesh Ravi
 */
class messagesController extends baseController
{
    public function index() 
	{
            $this->log->debug("Inside messagesController... index()");
	
            //SET TITLE FOR THE PAGE
		$this->registry->template->title = 'Messages - INBOX';
		
		//select message threads addressed to current user
                loadclass('MsgService');
                $msgService = new MsgService();
                
                $arr_msgsList = $msgService->getMessagesForCurrentUser($_SESSION['myid']);
                
                //SET TEMPLATE VARIABLES
		$this->registry->template->arr_msgsList = $arr_msgsList;
		
		//CALL THE VIEW FILE
		$this->registry->template->show();
	}
        
    public function sent() 
	{
		$this->log->debug("Inside messagesController... sent()");
		
		//SET TITLE FOR THE PAGE
		$this->registry->template->title = 'Messages - SENT';
		
		//select message threads sent by current user
		loadclass('MsgService');
                $msgService = new MsgService();
                
                $arr_msgsList = $msgService->getMessagesFromCurrentUser($_SESSION['myid']);               
                                
		//SET TEMPLATE VARIABLES
		$this->registry->template->msgs_list = $arr_msgsList;
		
		//CALL THE VIEW FILE
		$this->registry->template->show();
	}
	
	public function compose()
        {
            $this->log->debug('Inside '.__CLASS__.' '.__FUNCTION__.'()');
            
            $this->registry->template->title = 'Message - compose';
            
            try
            {
                if(isset($_GET['to']))
                {
                        $this->log->debug("In COMPOSE mode");
                        if(!preg_match('/^[A-Z ]+$/i', $_GET['to']))
                                Throw new InvalidArgumentException('Invalid Recipient',98);                       
                        $this->registry->templat->recipients = $_GET['to'];
                }
                
                if(isset($_GET['reply_to_msg']))
                {
                        $this->log->debug("In REPLY mode. Reply to msg:". $_GET['reply_to_msg']);
                        //$recipients = '';
                        
                        if(!preg_match('/^[0-9]+$/', $_GET['reply_to_msg']))
                                Throw new InvalidArgumentException('Message your are trying to reply does not exist',98);                        
			
                        //GET ALL RECIPIENTS FOR THIS MSG ID
                        loadclass('MsgService');
                        $msgService = new MsgService();
                        $myDisplayName = $_SESSION['displayname'];
                        $recipients = $msgService->getRecipientsForMsgId($_GET['reply_to_msg'],$myDisplayName);  
                        $subject = 'Re:'.$msgService->getSubjectForMsgId($_GET['reply_to_msg']);
				
                        $this->registry->template->replyToMsgId = $_GET['reply_to_msg'];
                        $this->registry->template->recipients = $recipients;
                        $this->registry->template->msgSubject = $subject;		
                }
                
                
                if(!empty ($_POST))
		{
                    $allowed = array();
                    $allowed[] = 'msg_to';
                    $allowed[] = 'msg_subject';
                    $allowed[] = 'msg_body';
                    $allowed[] = 'reply_to_msg';
                    
                    $submitted = array_keys($_POST);
                    if($allowed != $submitted)
                        Throw new InvalidArgumentException('Invalid form submission',98);            
			
                    $subject = filter_var($_POST['msg_subject'], FILTER_SANITIZE_STRING);
                    $body = filter_var($_POST['msg_body'], FILTER_SANITIZE_STRING);

                    if(!filter_var($_POST['reply_to_msg'], FILTER_VALIDATE_INT))
                        $replyToMsgId = -1;
                    else
                        $replyToMsgId = $_POST['reply_to_msg'];
                    
                    if(strlen($_POST['msg_to']) < 1)
                        throw new InvalidArgumentException ('Specify atleast one recipient',98);
                    	
                    if(strlen($subject) < 1)
                        throw new InvalidArgumentException ('Subject cannot be empty!', 98);
                    
                    if(strlen($subject) > 32)
                        throw new InvalidArgumentException ('Subject too long!', 98);
                    
                    if(strlen($body) < 1)
                        throw new InvalidArgumentException ('Message cannot be empty!',98);            
                    
                    if(strlen($body) > 256)
                        throw new InvalidArgumentException('Message too long!',98);
                    
                    loadclass('Msg');
                    $msg = new Msg(
                                    $subject,
                                    $body,
                                    date('Y-m-d H:i:s')
                                    );
                    
                    
                    loadClass('MsgService');
                    $msgService = new MsgService();
                    
                    $arr_dispNames = explode(',', $_POST['msg_to']);
                    $msgService->composeMessage($msg, $arr_dispNames,$_SESSION['myid'],$replyToMsgId);
                    
                    $this->registry->template->message = '<span class="success">Message sent successfully</span>';
                }
            }
            catch(InvalidArgumentException $e)
            {
                $this->log->debug('EXCEPTION: '.$e->getMessage());
                if(isset($_POST['msg_to']))
                    $this->registry->template->recipients = $_POST['msg_to'];
                if(isset($_POST['msg_subject']))
                    $this->registry->template->msgSubject = $_POST['msg_subject'];
                if(isset($_POST['msg_body']))
                    $this->registry->template->msgBody = $_POST['msg_body'];

                $this->registry->template->message = '<span class="alert">'.$e->getMessage().'</span>';
            }
            catch(Exception $e)
            {
                $this->log->debug('EXCEPTION: '.$e->getMessage());
                if(isset($_POST['msg_to']))
                    $this->registry->template->recipients = $_POST['msg_to'];
                if(isset($_POST['msg_subject']))
                    $this->registry->template->msgSubject = $_POST['msg_subject'];
                if(isset($_POST['msg_body']))
                    $this->registry->template->msgBody = $_POST['msg_body'];

                $this->registry->template->message = '<span class="alert">Unexpected error occurred</span>';
            }
            
            $this->registry->template->show();
        }
     
    public function showMsg()
    {
        checkAuth();

        $this->log->debug("Inside " . __CLASS__ . " " . __FUNCTION__ . "()...");

        $messageId = preg_replace('#[^0-9]#','',$_GET['messageId']);
        if($messageId != '')
        {
                //SET TITLE FOR THE PAGE
                $this->registry->template->title = "View message";

                $cacheFileName = 'showMsg_'.$messageId;
                try
                {
                //CHECK IF THE CURRENT USER HAS PERMISSION TO VIEW THIS MESSAGE
                loadclass('MsglogService');
                $msglogService = new MsglogService();

                $hasPermission = $msglogService->checkPermissionForMessageView($messageId,$_SESSION['myid']);
                }
                catch(PDOException $e)
                {
                        $this->registry->template->message = "<span class='alert'>An error occurred! Please try again later!</span>";
                        $this->log->debug("PDOException while checking permission to view msg:".$e->getMessage());
                }

                //CHECK FOR CACHE FILE (EXPIRY TIME = ONE DAY)
                if($hasPermission && !checkForCache($cacheFileName, 86400))
                {
                        try
                        {
                                $this->log->debug("Fetching details for msg ID: ".$messageId);

                                //GET FROM, TO, SUBJECT AND BODY OF THE MESSAGE
                                loadClass('MsgService');
                                $msgService = new MsgService();

                                $msg_details = $msgService->getMessageDetails($messageId);

                                //GET DISPLAY NAME OF FROM ID
                                $from_displayName1 = $msglogService->getFromId($messageId);
                                foreach ($from_displayName1 as $from_displayName)
                                {
                                /* @var $from_displayName MessageVO */
                                $this->log->debug("Message written by: ".$from_displayName->getDisplayname());
                                $from_displayName = $from_displayName->getDisplayname();
                                }
                                //GET DISPLAY NAME OF TO ID

                                $to_list = $msglogService->getToId($messageId);

                                $recipients='';

                                $count = count($to_list);

                                foreach($to_list as $to_obj)
                                {
                                    /* @var $to_ob MessageVO */
                                        $recipients .= $to_obj->getDisplayname();
                                        $count--;
                                        if($count!=0)
                                                $recipients .= ', ';
                                }
                                $this->log->debug("Message received by: ".$recipients);

                                //SET TEMPLATE VARIABLES
                                $this->registry->template->msg_details = $msg_details;
                                $this->registry->template->msg_from = $from_displayName;
                                $this->registry->template->msg_to = $recipients;
                        }
                        catch(PDOException $e)
                        {
                                $this->registry->template->message = "<span class='alert'>An error occurred! Please try again later!</span>";
                                $this->log->debug("PDOException while fetching message details:".$e->getMessage());
                        }
                }

                if($hasPermission)
                {
                        //CALL THE VIEW FILE
                        $this->registry->template->show();
                }
                else
                {
                        //SET TEMPLATE VARIABLES
                        $this->registry->template->message = '<span class="alert">ERROR: This message was not intended for you!</span>';
                        //CALL THE VIEW FILE
                        $this->registry->template->show();
                }
        }
}	
    public function deleteMsg()
    { 
        checkAuth();

        $this->log->debug("Inside " . __CLASS__ . " " . __FUNCTION__ . "()...");

        if(isset($_POST['delete_inbox_msgs']))
        {
            try
            {
                //DELETE INBOX MSGS
                $arr_msgIds = $_POST['delete_inbox_msgs'];

                //CHANGE VISIBILITY OF DELETED MSGS
                loadclass('MsglogService');
                $msglogService = new MsglogService(); 

                $msglogService->removeInboxMessage($arr_msgIds, $_SESSION['myid']);

                Header("location:index.php?rt=messages");
            }
            catch(PDOException $e)
            {
                    $this->registry->template->message = "<span class='alert'>An error occurred! Please try again later!</span>";
                    $this->log->debug("PDOException while deleting msgs from INBOX:".$e->getMessage());
            }
        }
        if(isset($_POST['delete_sent_msgs']))
        {
            //DELETE SENT ITEMS
            $arr_msgIds = $_POST['delete_sent_msgs'];

            try
            {
                //CHANGE VISIBILITY OF DELETED MSGS
                loadClass('MsglogService');
                $msglogService = new MsglogService();
                $this->log->debug(print_r($arr_msgIds));
                $msglogService->removeSentMessage($arr_msgIds,$_SESSION['myid']);

                Header("location:index.php?rt=messages/sent");
            }
            catch(PDOException $e)
            {
                $this->registry->template->message = "<span class='alert'>An error occurred! Please try again later!</span>";
                $this->log->debug("PDOException while deleting msgs from SENT ITEMS:".$e->getMessage());
            }
        }
        //CALL THE VIEW FILE
        $this->registry->template->show();
    }
    public function newsletter()
    {
        checkAuth();

        $this->log->debug("Inside " . __CLASS__ . " " . __FUNCTION__ . "()...");

        //SET TITLE FOR THE PAGE
        $this->registry->template->title = "Send newsletter";

        //CALL THE VIEW FILE
        $this->registry->template->show();
    }
}

?>
