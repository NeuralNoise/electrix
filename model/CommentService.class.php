<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of CommentService
 *
 * @author Vagabond
 */
class CommentService extends BaseService {
   
    /**
     *
     * @param Comment $comments 
     * @return Integer $commentId
     */
    public function addComment($comments)
    {
        $this->log->debug("Inside " . __CLASS__ . " " . __FUNCTION__ . "()...");
        
        $stmt = $this->db->prepare('
                                INSERT INTO 
                                    comments 
                                    (
                                        type,
                                        itemId,
                                        comment,
                                        author,
                                        date
                                    )
                                VALUES 
                                    (
                                        :type,
                                        :itemId,
                                        :comment,
                                        :author,
                                        :today
                                    )
                                ');

        $stmt->bindValue(':type', $comments->getType(), PDO::PARAM_STR);
        $stmt->bindValue(':itemId', $comments->getItemId(), PDO::PARAM_INT);
        $stmt->bindValue(':comment', $comments->getComment(), PDO::PARAM_STR);
        $stmt->bindValue(':author', $comments->getAuthor(), PDO::PARAM_INT);
        $stmt->bindValue(':today', date("Y-m-d H:i:s"));
        
        $stmt->execute();

        return $this->db->lastInsertId();      
    }

    /**
     *
     * @param String $csv_statusid
     * @param Integer $profileId
     * @return arrayOfUpdatesVOComments 
     */
    public function getCommentsForStatus($csv_statusid, $profileId)
    {
        $stmt = $this->db->prepare("
                                    SELECT
                                        itemId,
                                        commentId,
                                        profileId AS author,
                                        displayname,
                                        comment,
                                        (
                                            SELECT
                                                count(profileId)
                                            FROM 
                                                likes l
                                            WHERE
                                                l.type='comment' AND
                                                l.itemId = commentId
                                        ) AS noOfLikes,
                                        (
                                            SELECT
                                                count(profileId)
                                            FROM 
                                                likes l
                                            WHERE
                                                l.type='comment' AND
                                                l.itemId = commentId AND
                                                l.profileId = :profileId
                                        ) AS iLiked,
                                        date
                                    FROM
                                        comments c,
                                        profile p
                                    WHERE
                                        author = profileId AND 
                                        type = 'status' AND 
                                        itemId IN ($csv_statusid)
                                    ORDER BY
                                        itemId, date
                ");
        $stmt->bindValue(':profileId',$profileId,PDO::PARAM_INT);
        $stmt->execute();
        loadClass('viewObjects/UpdatesVOComment');
        return $stmt->fetchAll(PDO::FETCH_CLASS|PDO::FETCH_PROPS_LATE, 'UpdatesVOComment',  UpdatesVOComment::$constArgs); 
    }
    
    /**
     *
     * @param Integer $commentId
     * @param Integer $profileId
     * @return Integer 
     */
    public function deleteComment($commentId,$profileId)
    {
         $stmt = $this->db->prepare('
                                DELETE FROM                                     
                                    comments
                                WHERE
                                    commentId =:commentId 
                                AND
                                    itemId IN
                                        (
                                            SELECT 
                                                statusId
                                            FROM
                                                statusmsgs
                                            WHERE
                                                author =:author
                                        )
                                AND 
                                    type = "status" 
                                OR
                                    author =:author                                
                                LIMIT 1    
                                    ');
              
        $stmt->bindValue(':commentId', $commentId,PDO::PARAM_INT);
        $stmt->bindValue(':author', $profileId, PDO::PARAM_INT); 
        $stmt->execute();       
        
        return $stmt->rowCount();                
    }
}
?>
