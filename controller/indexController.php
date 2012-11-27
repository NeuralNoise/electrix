<?php

Class indexController Extends baseController {

    public function index() {
        
        $this->log->debug("Inside " . __CLASS__ . " " . __FUNCTION__ . "()...");
        
        checkAuth();

        //SET TITLE
        $this->registry->template->title = "ELECTRIX";

        //cheking for authorised users
        if (!isset($_SESSION['myid']))
            Header("location:index.php?rt=login/index");


        //adding new status messages..
        if (isset($_POST['status_msg'])) {
            $this->log->debug("processing submitted status messages values...");
            $status = $_POST['status_msg'];

            if (strlen(str_replace(' ', '', $status)) > 0) {
                try {
                    $this->log->debug("Inside " . __CLASS__ . " " . __FUNCTION__ . "()...");
                    $this->log->debug("About to insert a new status msg...");

                    loadClass('Statusmsg');
                    $statusmsg = new Statusmsg($_POST['status_msg'], $_SESSION['myid'], date("Y-m-d H:i:s"));
                    loadClass('StatusmsgService');
                    $statusmsgservice = new StatusmsgService();
                    $this->log->debug("About to call the service class...");
                    $statusId = $statusmsgservice->insertStatusmsg($statusmsg);
                    $this->log->debug("$statusId:");
                    $_SESSION['status'] = stripslashes($status);
                    header("Location: index.php");
                } catch (Exception $e) {
                    $this->log->debug("Unknown Exception while updating status msg:" . $e->getMessage());
                }
            }
            else
                $this->registry->template->message = "<span class='alert'>Set some meaningful status msg!</span>";
        }

        try {
            // retrieving latest status message

            loadclass('Statusmsg');
            $statusmsg = new Statusmsg();

            loadclass('StatusmsgService');
            $statusmsgservice = new StatusmsgService();

            $arr_statusmsg = $statusmsgservice->retrieveStatusmsg($_SESSION['myid']);

            if (isset($arr_statusmsg)) {
                foreach ($arr_statusmsg as $statusmsg) {
                    $_SESSION['statusmsg'] = stripslashes($statusmsg->getStatus());
                    $this->log->debug("Retrieved the latest status msg: " . $_SESSION['statusmsg']);
                }
            }

            //Retrieve Birthday details
            loadclass('ProfileService');
            $ProfileService = new ProfileService();

            $arr_birthdayVO = $ProfileService->retrieveBirthdays($_SESSION['myid']);

            if (count($arr_birthdayVO) > 0)
                $this->registry->template->arr_birthdayVO = $arr_birthdayVO;

            //Retrieve Updates

            $arr_updatesVOstatus = $ProfileService->getStatusUpdates($_SESSION['myid']);

            if (count($arr_updatesVOstatus) > 0) {
                $this->registry->template->arr_updatesVOstatus = $arr_updatesVOstatus;

                $csv_statusid = '';
                foreach ($arr_updatesVOstatus as $updatesVOstatus) {
                    /* @var $updatesVOstatus UpdatesVOStatus */
                    if (!preg_match("/{$updatesVOstatus->getStatusId()}/", $csv_statusid)) {
                        if ($csv_statusid == '')
                            $csv_statusid = $updatesVOstatus->getStatusId();
                        else
                            $csv_statusid .= ',' . $updatesVOstatus->getStatusId();
                    }
                }

                //Retrieve comments for status and photos
                loadClass('CommentService');
                $commentService = new CommentService();
                $arr_updatesVOComments = $commentService->getCommentsForStatus($csv_statusid, $_SESSION['myid']);
                $arr_allComments = array();
                foreach ($arr_updatesVOComments as $updatesVOComment) {
                    $arr_allComments[$updatesVOComment->getItemId()][] = $updatesVOComment;                    
                }
                $this->registry->template->arr_allComments=$arr_allComments;
            }
        } catch (Exception $e) {
            unset($_SESSION['myid']);
            $this->log->debug("Exception while fetching latest status message..." . $e->getMessage());
        }

        if ($_SESSION['logintype'] == 'admin')
            $this->registry->template->show();
        if ($_SESSION['logintype'] == 'user')
            $this->registry->template->show();

        $this->log->debug("Peak memory usage in " . __CLASS__ . " " . __FUNCTION__ . "(): " . memory_get_peak_usage(true) / 1024 . " KB");
    }

    public function getOlderPosts() {
        $this->log->debug("Inside " . __CLASS__ . " " . __FUNCTION__ . "()...");

        checkAuth();

        if (isset($_GET['offset_count'])) {
            $this->log->debug('Processing offset...');

            $offset = (POSTS_COUNT * intval($_GET['offset_count'])) + 1;
            loadClass('ProfileService');
            $ProfileService = new ProfileService();

            $arr_updatesVOstatus = $ProfileService->getStatusUpdates($_SESSION['myid'], $offset, POSTS_COUNT);

            if (count($arr_updatesVOstatus) > 0) {
                $this->registry->template->arr_updatesVOstatus = $arr_updatesVOstatus;

                $csv_statusid = '';
                foreach ($arr_updatesVOstatus as $updatesVOstatus) {
                    /* @var $updatesVOstatus UpdatesVOStatus */
                    if (!preg_match("/{$updatesVOstatus->getStatusId()}/", $csv_statusid)) {
                        if ($csv_statusid == '')
                            $csv_statusid = $updatesVOstatus->getStatusId();
                        else
                            $csv_statusid .= ',' . $updatesVOstatus->getStatusId();
                    }
                }

                //Retrieve comments for status and photos
                loadClass('CommentService');
                $commentService = new CommentService();

                $arr_updatesVOComments = $commentService->getCommentsForStatus($csv_statusid, $_SESSION['myid']);

                $prevStatusid = 0;
                foreach ($arr_updatesVOComments as $updatesVOComments) {
                    /* @var $updatesVOComments UpdatesVOComment */
                    $arrayName = 'arr_commentsForStatus_' . $updatesVOComments->getItemId();

                    if ($prevStatusid != $updatesVOComments->getItemId()) {
                        /* create array */
                        $$arrayName = array();
                    }

                    ${$arrayName}[] = $updatesVOComments;

                    $this->registry->template->$arrayName = ${$arrayName};

                    $prevStatusid = $updatesVOComments->getItemId();
                }
            }
            $this->registry->template->show();
        } else {
            if (!empty($_GET)) {
                $arr_get = array_keys($_GET);
                foreach ($arr_get as $get)
                    $this->log->debug("GET: $get");
            }
            $this->log->debug('offset count not set');
        }
    }

    //AJAX
    public function commentOnStatus() {
        
        $this->log->debug("Inside " . __CLASS__ . " " . __FUNCTION__ . "()...");
        
        checkAuth();

        if (!empty($_POST)) {
            $allowed = array();
            $allowed[] = 'status_id';
            $allowed[] = 'comment';

            $submitted = array_keys($_POST);

            try {
                if ($submitted != $allowed)
                    throw new InvalidArgumentException('Invalid form submission!', 99);

                /* VALIDATE STATUS ID */
                if (!preg_match("/^[0-9]+$/", $_POST['status_id']))
                    throw new InvalidArgumentException('Invalid Status Id', 98);

                loadClass('Comment');
                $comment = new Comment('status', $_POST['status_id'], htmlspecialchars($_POST['comment']), $_SESSION['myid']);

                loadClass('CommentService');
                $commentService = new CommentService();

                echo $commentService->addComment($comment);
            } catch (Exception $e) {
                $this->log->debug('EXCEPTION: ' . $e->getMessage());

                if ($e->getCode() == 99 || $e->getCode() == 98)
                    echo 'E_' . $e->getCode() . '::' . $e->getMessage();
                else
                    echo('E_::Unknown error occurred!');
            }
        }
        $this->log->debug("Peak memory usage in " . __CLASS__ . " " . __FUNCTION__ . "(): " . memory_get_peak_usage(true) / 1024 . " KB");
    }

    //AJAX
    public function deleteComment() {
        $this->log->debug("Inside " . __CLASS__ . " " . __FUNCTION__ . "()...");

        if (isset($_POST['comment_id'])) {
            try {
                //delete comments                
                if (!preg_match("/^[0-9]+$/", $_POST['comment_id']))
                    throw new InvalidArgumentException('Invalid Comment Id', 98);

                loadClass('CommentService');
                $commentService = new CommentService();

                echo $commentService->deleteComment($_POST['comment_id'], $_SESSION['myid']);
            } catch (Exception $e) {
                $this->log->debug('EXCEPTION: ' . $e->getMessage());

                if ($e->getCode() == 98)
                    echo 'E_98::' . $e->getMessage();
                else
                    echo('E_::Unknown error occurred!');
            }
        }
        $this->log->debug("Peak memory usage in " . __CLASS__ . " " . __FUNCTION__ . "(): " . memory_get_peak_usage(true) / 1024 . " KB");
    }

    //AJAX
    public function deleteStatus()
    {
        $this->log->debug("Inside " . __CLASS__ . " " . __FUNCTION__ . "()...");
        
        checkAuth();
        
        if (isset($_POST['status_id'])) 
          {        
            try {
                //Checking if the posted status id and session status_id are equal to reset the status message
                if($_POST['status_id'] == $_SESSION['statusId'])
                {
                    $_SESSION['status']=NULL;   
                    $_SESSION['statusId']=NULL;
                    $this->log->debug('Inside if block and status msg: '.$_SESSION['status']);
                }
                //delete status
                if (!preg_match("/^[0-9]+$/", $_POST['status_id']))
                    throw new InvalidArgumentException('Invalid delete Id', 98);
                
                    loadClass('StatusmsgService');
                    $statusmsgService = new StatusmsgService();
                    
                    echo $statusmsgService->deleteStatusmsg($_POST['status_id'], $_SESSION['myid']);
            }   catch (Exception $e) {
                $this->log->debug('EXCEPTION: ' . $e->getMessage());

                if ($e->getCode() == 98)
                    echo 'E_98::' . $e->getMessage();
                else
                    echo('E_::Unknown error occurred!');
            }                    
                    
        }
    }
    
    //AJAX
    public function like() {
        $this->log->debug("Inside " . __CLASS__ . " " . __FUNCTION__ . "()...");
        
        checkAuth();

        if (!empty($_POST)) {
            try {
                $allowed = array();
                $allowed[] = 'type';
                $allowed[] = 'item_id';
                $this->log->debug('Type:'.$_POST['type']);
                $this->log->debug('Item_id:'.$_POST['item_id']);

                $submitted = array_keys($_POST);

                if ($allowed != $submitted) {
                    foreach ($submitted as $keys) {
                        $this->log->debug($keys);
                    }
                    throw new InvalidArgumentException('Invalid form submission', 99);
                }

                if (!preg_match('/^[0-9]+$/', $_POST['item_id']))
                    throw new InvalidArgumentException('Invalid Item id', 98);

                if ($_POST['type'] != 'comment' && $_POST['type'] != 'status')
                    throw new InvalidArgumentException('Invalid type', 98);

                loadClass('Like');
                $like = new Like($_POST['type'], $_POST['item_id']);

                loadClass('LikeService');
                $likeService = new LikeService();

                $likeService->addLike($like, $_SESSION['myid']);
                echo '1';
            } catch (InvalidArgumentException $e) {
                $this->log->debug('Exception: ' . $e->getMessage());
                echo('E_' . $e->getCode() . '::' . $e->getMessage());
            } catch (Exception $e) {
                $this->log->debug('Exception: ' . $e->getMessage());
                echo('E_::Unknown error occured');
            }
        }
    }

    //AJAX
    public function unlike() {
        $this->log->debug("Inside " . __CLASS__ . " " . __FUNCTION__ . "()...");
        
        checkAuth();

        if (!empty($_POST)) {
            try {
                $allowed = array();
                $allowed[] = 'type';
                $allowed[] = 'item_id';

                $submitted = array_keys($_POST);

                if ($allowed != $submitted) {
                    foreach ($submitted as $keys) {
                        $this->log->debug($keys);
                    }
                    throw new InvalidArgumentException('Invalid form submission', 99);
                }

                if (!preg_match('/^[0-9]+$/', $_POST['item_id']))
                    throw new InvalidArgumentException('Invalid Item id', 98);

                if ($_POST['type'] != 'comment' && $_POST['type'] != 'status')
                    throw new InvalidArgumentException('Invalid type', 98);

                loadClass('Like');
                $like = new Like($_POST['type'], $_POST['item_id']);

                loadClass('LikeService');
                $likeService = new LikeService();

                echo $likeService->unlike($like, $_SESSION['myid']);
            } catch (InvalidArgumentException $e) {
                $this->log->debug('Exception: ' . $e->getMessage());
                echo('E_' . $e->getCode() . '::' . $e->getMessage());
            } catch (Exception $e) {
                $this->log->debug('Exception: ' . $e->getMessage());
                echo('E_::Unknown error occured');
            }
        }
    }

    //AJAX
    public function getLikeDetails() {
        $this->log->debug("Inside " . __CLASS__ . " " . __FUNCTION__ . "()...");
            
        checkAuth();
        
        if (!empty($_POST)) {
            try {
                $allowed = array();
                $allowed[] = 'type';
                $allowed[] = 'item_id';

                $submitted = array_keys($_POST);

                if ($allowed != $submitted) {
                    foreach ($submitted as $keys) {
                        $this->log->debug($keys);
                    }
                    throw new InvalidArgumentException('Invalid form submission', 99);
                }

                if (!preg_match('/^[0-9]+$/', $_POST['item_id']))
                    throw new InvalidArgumentException('Invalid Item id', 98);

                if ($_POST['type'] != 'comment' && $_POST['type'] != 'status')
                    throw new InvalidArgumentException('Invalid type', 98);

                loadClass('Like');
                $like = new Like($_POST['type'], $_POST['item_id']);

                loadClass('LikeService');
                $likeService = new LikeService();

                $arr_likeDetailsVO = $likeService->getlikedetails($like);

                $JSON_likeDetailsVO = '{ "like_details" : [';

                foreach ($arr_likeDetailsVO as $likeDetailsVO) {
                    /* @var $likeDetailsVO LikeDetailsVO */
                    if ($JSON_likeDetailsVO != '{ "like_details" : [')
                        $JSON_likeDetailsVO .= ',';

                    $JSON_likeDetailsVO .= '{';

                    $JSON_likeDetailsVO .= '"profile_id":"' . $likeDetailsVO->getProfileId() . '", ';
                    $JSON_likeDetailsVO .= '"displayname":"' . $likeDetailsVO->getDisplayname() . '"';

                    $JSON_likeDetailsVO .= '}';
                }
                $JSON_likeDetailsVO .= '] }';

                echo $JSON_likeDetailsVO;
            } catch (InvalidArgumentException $e) {
                $this->log->debug('Exception: ' . $e->getMessage());
                echo('E_' . $e->getCode() . '::' . $e->getMessage());
            } catch (Exception $e) {
                $this->log->debug('Exception: ' . $e->getMessage());
                echo('E_::Unknown error occured');
            }
        }
    }

    /**
     * @author microscopicearthling
     * @link http://forum.jquery.com/topic/prevent-ampersand-from-splitting-up-data-string-during-ajax
     * @param String $str
     * @return String 
     */
    private function utf8_urldecode($str) {
        $str = preg_replace("/%u([0-9a-f]{3,4})/i", "&#x\\1;", urldecode($str));
        return html_entity_decode($str, null, 'UTF-8');
    }

}

?>
