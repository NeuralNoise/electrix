<?php

class Router
{
    /** @var Registry */
    private $registry;

    /** @var Log */
    private $log;
    
    private $path;

    private $args = array();

    public $file;

    public $controller;

    public $action; 

    /**
     *
     * @param Registry $registry
     * @param Log $log 
     */
    function __construct($registry, $log)
    {
        $this->registry = $registry;
        $this->log = $log;
    }

    /**
    *
    * @set controller directory path
    *
    * @param string $path
    *
    * @return void
    *
    */
    function setPath($path)
    {
        /*** check if path is a directory ***/
        if (is_dir($path) == false)
        {
            throw new Exception ('Invalid controller path: `' . $path . '`');
        }
        /*** set the path ***/
        $this->path = $path;
    }


    /**
     * 
     */
    public function loader()
    {
        /*** check the route ***/
        $this->getController();

        /*** if the file is not there diaf ***/
        if (is_readable($this->file) == false)
        {
                $this->file = $this->path.'/error404.php';
                $this->controller = 'error404';
        }

        /*** include the controller ***/
        include $this->file;

        /*** a new controller class instance ***/
        $class = $this->controller . 'Controller';
        $this->log->debug("Controller class: ".$class);
        $controller = new $class($this->registry, $this->log);
        

        /*** check if the action is callable ***/
        if (is_callable(array($controller, $this->action)) == false)
        {
                $action = 'index';
                $this->action = 'index';
        }
        else
        {
                $action = $this->action;
        }
        /*** run the action ***/
        $controller->$action();
    }


    /**
     * 
     */
    private function getController()
    {

        /*** get the route from the url ***/
        $route = (empty($_GET['rt'])) ? '' : $_GET['rt'];

        if (empty($route))
        {
                $route = 'index';
        }
        else
        {
            /*** get the parts of the route ***/
            $parts = explode('/', $route);

            if(isset($parts[0]))
            {
                //SETTING CONTROLLER
                $this->controller = $parts[0];
            }

            if(isset( $parts[1]))
            {
                //SETTING ACTION
                $this->action = str_replace('-','',$parts[1]);  /* Ignore hyphens in url */
            }

            $partsCount = count($parts);
            
            for($i=2; $i < $partsCount; $i=$i+2)
            {
                //SETTING GET PARAMETERS
                if(array_key_exists($i+1, $parts))
                    $_GET[$parts[$i]] = $parts[($i+1)];
                else
                    $_GET[$parts[$i]] = "";
                
                $this->log->debug("GET Parameter : {$parts[$i]} = {$_GET[$parts[$i]]}");
            }
        }

        $this->log->debug("Route: ".$route);

        if (empty($this->controller))
        {
                $this->controller = 'index';
        }

        $this->log->debug("Controller: ".$this->controller);

        /*** Get action ***/
        if (empty($this->action))
        {
                $this->action = 'index';
        }

        $this->log->debug("Action: ".$this->action);

        /*** set the file path ***/
        $this->file = $this->path .'/'. $this->controller . 'Controller.php';
    }
}

?>
