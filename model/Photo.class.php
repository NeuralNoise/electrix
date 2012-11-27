<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Photo
 *
 * @author DJ
 */
class Photo {
    private $photoId;
    private $fileName;
    private $description;
    private $uploader;
    private $uploadedDate;
    
    public function getPhotoId() {
        return $this->photoId;
    }

    public function setPhotoId($photoId) {
        $this->photoId = $photoId;
    }

    public function getFileName() {
        return $this->fileName;
    }

    public function setFileName($fileName) {
        $this->fileName = $fileName;
    }

    public function getDescription() {
        return $this->description;
    }

    public function setDescription($description) {
        $this->description = $description;
    }

    public function getUploader() {
        return $this->uploader;
    }

    public function setUploader($uploader) {
        $this->uploader = $uploader;
    }

    public function getUploadedDate() {
        return $this->uploadedDate;
    }

    public function setUploadedDate($uploadedDate) {
        $this->uploadedDate = $uploadedDate;
    }
    
    /**
     *
     * @param Integer $photoId
     * @param String $fileName
     * @param String $description
     * @param Integer $uploader
     * @param String $uploadedDate 
     */
    function __construct( $fileName, $description=NULL, $uploader=NULL, $uploadedDate=NULL, $photoId=NULL) {
        $this->photoId = $photoId;
        $this->fileName = $fileName;
        $this->description = $description;
        $this->uploader = $uploader;
        $this->uploadedDate = $uploadedDate;
    }
    public static $const_args = array(
                                    'fileName',
                                    '$description',
                                    'uploader',
                                    'uploadedDate',
                                    'photoId');   
}

?>
