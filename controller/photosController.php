<?php
class photosController extends baseController 
{
    public function index()
    {
        checkAuth();

        $this->log->debug("Inside " . __CLASS__ . " " . __FUNCTION__ . "()...");

        //SET TITLE FOR THE PAGE
        $this->registry->template->title='Upload Photo';
        if (isset($_FILES['file']))
        {
            if (($_FILES["file"]["type"] == "image/jpeg") && ($_FILES["file"]["size"] < 2048000))
            {
                if ($_FILES["file"]["error"] > 0)
                {
                        $this->log->debug("Return Code: " . $_FILES["file"]["error"]);
                }
                else
                {
                        $this->log->debug("Upload: " . $_FILES["file"]["name"]);
                        $this->log->debug("Type: " . $_FILES["file"]["type"]);
                        $this->log->debug("Size: " . ($_FILES["file"]["size"] / 2048));

                        $temp_file = $_FILES["file"]["tmp_name"];
                        $pathToImages = IMAGES."album/{$_SESSION['displayname']}/";
                        
                        if(!file_exists($pathToImages))
                        {
                            if(!mkdir($pathToImages))
                                die('Album could not be created for your account. Kindly contact administrator');
                        }
                        $new_name = substr($_FILES["file"]["name"],0,-4);
                        $new_name = substr($new_name,0,41).".jpg";
                 
                        if (file_exists($pathToImages.$new_name))
                        {
                                $new_name .= date('Y-m-d_H:i:s');
                                if(move_uploaded_file($temp_file, $pathToImages.$new_name))
                                        $this->log->debug("Photo added successfully...");
                        }
                        else
                        {
                                if(move_uploaded_file($temp_file, $pathToImages.$new_name))
                                        $this->log->debug("Photo added successfully...");
                        }
                        
                        // load image and get image size
                        $pathToThumbnails = IMAGES."album/{$_SESSION['displayname']}/thumbnails/";
				$img = imagecreatefromjpeg( IMAGES."album/{$_SESSION['displayname']}/{$new_name}" );
				$width = imagesx( $img );
				$height = imagesy( $img );
				
				// calculate thumbnail size
				$new_width;	$new_height;
				
				if($width >= $height)
				{
					$new_height = 100;
					$new_width = floor( $width * ( $new_height / $height ) );	
				}
				else
				{
					$new_width = 100;
					$new_height = floor( $height * ( $new_width / $width ) );
				}
                                // create a new temporary image
				$tmp_img = imagecreatetruecolor( $new_width, $new_height );
				
				// copy and resize old image into new image 
				//imagecopyresized( $tmp_img, $img, 0, 0, 0, 0, $new_width, $new_height, $width, $height );
                                imagecopyresampled($tmp_img, $img, 0, 0, 0, 0, $new_width, $new_height, $width, $height);
				
                                if(!is_dir($pathToThumbnails))
                                {
                                    if(!mkdir($pathToThumbnails))
                                    {
                                        unlink($pathToImages.$new_name);
                                        die('Thumbnails cannot be created. Kindly contact administrator!');                                           
                                    }
                                }
                                
				// save thumbnail into a file
				imagejpeg( $tmp_img, "{$pathToThumbnails}{$new_name}" );
                                
                                loadclass('Photo');
                                $photo = new Photo($new_name,NULL,$_SESSION['myid']);
                                loadclass('PhotoService');
                                $photoService = new PhotoService();

                                $photoId = $photoService->insertPhotoDetails($photo);
                                $this->log->debug("Photo Id : $photoId");
                                
                        header("location:index.php?rt=photos/index/file=$new_name&upload=1");
                }
            }
            else
                    $this->registry->template->message = "<span class='alert'>Note: Only JPEG files less than 2 MB can be uploaded</span>";
        }
        
        
        loadclass('PhotoService');
        $photoService = new PhotoService();
                
        $arr_photo = $photoService->getPhotosForUser($_SESSION['myid']);

        //SET TEMPLATE VARIABLES
        $this->registry->template->arr_photo = $arr_photo;

        if(isset($_GET['upload']) && $_GET['upload'] == 1 && isset($_GET['file']))
                $this->registry->template->message = "<span class='success'>Photo {$_GET['file']} uploaded successfully!</span>";

        //CALL THE VIEW FILE
        $this->registry->template->show();
    }
    
    public function view()
    {
        checkAuth();

        $this->log->debug("Inside " . __CLASS__ . " " . __FUNCTION__ . "()...");
        
        //SET TITLE FOR PAGE
        $this->registry->template->title="Photos";
		
        if(isset($_GET['album']))
        {
            $album = $_GET['album'];
            
            if(preg_match('/^[A-Z ]+$/i', $_GET['album']))
            {
                $this->log->debug('Crossed preg_match');
                $album_location = IMAGES."album/".$album;

                if(is_dir($album_location))
                {
                    $this->log->debug('Inside directory check');
                    $handle = opendir($album_location);

                    $arr_imgs = array();

                    while(false !== ($file = readdir($handle)))
                    {
                        $this->log->debug('Assigning array to curImg');
                        $curImg = array();
                        if($file != "." && $file != ".." && strpos($file, '.jpg',1))
                        {
                            $img = array();
                            $img['orig'] = "$album_location/$file";
                            $img['thumb'] = "$album_location/thumbnails/$file";
                            $arr_imgs[] = $img;
                        }
                                
                    }
                    closedir($handle);
                    $this->log->debug('Size of arr_imgs: '.count($arr_imgs));
                    //SET TEMPLATE VARIABLES
                    $this->registry->template->album = $album;
                    $this->registry->template->arr_image = $arr_imgs;
                }
                else
                {
                    $this->log->debug('Directory not found.'.$album_location);
                }
            }
        }
        else
            $this->registry->template->message = "<span class='alert'>Note: Album does not exist</span>";
        ////CALL THE VIEW FILE
        $this->registry->template->show();
    }
}
?>