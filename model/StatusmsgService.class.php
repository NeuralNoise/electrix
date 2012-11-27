<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of StatusmsgService
 *
 * @author nancy
 */
class StatusmsgService extends BaseService 
{
    /**
     *
     * @param Statusmsg $statusmsg 
     * @return Integer $statusId
     */
    
    public function insertStatusmsg($statusmsg)
    {
        $this->log->debug("Inside " . __CLASS__ . " " . __FUNCTION__ . "()...");
        
        $stmt = $this->db->prepare('
                                INSERT INTO 
                                    statusmsgs 
                                        (author, 
                                        status, 
                                        statusDate) 
                                VALUES 
                                        (:author, 
                                        :status, 
                                        :date)
                                    ');
        $stmt->bindValue(':author', $statusmsg->getAuthor(), PDO::PARAM_INT);
        $stmt->bindValue(':status', $statusmsg->getStatus(), PDO::PARAM_STR);
        $stmt->bindValue(':date', date("Y-m-d H:i:s"));
        $stmt->execute();
        
        $statusId = $this->db->lastInsertId();
        $this->log->debug("$statusId:");
    }
    /**
    *
    * @param Integer $profileId
    * @return arrayOfObjects (Statusmsg)
    *
    */

    public function retrieveStatusmsg($profileId)
    {
        $stmt = $this->db->prepare('
                                SELECT
                                    *
                                FROM 
                                    statusmsgs 
                                WHERE 
                                    author=:profileId
                                ORDER BY 
                                    statusDate 
                                DESC 
                                    LIMIT 1
                                ');
        $stmt->bindValue(':profileId', $profileId, PDO::PARAM_INT);
        $stmt->execute();
        loadClass('Statusmsg');
        return $stmt->fetchAll(PDO::FETCH_CLASS|PDO::FETCH_PROPS_LATE, 'Statusmsg', 
                array(
                    'statusId', 
                    'author',
                    'status',
                    'statusDate'
                    )); 
        }
        
    /**
     * 
     * @param Integer $statusId
     * @param Integer $profileId
     * @return Integer
     */
    public function deleteStatusmsg($statusId,$profileId)
    {
        $stmt = $this->db->prepare('
                            DELETE FROM                                     
                                statusmsgs
                            WHERE
                                statusId =:statusId
                            AND
                                author = :author
                                ');
        $stmt->bindValue(':statusId', $statusId,PDO::PARAM_INT);
        $stmt->bindValue(':author', $profileId, PDO::PARAM_INT); 
        $stmt->execute();
        
        return $stmt->rowCount();   
    }
}

?>
