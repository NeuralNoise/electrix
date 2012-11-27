<?php

class friendsController extends baseController
{
    public function index()
    {
        $this->log->debug("Inside " . __CLASS__ . " " . __FUNCTION__ . "()...");

        $this->registry->template->title='Friends List';
        
        if(!isset($_SESSION['myid']))
            Header("location:index.php");
        try
        {
            $cacheFile = 'friends_index_'.$_SESSION['myid'];
            $expirytime = 10800;
            if(!checkForCache($cacheFile, $expirytime))
            {
                $this->log->debug("About to fetch list of friends...");
                /*$count = 0;
                loadclass('Profile');
                $profile=new Profile();*/
                loadClass('ProfileService');
                $profileservice=new ProfileService();
                $arr_profile = $profileservice->viewFriendsList(intval($_SESSION['myid']));
                if (count($arr_profile)>0)
                {
                     $this->registry->template->arr_profile=$arr_profile;
                     $this->log->debug("no. of friends....".count($arr_profile));
                }
                else
                {
                    $this->log->debug("NO FRIENDS....");
                }
                
            }
            else
                $this->log->debug ('Fetching from cache...');
        }
        catch(PDOException $e)
        {
            $this->registry->template->message = "An error occurred! Please try again later!";
            $this->log->debug("PDOException while fetching list of friends: ".$e->getMessage());
        }
        catch(Exception $e)
        {
            $this->registry->template->message = "Unexpected error! Please report to administrator!";
            $this->log->debug("Unknown Exception while fetching list of friends:".$e->getMessage());
        }
            
	$this->registry->template->show($cacheFile, $expirytime);
        
        $this->log->debug("Peak memory usage in " . __CLASS__ . " " . __FUNCTION__ . "(): ".  memory_get_peak_usage(true)/1024 ." KB" );
    }
    public function view()
    {
        $this->log->debug("Inside " . __CLASS__ . " " . __FUNCTION__ . "()...");

        if(!isset($_SESSION['myid']))
            Header("location:index.php");

        $this->registry->template->title='View Friend';

        $profileId = preg_replace("#[^0-9]#", '', $_GET['id']);

        if($profileId != 0)
        {
            try
            {
            $this->log->debug("About to fetch details for friend id: ".$profileId."...");
           
            loadclass('ProfileService');
            $profileservice=new ProfileService();
            $this->log->debug("profile ID...in profile view function " . $profileId);
            $farr_profile=$profileservice->viewFriendProfile($profileId);
            $this->registry->template->farr_profile=$farr_profile;
            
            }
            catch(PDOException $e)
            {
                    $this->log->debug("PDO Exception while fetching profile details: ".$e->getMessage());
                    $this->registry->template->message = "<span class='alert'>No such friend found!</span>";
            }
            catch(Exception $e)
            {
                    $this->log->debug("Unknown exception while fetching profile details: ".$e->getMessage());
                    $this->registry->template->message = "Unexpected error! Please report to administrator!";
            }
        }
        
        if($_SESSION['logintype']=='admin')
        {
            loadclass('LoginService');
            $loginService = new LoginService();
            $_SESSION['lastlogin'] = $loginService->getLastLogin($profileId);
            $this->registry->template->show();
        }
        else
            $this->registry->template->show();
        
                $this->log->debug("Peak memory usage in " . __CLASS__ . " " . __FUNCTION__ . "(): " . memory_get_peak_usage(true) / 1024 . " KB");
    }
}