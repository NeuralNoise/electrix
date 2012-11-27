<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of MsglogService
 *
 * @author DJ
 */
class MsglogService extends BaseService 
{
  
    /**
     *
     * @param Integer $messageId
     * @param Integer $profileId
     * @return Boolean 
     */
    public function checkPermissionForMessageView($messageId,$profileId)
    {
        $this->log->debug("Inside " . __CLASS__ . " " . __FUNCTION__ . "()...");
        
        $stmt = $this->db->prepare('
                                SELECT 
                                    * 
                                FROM 
                                    messages_log 
                                WHERE 
                                    messageId = :messageId
                                AND 
                                    (msgfrom=:profileId OR msgto=:profileId)
                                LIMIT 1
                                ');

        $stmt->bindValue(':messageId', $messageId,PDO::PARAM_INT);
        $stmt->bindValue(':profileId', $profileId,PDO::PARAM_INT);

        $stmt->execute();
        
        if ( $stmt->rowCount()== 1 )
            return TRUE;
        else
            return FALSE;
    }
    
    /**
     *
     * @param Integer $messageId
     * @return arrayOfMessageVO 
     */
    public function getFromId($messageId)
    {
        $this->log->debug("Inside " . __CLASS__ . " " . __FUNCTION__ . "()...");
        
        $stmt = $this->db->prepare('
                                SELECT
                                    displayname, 
                                    ml.msgfrom 
                                FROM 
                                    messages_log ml,
                                    profile p
                                WHERE 
                                    p.profileId=ml.msgfrom
                                AND 
                                    ml.messageId = :messageId
                                LIMIT 1
                                ');

        $stmt->bindValue(':messageId', $messageId,PDO::PARAM_INT);

        $stmt->execute();
        
        //$result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        //return $result['displayname'];
        loadClass('viewObjects/MessageVO');
        return $stmt->fetchAll(PDO::FETCH_CLASS|PDO::FETCH_PROPS_LATE, 'MessageVO', MessageVO::$constArgs);
    }
    
    
    /**
     *
     * @param Integer $messageId
     * @return arrayOfObject MessageVO 
     */
    public function getToId($messageId)
    {
        $this->log->debug("Inside " . __CLASS__ . " " . __FUNCTION__ . "()...");

        $stmt = $this->db->prepare('
                                SELECT 
                                    p.displayname, 
                                    ml.msgto
                                FROM
                                    messages_log ml,
                                    profile p 
                                WHERE
                                    p.profileId=ml.msgto 
                                AND
                                    ml.messageId = :messageId
                                 ');

        $stmt->bindValue(':messageId', $messageId,PDO::PARAM_INT);

        $stmt->execute();
        loadClass('viewObjects/MessageVO');
        
        return $stmt->fetchAll(PDO::FETCH_CLASS|PDO::FETCH_PROPS_LATE, 'MessageVO', MessageVO::$constArgs);
    }
    
    /**
     *
     * @param arrayOfIntegers $arr_msgIds 
     * @param Integer $profileId
     */
    public function removeInboxMessage($arr_msgIds,$profileId)
    {
        $msgIds_csv = '';
        
        foreach($arr_msgIds as $msgId)
        {
            if($msgIds_csv == '')
                $msgIds_csv .= $msgId;
            else
                $msgIds_csv .= ','.$msgId;
        }
        
        $query = 'UPDATE 
                    messages_log 
                SET 
                    visibility =(visibility-1)
                WHERE
                    messageId 
                           IN ('.$msgIds_csv.') AND                         
                    msgto = '.$profileId;        

        $stmt = $this->db->prepare($query);
        
        $stmt->bindValue(':messageId', $arr_msgIds,PDO::PARAM_INT);

        $stmt->execute();
    
    }
    
    
    /**
     *
     * @param arrayOfIntegers $arr_msgIds
     * @param Integer $profileId
     */
    public function removeSentMessage($arr_msgIds, $profileId)
    {
        $msgIds_csv = '';
        foreach($arr_msgIds as $msgId)
        {
            if($msgIds_csv == '')
                $msgIds_csv .= $msgId;
            else
                $msgIds_csv .= ','.$msgId;
        }
        
        $this->log->debug($msgIds_csv);
        $query = 'UPDATE 
                      messages_log
                  SET 
                      visibility=(visibility-10)
                  WHERE 
                      messageId IN ('.$msgIds_csv.') AND 
                      msgfrom = :fromId';


        $stmt = $this->db->prepare($query);
        
        $stmt->bindValue(':fromId',$profileId, PDO::PARAM_INT);

        $stmt->execute();
        
        $this->log->debug("Row count: ".$stmt->rowCount());
    }
}
?>
