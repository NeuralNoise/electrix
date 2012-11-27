<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of LikesService
 *
 * @author DJ
 */
class LikeService extends BaseService {
    
    /**     
     *
     * @param Like $like
     * @param Integer $profileId 
     */
    public function addLike($like, $profileId)
    {
         $this->log->debug("Inside " . __CLASS__ . " " . __FUNCTION__ . "()...");

           $stmt = $this->db->prepare('
                                    INSERT INTO
                                        likes 
                                            (
                                                type,
                                                itemId,
                                                profileId
                                            ) 
                                    VALUES
                                            (
                                                :type,
                                                :itemId,
                                                :profileId
                                            )
                                      ');
           
           $stmt->bindValue(':type', $like->getType(),PDO::PARAM_STR);
           $stmt->bindValue(':itemId', $like->getItemId(),PDO::PARAM_INT);
           $stmt->bindValue(':profileId',$profileId, PDO::PARAM_INT);
           $stmt->execute();    
                             
    }
    
    /**
     *
     * @param Like $like
     * @param Integer $profileId
     * @return Integer 
     */
    public function unlike($like,$profileId)
    {
        $this->log->debug("Inside " . __CLASS__ . " " . __FUNCTION__ . "()...");
        
        $stmt = $this->db->prepare('
                                DELETE FROM 
                                    likes 
                                WHERE 
                                    type = :type 
                                        AND 
                                    itemId = :itemId
                                        AND 
                                    profileId = :profileId
                                LIMIT 1             
                                    ');
        
        $stmt->bindValue(':type', $like->getType(),PDO::PARAM_STR);
        $stmt->bindValue(':itemId', $like->getItemId(),PDO::PARAM_INT);
        $stmt->bindValue(':profileId',$profileId, PDO::PARAM_INT);
               
        $stmt->execute();
        return $stmt->rowCount();
    }
    
    /**
     *
     * @param Like $like
     * @return arrayOfLikeDetailsVO
     */
    public function getlikedetails($like)
    {
        $this->log->debug("Inside " . __CLASS__ . " " . __FUNCTION__ . "()...");
        
        $stmt = $this->db->prepare('
                                SELECT 
                                    l.profileId,
                                    displayname
                                FROM 
                                    likes l,
                                    profile p
                                WHERE 
                                    l.type = :type 
                                        AND 
                                    l.profileId = p.profileId 
                                        AND 
                                    l.itemId = :itemId   
                                    ');
        $stmt->bindValue(':type', $like->getType(),PDO::PARAM_STR);
        $stmt->bindValue(':itemId', $like->getItemId(),PDO::PARAM_INT);
        
        $stmt->execute();
        
        loadClass('viewObjects/LikeDetailsVO');
        return $stmt->fetchAll(PDO::FETCH_CLASS|PDO::FETCH_PROPS_LATE, 'LikeDetailsVO',  LikeDetailsVO::$constArgs); 
    }
}
?>
