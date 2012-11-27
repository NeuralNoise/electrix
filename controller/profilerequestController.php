<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of profilerequestController
 *
 * @author admin
 */
class profilerequestController extends baseController 
{    
    public function index()
    {
        $this->log->debug("Inside " . __CLASS__ . " " . __FUNCTION__ . "()...");
        
        $this->registry->template->title="Signup";
        
        if(isset($_POST['univregnno']))
        {
            $this->log->debug("Processing submitted values...");
            
            $allowed=array();
            $allowed[]='univregnno';
            $allowed[]='fullname';
            $allowed[]='dob';
            $allowed[]='email';
            $allowed[]='phone';
            $allowed[]='currentLocation';
            $allowed[]='contactAddress';

            $submitted=array_keys($_POST);
            
            try
            {
                if($allowed != $submitted)
                    throw new Exception ('Invalid form submission', 99);
                
                loadClass('Profilerequest');
                $profilerequest = new Profilerequest($_POST['univregnno'],$_POST['fullname'],$_POST['dob'],$_POST['email'],$_POST['phone'],$_POST['currentLocation'],$_POST['contactAddress']);
                
                $this->log->debug("University regnno:".$_POST['univregnno']);
                //vli
                $profilerequest->validateUniversityRegnNo($_POST['univregnno']);
                $profilerequest->validateFullname($_POST['fullname']);
                $profilerequest->validateDob($_POST['dob']);
                $profilerequest->validateEmail($_POST['email']);
                $profilerequest->validatePhone($_POST['phone']);
                $profilerequest->validateCurrentLocation($_POST['currentLocation']);
                $profilerequest->validateContactAddress($_POST['contactAddress']);
                
                
                /*$checkExistingID=$loginservice->checkExistingID($this->registry->db, $_POST['univregnno']);
                if(($checkExistingID)==true)
                {
                    throw new InvalidUnivRegnNoException('Profile already exist');
                }
               */
                
                loadclass('ProfilerequestService');
                $profilerequestservice = new ProfilerequestService();
                
                $profilerequestservice->insertProfile($profilerequest);         
                
                $this->registry->template->message = 'Request has been sent to the admin for approval';         
                
            }
            catch(InvalidUnivRegnNoException $e)
            {
                $this->log->debug("EXCEPTION: ".$e->getMessage());
                $this->registry->template->err_message = $e->getMessage();
                
                $this->registry->template->profilerequest = $profilerequest;
            }
            catch(InvalidFullnameException $e)
            {
                $this->log->debug("EXCEPTION: ".$e->getMessage());
                $this->registry->template->err_message = $e->getMessage();
                
                $this->registry->template->profilerequest = $profilerequest;
            }
            catch(InvalidDobException $e)
            {
                $this->log->debug("EXCEPTION: ".$e->getMessage());
                $this->registry->template->err_message = $e->getMessage();
                
                $this->registry->template->profilerequest = $profilerequest;
            }
            catch(InvalidEmailException $e)
            {
                $this->log->debug("EXCEPTION: ".$e->getMessage());
                $this->registry->template->err_message = $e->getMessage();
                
                $this->registry->template->profilerequest = $profilerequest;
            }    
            catch(InvalidPhoneException $e)
            {
                $this->log->debug("EXCEPTION: ".$e->getMessage());
                $this->registry->template->err_message = $e->getMessage();
                
                $this->registry->template->profilerequest = $profilerequest;
            }
            catch(InvalidCurrentLocationException $e)
            {
                $this->log->debug("EXCEPTION: ".$e->getMessage());
                $this->registry->template->err_message = $e->getMessage();
                
                $this->registry->template->profilerequest = $profilerequest;
            }
            catch(InvalidContactAddressException $e)
            {
                $this->log->debug("EXCEPTION: ".$e->getMessage());
                $this->registry->template->err_message = $e->getMessage();
                
                $this->registry->template->profilerequest = $profilerequest;
            }
            catch(PDOException $e)
            {
                $this->log->debug($e->getMessage());
                if($e->getCode()==23000)
                    $this->registry->template->err_message='Profile already exists';
                
                $this->registry->template->profilerequest = $profilerequest;
            }
            catch(Exception $e)
            {
                $this->log->debug("EXCEPTION: ".$e->getMessage());
                
                $this->registry->template->profilerequest = $profilerequest;
            }
        }
        
        $this->registry->template->show();       
   }
}

?>
