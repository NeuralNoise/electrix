<?php

Abstract Class BaseController
{
    /** @var Registry */
    protected $registry;
    
    /** @var Log */
    protected $log;

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
    * @all controllers must contain an index method
    */
    abstract function index();
}

?>