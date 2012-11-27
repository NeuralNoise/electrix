<?php

class loginController extends baseController
{
    public function index()
    {
        $this->log->debug("inside index of login controller");
                
        $this->registry->template->title = "Login";
        
        $this->registry->template->css = array('electrix_login');
        
        if(isset($_POST['username']) && isset($_POST['password']))
        {
            $this->log->debug("Processing submitted values...");
            
            $allowed = array();
            
            $allowed[] = 'username';
            $allowed[] = 'password';
            $submitted = array_keys($_POST);
            
            try
            {
                if($allowed != $submitted)
                    throw new Exception ('Invalid form submission', 99);
                
                // converted to objects of target class
                loadClass('Login');
                $login = new Login($_POST['username'], md5($_POST['password']));
                
                //converted object is send to service class with the connection
                loadClass('LoginService');
                $loginservice=new LoginService();
                $profileId = $loginservice->passwordCheck($login);
                
                if($profileId <= 0)
                    throw new Exception('Invalid username or password!', 99);
                
                //SETTING PROFILE-ID IN SESSION
                $_SESSION['myid'] = $profileId;
                $_SESSION['logintype'] = 'user';
                //GET PROFILE PIC DIMENSIONS AND SET RESIZE FACTOR
                $this->log->debug("inside cookie");
                $imgloc = IMAGES."profilepics/";
                
                if(file_exists($imgloc.$_SESSION["myid"].".jpg"))
                    $img = imagecreatefromjpeg( $imgloc.$_SESSION['myid'].".jpg" );
                else
                    $img = imagecreatefromjpeg( $imgloc."default.jpg" );
                if(imagesx( $img ) >= imagesy( $img ))
                        setcookie("profile_pic_resize", "height");
                else
                        setcookie("profile_pic_resize", "width");
                
                //redirect to home page
                header("Location: index.php");
                
                //GET DISPLAYNAME, LAST STATUS MSG AND CURRENT LOCATION
                $resultSet = $loginservice->getUserDetail($_SESSION['myid']);
                $_SESSION['displayname'] = $resultSet['displayname'];
                $_SESSION['currentLocation'] = $resultSet['currentLocation'];
                $_SESSION['status'] = $resultSet['status'];
                $_SESSION['statusId'] = $resultSet['statusId'];                
                $this->log->debug("Session variables set... Name: {$resultSet['displayname']}");
            }
            catch(Exception $e)
            {
                $this->log->debug("EXCEPTION: ".$e->getMessage());
                
                if($e->getCode() == 99)
                    $this->registry->template->message = $e->getMessage();
                else
                    $this->registry->template->message = 'Unknown error occurred!';
            }            
        }
        $this->registry->template->show();
    }
    
    public function logout()
    {
        session_destroy();
        header("Location: index.php?rt=login"); 
    }
}
?>
