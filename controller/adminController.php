<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of AdminController
 *
 * @author nancy
 */
class adminController extends baseController {

    public function index() {
        $this->registry->template->title = TITLE_PREFIX.' - Admin login';
        $this->registry->template->css = array('electrix_login');
        $this->registry->template->show();
    }

    public function login() {
        if (isset($_POST['username']) && isset($_POST['password'])) {

            $allowed = array();

            $allowed[] = 'username';
            $allowed[] = 'password';
            $submitted = array_keys($_POST);
            if ($allowed != $submitted)
                $this->registry->template->message = 'Invalid form submission';
            else {
                try {
                    // converted to objects of target class
                    $this->log->debug('Inside try block');
                    loadClass('Admin');
                    $admin = new Admin($_POST['username'], $_POST['password']);
                    //converted object is sent to service class with the connection
                    loadClass('AdminService');
                    $adminservice = new AdminService();
                    //$this->log->debug($admin);  
                    $isAuthorized = $adminservice->passwordCheck($admin);
                    if ($isAuthorized) {
                        $_SESSION['myid'] = 'valid_admin';
                        $_SESSION['logintype'] = 'admin';
                        header("Location: index.php?rt=admin/welcome");
                    } else {
                        header("Location: index.php?rt=admin/index/msg/failure");
                    }
                } catch (Exception $e) {
                    $this->registry->template->message = $e;
                }
            }
        }
    }

    public function logout() {
        session_destroy();
        header("Location: index.php");
    }

    public function welcome() {
        //cheking for authorised users

        if (!isset($_SESSION['myid']))
            Header("location:index.php?rt=admin/index");


        loadClass('ProfilerequestService');
        $profilerequestservice = new ProfilerequestService();

        $arr_Profilerequest = $profilerequestservice->displayRegname();
        if (count($arr_Profilerequest) > 0)
            $this->registry->template->arr_profilerequest = $arr_Profilerequest;

        $this->registry->template->title = 'Welcome';
        $this->registry->template->show();
    }

    public function viewDetails() {
        //checking for authorised users

        if (!isset($_SESSION['myid']))
            Header("location:index.php?rt=admin/index");

        $this->log->debug("Inside " . __CLASS__ . " " . __FUNCTION__ . "()...");

        try {
            $this->log->debug("Inside try block");
            if (isset($_GET['request'])) {
                $this->log->debug("Processing Request...");
                //only numbers
                //min 10, max 15

                loadClass('ProfilerequestService');
                $profilerequestservice = new ProfilerequestService();
                $arr_profilerequest = $profilerequestservice->getProfilerequestDetails($_GET['request']);
                if (isset($arr_profilerequest)) {
                    foreach ($arr_profilerequest as $profilerequest) {
                        $this->log->debug("university Reg no." . $profilerequest->getUnivregnno());
                        $this->registry->template->profilerequest = $profilerequest;
                    }
                }
            }

            //add into profile and login
            if (isset($_POST['submit_action'])) {

                $this->log->debug("Processing submitted values...");
                if (($_POST['submit_action']) == 'accept') {
                    try {
                        loadClass('Profile');

                        $profile = new Profile(
                                        $_POST['fullname'],
                                        $_POST['displayname'],
                                        date('Y-m-d H:i:s', strtotime($_POST['dob'])),
                                        $_POST['email1'],
                                        '',
                                        $_POST['phone1'],
                                        '',
                                        $_POST['currentLocation'],
                                        $_POST['contactAddress'],
                                        1
                        );
                        loadClass('Profilerequest');
                        $profilerequest = new Profilerequest();
                        $profilerequest->validateFullname($_POST['fullname']);
                        $profilerequest->validateDob($_POST['dob']);
                        $profilerequest->validateEmail($_POST['email1']);
                        $profilerequest->validatePhone($_POST['phone1']);
                        $profilerequest->validateCurrentLocation($_POST['currentLocation']);
                        $profilerequest->validateContactAddress($_POST['contactAddress']);

                        loadClass('ProfileService');
                        $profileservice = new ProfileService();

                        $profileId = $profileservice->insertProfile($profile);

                        $password = substr($_POST['univregnno'], 0, 5) . substr($_POST['fullname'], 0, 3);

                        //add to login table
                        loadclass('Login');
                        $login = new Login(
                                        $_POST['univregnno'],
                                        md5($password),
                                        $profileId);
                        loadclass('LoginService');
                        $loginservice = new LoginService();
                        $loginservice->addLogin($login);


                        //remove approved profile requests

                        loadClass('ProfilerequestService');
                        $profilerequestservice = new ProfilerequestService();
                        $profilerequestservice->removeApprovedRequest($_POST['univregnno']);

                        $message = 'Welcome to Electrix!<br/>
                            Your profile request has been approved.<br/>
                            Username:' . $_POST['univregno'] . '<br/>
                            Password:' . $password;

                        $to = $_POST['email1'] . ', arun_maximus88@yahoo.co.in';

                        mail($to, 'Welcome to Electrix', $message, "FROM: ELECTRIX\r\nContent-type: text/html");

                        header("location: index.php?rt=admin/welcome/msg/success");
                    } catch (InvalidFullnameException $e) {
                        $this->log->debug("EXCEPTION: " . $e->getMessage());
                    } catch (InvalidDobException $e) {
                        $this->log->debug("EXCEPTION: " . $e->getMessage());
                    } catch (InvalidEmailException $e) {
                        $this->log->debug("EXCEPTION: " . $e->getMessage());
                    } catch (InvalidPhoneException $e) {
                        $this->log->debug("EXCEPTION: " . $e->getMessage());
                    } catch (InvalidCurrentLocationException $e) {
                        $this->log->debug("EXCEPTION: " . $e->getMessage());
                    } catch (InvalidContactAddressException $e) {
                        $this->log->debug("EXCEPTION: " . $e->getMessage());
                    } catch (Exception $e) {
                        $this->log->debug("EXCEPTION: " . $e->getMessage());
                    } catch (Exception $e) {
                        $this->registry->template->message = $e;
                    }
                } else {
                    // removal of rejected requests
                    loadClass('ProfilerequestService');
                    $profilerequestservice = new ProfilerequestService();
                    $profilerequestservice->removeApprovedRequest($_POST['univregnno']);

                    header("location: index.php?rt=admin/welcome/msg/failure");
                }
            } else {
                $this->registry->template->message = 'Profile Insertion failed';
            }
        } catch (Exception $e) {
            $this->registry->template->message = $e;
        }
        $this->registry->template->title = 'Request details';
        $this->registry->template->show();
    }

}

?>
