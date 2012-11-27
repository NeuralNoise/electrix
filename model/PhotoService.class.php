<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of PhotoService
 *
 * @author DJ
 */
class PhotoService extends BaseService {
    
    /**
     *
     * @param Photo $photo
     * @return photoId
     */
    public function insertPhotoDetails($photo)
    {
        $this->log->debug("Inside " . __CLASS__ . " " . __FUNCTION__ . "()...");
        
        $stmt = $this->db->prepare('
                                INSERT INTO photos
                                    (
                                        fileName,
                                        description,
                                        uploader,
                                        uploadedDate
                                    )
                                 VALUES
                                    (
                                        :fileName,
                                        :description,
                                        :uploader,
                                        :uploadedDate
                                    )');
        $stmt->bindValue(':fileName',$photo->getFileName(),PDO::PARAM_STR);
        $stmt->bindValue(':description', $photo->getDescription(), PDO::PARAM_STR);
        $stmt->bindValue(':uploader',$photo->getUploader(),PDO::PARAM_INT);
        $stmt->bindValue(':uploadedDate', date('Y-m-d H:i:s'), PDO::PARAM_STR);
        
        $stmt->execute();
        return $this->db->lastInsertId();        
    }       
    
    /**
     *
     * @param profile $profileId 
     * @return arrayOfPhoto
     */
    public function getPhotosForUser($profileId)
    {
        $this->log->debug("Inside " . __CLASS__ . " " . __FUNCTION__ . "()...");
        
        $stmt = $this->db->prepare('
                                SELECT
                                    fileName,
                                    description,
                                    uploadedDate,
                                    photoId
                                FROM
                                    photos
                                WHERE
                                    uploader = :profileId
                                    ');
        $stmt->bindValue(':profileId', $profileId,PDO::PARAM_INT);
        $stmt->execute();
        
        loadClass('Photo');
        return $stmt->fetchAll(PDO::FETCH_CLASS|PDO::FETCH_PROPS_LATE, 'Photo', Photo::$const_args);     
    }                              
}
?>
