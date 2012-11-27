<?php

Class error404Controller Extends BaseController {

public function index() 
{
    $this->log->debug("Inside " . __CLASS__ . " " . __FUNCTION__ . "()...");
    
    $this->registry->template->title = 'Page not found!';
    
    $this->registry->template->blog_heading = 'This is the 404';

    $this->registry->template->show();
}


}
?>
