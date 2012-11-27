<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of MsgService
 *
 * @author DJ
 */
class MsgService extends BaseService
{
    /**
     *
     * @param Msg $msg 
     * @param arrayOfdisplaynames $arr_dispNames
     * @param Integer $profileId
     * * @param Integer $replyToMsg
     * @return Integer
     */
    public function composeMessage($msg, $arr_dispNames,$profileId,$replyToMsg)
    {
        $this->log->debug("Inside " . __CLASS__ . " " . __FUNCTION__ . "()...");          
        try
        {
            $this->db->beginTransaction();
            
            $stmt = $this->db->prepare('
                                   INSERT INTO messages
                                   (
                                        messageId,
                                        subject,
                                        body,
                                        date
                                   )
                                   VALUES
                                   (
                                        :messageId,
                                        :subject,
                                        :body,
                                        :today
                                    )
                                  ');
           
            $stmt->bindValue(':messageId',$msg->getMessageId(),PDO::PARAM_INT);
            $stmt->bindValue(':subject', $msg->getSubject(), PDO::PARAM_STR);
            $stmt->bindValue(':body', $msg->getBody(), PDO::PARAM_STR);
            $stmt->bindValue(':today', date("Y-m-d H:i:s"));

            $stmt->execute(); 

            $messageId = $this->db->lastInsertId();
            
            $stmt2 = $this->db->prepare('
                                    INSERT INTO messages_log 
                                    (
                                        messageId,
                                        msgfrom, 
                                        msgto, 
                                        replyto, 
                                        visibility
                                    ) 
                                    VALUES 
                                    (
                                        :messageId,
                                        :msgfrom, 
                                        (
                                            SELECT
                                                profileId 
                                            FROM 
                                                profile
                                            WHERE 
                                                displayname = :dpname
                                        ), 
                                        :replyto, 
                                        11
                                    )
                                  ');
            
            $stmt2->bindValue(':messageId', $messageId, PDO::PARAM_INT);
            $stmt2->bindValue(':msgfrom', $profileId, PDO::PARAM_INT);
            $stmt2->bindValue(':replyto', $replyToMsg, PDO::PARAM_INT);
                       
            foreach($arr_dispNames as $dispname)
            {
               if(trim($dispname) != '')
               {
                    $this->log->debug('Display Name:'.$dispname);
                    $stmt2->bindValue(':dpname', trim($dispname), PDO::PARAM_STR);
                    $stmt2->execute();
               }
            }
            
            $this->db->commit();
            
            return $messageId;
        }
        catch(Exception $e)
        {
            $this->db->rollBack();
            $this->log->debug('EXCEPTION: '.$e->getMessage());
            
            throw new Exception($e->getMessage(), $e->getCode());
        }
    }
    
    
    /**
     *
     * @param String $dpname
     * @return Integer 
     */
    public function getProfileId($dpname)
    {
        $this->log->debug("Inside " . __CLASS__ . " " . __FUNCTION__ . "()...");
        
           $stmt = $this->db->prepare('
                                    SELECT 
                                        profileId
                                    FROM 
                                        profile 
                                    WHERE 
                                        displayname = :dpname
                                    LIMIT 1
                                    ');

                $stmt->bindValue(':dpname', $dpname, PDO::PARAM_STR);
                
                $stmt->execute();
                
                $result = $stmt->fetch(PDO::FETCH_ASSOC);
                return $result['profileId'];   
    }
    
    /**
     *
     * @param Integer $messageId
     * @return Msg
     */    
     
    public function getMessage($messageId)
    {
        $this->log->debug("Inside " . __CLASS__ . " " . __FUNCTION__ . "()...");
        
        $stmt = $this->db->prepare('
                                SELECT
                                        *
                                FROM
                                    messages
                                WHERE
                                    messageId = :messageId 
                                LIMIT 1
                                ');
                
        $stmt->bindValue(':messageId', $messageId,PDO::PARAM_INT);                                       
					
        $stmt->execute();

        $msg_details = $stmt-> fetchAll(PDO::FETCH_CLASS|PDO::FETCH_PROPS_LATE, 'Msg',Msg::$const_args);
        
        return $msg_details;       
    }  
    /**
     *
     * @param Integer $messageId 
     * @return arrayOfObjects Msgs
     */
    public function getMessageDetails($messageId)
    {
        $this->log->debug("Inside " . __CLASS__ . " " . __FUNCTION__ . "()...");
        
        $stmt = $this->db->prepare('
                                SELECT
                                    *
                                FROM
                                    messages
                                WHERE 
                                    messageId = :messageId 
                                LIMIT 1
                                ');

        $stmt->bindValue(':messageId', $messageId,PDO::PARAM_INT);

        $stmt->execute();
        
        loadClass('Msg');
        return $stmt->fetchAll(PDO::FETCH_CLASS|PDO::FETCH_PROPS_LATE, 'Msg', Msg::$const_args);
    }


    /**
     *@param Integer $profileId
     * @return $msgs_list MessageVO
     */
    public function getMessagesForCurrentUser($profileId)
    {
        $stmt = $this->db->prepare('
                                SELECT 
                                    m.messageId,
                                    m.subject,
                                    m.date,
                                    p.displayname
                                FROM 
                                    messages m,
                                    messages_log ml,
                                    profile p 
                                WHERE 
                                    m.messageId = ml.messageId 
                                AND
                                    ml.msgto = :msgto 
                                AND
                                    p.profileId = ml.msgfrom 
                                AND 
                                    (visibility = :visi1 OR visibility = :visi2) 
                                ORDER BY
                                    m.date 
                                DESC                    
                            ');
		
		$stmt->bindValue(':msgto', $profileId, PDO::PARAM_INT);
		$stmt->bindValue(':visi1', '11', PDO::PARAM_INT);
		$stmt->bindValue(':visi2', '01', PDO::PARAM_INT);
		
		$stmt->execute();
		
                loadclass('viewObjects/MessageVO');
		return $stmt->fetchAll(PDO::FETCH_CLASS|PDO::FETCH_PROPS_LATE, 'MessageVO', MessageVO::$constArgs);
    }
    /**
     * @param Integer $profileId
     * @return MessageVO
     */
    public function getMessagesFromCurrentUser($profileId)
    {
        $stmt = $this->db->prepare('
                                    SELECT 
                                        DISTINCT(ml.messageId),
                                        m.subject,
                                            (SELECT 
                                                displayname 
                                            FROM 
                                                profile
                                            WHERE
                                                profileId =ml.msgto) as msgto,
                                        m.date 
                                    FROM 
                                        messages m, 
                                        messages_log ml,
                                        profile p
                                    WHERE
                                        m.messageId = ml.messageId 
                                    AND 
                                        ml.msgfrom = :msgfrom
                                    AND 
                                        (ml.visibility = :visi1 OR ml.visibility = :visi2) 
                                    ORDER BY
                                        m.date 
                                    DESC
                                    ');
		
		$stmt->bindValue(':msgfrom', $profileId, PDO::PARAM_INT);
		$stmt->bindValue(':visi1', '11', PDO::PARAM_INT);
		$stmt->bindValue(':visi2', '10', PDO::PARAM_INT);
		
		$stmt->execute();
		
                loadclass('viewObjects/MessageVO');
		return $stmt->fetchAll(PDO::FETCH_CLASS|PDO::FETCH_PROPS_LATE, 'MessageVO', MessageVO::$constArgs);
		
    }
    /**
     *
     * @param Integer $messageId
     * @param String $myDisplayName
     * @return string 
     */
    public function getRecipientsForMsgId($messageId,$myDisplayName)
    {
	$this->log->debug("Inside commonFunctions getRecipientsForMsgId()...");

		$stmt = $this->db->prepare('
                                            SELECT 
                                                distinct(p.displayname) 
                                                AS displayname
                                            FROM 
                                                messages_log AS ml, 
                                                profile AS p 
                                            WHERE 
                                                ml.messageId = :messageId 
                                            AND 
                                                (
                                                    p.profileId = ml.msgto
                                                    OR 
                                                    p.profileId = ml.msgfrom
                                                )
                                            ');
					
		$stmt->bindValue(':messageId', $messageId,  PDO::PARAM_INT);
		
		$stmt->execute();
		$result = $stmt->fetchAll(PDO::FETCH_ASSOC);
                $recipients = '';
		
		foreach($result as $curRecipient)
		{
			if($curRecipient['displayname'] != $myDisplayName)
				$recipients .= $curRecipient['displayname'].', ';
		}
                return $recipients;
		
}

/**
 *
 * @param Integer $messageId
 * @return String
 */
public function getSubjectForMsgId($messageId)
{
    $this->log->debug("Inside " . __CLASS__ . " " . __FUNCTION__ . "()...");

        $stmt = $this->db->prepare('
                                SELECT 
                                    subject 
                                FROM 
                                    messages
                                WHERE 
                                    messageId = :messageId 
                                LIMIT 1
                                ');

        $stmt->bindValue(':messageId', $messageId,  PDO::PARAM_INT);

        $stmt->execute();

        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        return $result['subject'];
}
}
?>
