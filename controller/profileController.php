<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of profileController
 *
 * @author Webinative Tech
 
 */
class profileController extends baseController 
{
    public function index()
    {
       checkAuth();
       
       $this->log->debug("Inside " . __CLASS__ . " " . __FUNCTION__ . "()...");
       
       $this->registry->template->title = "PROFILE";
       
       $this->registry->template->css = array('electrix_profile');
       
       loadclass('ProfileService');
       $ProfileService = new ProfileService();
              
       $profile=$ProfileService->viewProfile($_SESSION['myid']);
       
       $this->registry->template->profile=$profile;
       $this->registry->template->show();       
    }
    
    public function edit()
    {
       checkAuth();
       
       $this->log->debug("Inside " . __CLASS__ . " " . __FUNCTION__ . "()...");
              
       $this->registry->template->title = 'Profile - Edit';
       
       loadclass('ProfileService');
       $ProfileService = new ProfileService();
             
       if(isset($_POST['email1']))
       {
           $this->log->debug("Processing submitted values...");
           /*foreach(array_keys($_POST) as $postedVal)
           {
               $this->log->debug("$postedVal : ".$_POST[$postedVal]);
           }*/           
           $allowed1 = array();
           $allowed2 = array();
           
           $allowed1[] = 'fullname';
           $allowed1[] = 'currentLocation';
           $allowed1[] = 'email1';
           $allowed1[] = 'email2';
           $allowed1[] = 'phone1';
           $allowed1[] = 'phone2';
           $allowed1[] = 'contactAddress';
           
           $allowed2[] = 'fullname';
           $allowed2[] = 'currentLocation';
           $allowed2[] = 'email1';
           $allowed2[] = 'email2';
           $allowed2[] = 'phone1';
           $allowed2[] = 'phone2';
           $allowed2[] = 'contactAddress';
           $allowed2[] = 'notify';
           
           $submitted = array_keys($_POST);
            if($allowed1==$submitted || $allowed2 == $submitted)
            {
                
                loadclass('Profile');
                $profile= new Profile( $_POST['fullname'],
                                null,
                                null, 
                                $_POST['email1'],
                                $_POST['email2'],
                                $_POST['phone1'],
                                $_POST['phone2'],
                                $_POST['currentLocation'],
                                $_POST['contactAddress'],
                                (isset($_POST['notify'])) ? 1 : 0,
                                $_SESSION['myid']
                 );
         
                loadclass('ProfileService');
                $ProfileService = new ProfileService();
                $this->log->debug('Profile ID: '.$_SESSION['myid']);
                $ProfileService->updateProfile($profile);
            }
            else
            {
                 $this->registry->template->message= 'Invalid form submission';
            }           
       }       
       $profile=$ProfileService->viewProfile($_SESSION['myid']);
       //$arr_profile=$ProfileService->viewProfile($_SESSION['myid']);
       
       $this->registry->template->profile=$profile;            
       $this->registry->template->show();            
    }
    
   //Changing the existing password
    public function changePwd()
	{
		checkAuth();
		
		$this->log->debug("Inside profileController... changePwd()");
		
		//SET TITLE FOR THE PAGE
		$this->registry->template->title='Change Password';
		
		//CHECK FOR FORM SUBMISSION
		if(isset($_POST['existing_pwd']) && isset($_POST['new_pwd']) && isset($_POST['retype_pwd']))
		{
                        $existingPwd = filter_var($_POST['existing_pwd'],FILTER_SANITIZE_STRING);
			$newPwd = filter_var($_POST['new_pwd'],FILTER_SANITIZE_STRING);
			$retypePwd = filter_var($_POST['retype_pwd'],FILTER_SANITIZE_STRING);
			
			$validInputs = 1;
			
			if(strlen(str_replace(' ','',$newPwd)) == 0)
			{
                                $validInputs = 0;
				$this->registry->template->message = "<span class='alert'>Password cannot contain white spaces alone!</span>";
			}
			
			if(strlen($newPwd) < 6)
			{
				$validInputs = 0;
				$this->registry->template->message = "<span class='alert'>Password should be minimum 6 characters long!</span>";
			}
			
			if($newPwd != $retypePwd)
			{
                                $validInputs = 0;
				$this->registry->template->message = "<span class='alert'>Passwords does not match!</span>";
			}
			
			if($validInputs)
			{
				try
				{	
                                    loadclass('LoginService');
                                    $loginservice= new LoginService();
                                    $count=$loginservice->changePassword($newPwd, $existingPwd,$_SESSION['myid']);
					if($count == 0)
					{
						$this->registry->template->message = "<span class='alert'>Invalid existing password</span>";
					}
					else
						$this->registry->template->message = "<span class='success'>Password changed successfully</span>";
				}
				catch(PDOException $e)
				{
					$this->log->debug("PDOException while changing password: ".$e->getMessage());
				}
				catch(Exception $e)
				{
					$this->log->debug("Unknown Exception while changing password: ".$e->getMessage());
				}
			}
		}
		//CALL THE VIEW FILE
		$this->registry->template->show();
	}
        
    public function changePic()
	{
		checkAuth();
		
		$this->log->debug("Inside profileController... changePic()");
		
		//SET TITLE FOR THE PAGE
		$this->registry->template->title='Change display picture';
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
				$this->log->debug("Size: " . ($_FILES["file"]["size"] / 1024));
			
				$temp_file = $_FILES["file"]["tmp_name"];
				$pathToImages = IMAGES."profilepics/";
                                $uncroppedVer = $_SESSION['myid']."_uncropped.jpg";
				$new_name = $_SESSION['myid'].".jpg";
				
                                if (file_exists($pathToImages.$uncroppedVer))
				{
					unlink($pathToImages.$uncroppedVer);
					if(move_uploaded_file($temp_file, $pathToImages.$uncroppedVer))
						$this->log->debug("Uncropped Profile pic replaced successfully...");
				}
				else
				{
					if(move_uploaded_file($temp_file, $pathToImages.$uncroppedVer))
						$this->log->debug("Uncropped Profile pic added successfully...");
				}
                                
				if (file_exists($pathToImages.$new_name))
				{
					unlink($pathToImages.$new_name);
					if(move_uploaded_file($temp_file, $pathToImages.$new_name))
						$this->log->debug("Profile pic replaced successfully...");
				}
				else
				{
					if(move_uploaded_file($temp_file, $pathToImages.$new_name))
						$this->log->debug("Profile pic added successfully...");
				}
							
				// load image and get image size
				$img = imagecreatefromjpeg( "{$pathToImages}{$uncroppedVer}" );
				$width = imagesx( $img );
				$height = imagesy( $img );
				
				// calculate thumbnail size
				$new_width;	$new_height;
				
				if($width >= $height)
				{
					$new_height = 100;
					$new_width = floor( $width * ( $new_height / $height ) );
					setcookie("profile_pic_resize", "height");//update cookie value
				}
				else
				{
					$new_width = 100;
					$new_height = floor( $height * ( $new_width / $width ) );
					setcookie("profile_pic_resize", "width");//update cookie value
				}
				
				// create a new temporary image
				$tmp_img = imagecreatetruecolor( $new_width, $new_height );
				
				// copy and resize old image into new image 
				//imagecopyresized( $tmp_img, $img, 0, 0, 0, 0, $new_width, $new_height, $width, $height );
                                imagecopyresampled($tmp_img, $img, 0, 0, 0, 0, $new_width, $new_height, $width, $height);
				
				// save thumbnail into a file
				imagejpeg( $tmp_img, "{$pathToImages}{$new_name}" );
				
				Header("location:index.php?rt=profile/crop");
			}
                    }
                    else
                            $this->registry->template->message = "<span class='alert'>Note: Only JPEG files less than 400 KB can be uploaded</span>";
                }
	  	
		if(isset($_GET['upload']) && $_GET['upload'] == 1)
			$this->registry->template->message = "<span class='success'>Profile pic uploaded successfully!</span>";
	
		//CALL THE VIEW FILE
		$this->registry->template->show();
	}
        
    public function crop()
        {
            $this->log->debug("Inside ".__CLASS__."... ".__FUNCTION__."()");
            
            $this->registry->template->title = 'Crop Profile Pic';
            
            if(!empty($_POST))
            {
                /* @todo Validate form submission */
                
                $pathToImages = IMAGES."profilepics/";

                // Original image
                $filename = $_SESSION['myid'].'_uncropped.jpg';

                $new_filename = $_SESSION['myid'].'.jpg';

                // Get dimensions of the original image
                list($current_width, $current_height) = getimagesize($pathToImages.$filename);

                // The x and y coordinates on the original image where we
                // will begin cropping the image, taken from the form
                $x1   = $_POST['x1'];
                $y1   = $_POST['y1'];
                $x2   = $_POST['x2'];
                $y2   = $_POST['y2'];
                $w    = $_POST['w'];
                $h    = $_POST['h'];     

                //die(print_r($_POST));

                // This will be the final size of the image
                $crop_width = 100;
                $crop_height = 100;

                // Create our small image
                $new = imagecreatetruecolor($crop_width, $crop_height);
                // Create original image
                $current_image = imagecreatefromjpeg($pathToImages.$filename);
                // resamling (actual cropping)
                imagecopyresampled($new, $current_image, 0, 0, $x1, $y1, $crop_width, $crop_height, $w, $h);
                // creating our new image

                /*
                if(file_exists($pathToImages.$new_filename))
                    unlink($pathToImages.$new_filename);
                */
                imagejpeg($new, $pathToImages.$new_filename, 95);
                
                header('Location: index.php');
            }
            
            $this->registry->template->show();
        }
    public function suggestName()
        {
           //checkAuth();
           
           $this->log->debug("Inside " . __CLASS__ . " " . __FUNCTION__ . "()...");
           try
           {
               $cacheFile = __SITE_PATH . '/temp/cache/getnameSuggestion.txt';
               
               $expireTime = 10800;
               if ( file_exists ( $cacheFile ) && filemtime ( $cacheFile ) >( time() - $expireTime ) ):
                   echo file_get_contents( $cacheFile );
               else:
                   loadclass('Profile');
               
                   loadClass('ProfileService');
                   $profileservice=new ProfileService();
                   $arr_getSuggestions = $profileservice->getNameSuggestion();               
                   $JSONstring = json_encode($arr_getSuggestions);
                   
                   $this->log->debug("Creating cache... ".$cacheFile);

                   $fp = fopen( $cacheFile , 'w' );
                   fwrite( $fp , $JSONstring);
                   fclose( $fp);
                   
                   echo $JSONstring;
               endif;      
           }
           catch(exception $e)
           {
               $this->log->debug("Unknown exception while suggesting names: ".$e->getMessage());
               $this->registry->template->message = "Unexpected error! Please report to administrator!";
           }
        }
}
?>
