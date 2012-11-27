<?php

class Template
{
    /** @var Registry */
    private $registry;
    
    private $vars = array();

    /**
     *
     * @param Registry $registry 
     */
    function __construct($registry)
    {
        $this->registry = $registry;
    }

    public function __set($index, $value)
    {
        $this->vars[$index] = $value;
    }

    /**
    *
    * @param string $cacheFile
    * @param int $expiryTime
    * @return boolean
    * @throws Exception 
    */
    function show($cacheFile=NULL, $expiryTime='86400')
    {
        require_once 'includes/commonFunctions.inc.php';
        
        $viewFile = $this->registry->router->controller . '/' . $this->registry->router->action;
        
        $path = VIEW_SCRIPTS . $viewFile . '.phtml';

        if (file_exists($path) == false)
        {
            throw new Exception('View File not found in '. $path);
            return false;
        }

        // Load variables
        foreach ($this->vars as $key => $value)
        {
            $$key = $value;
        }

        //CHECK IF CACHE FILE SPECIFIED
        if(!is_null($cacheFile) && trim($cacheFile)!='')
        {
            //CHECK IF CACHE FILE EXISTS AND IS VALID
            if(!$output = getCache($cacheFile, $expiryTime))
            {
                //START OUTPUT BUFFERING
                ob_start();

                include ($path);

                $output = ob_get_contents();
                ob_clean();
                createCache($output, $cacheFile);

                //STOP OUTPUT BUFFERING
                ob_end_clean();
            }
            //DISPLAY CACHED FILE
            echo ($output);
        }
        else
            include ($path);
    }
}
?>
