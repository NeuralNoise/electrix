<?php

/**
 *
 * @author Magesh Ravi
 * @version -----
 * @copyright Copyright (c) 2011 Magesh Ravi
 */

class BaseService {
    
    /** @var Log */
    protected $log;
    
    /** @var PDO */
    protected $db;
    
    
    function __construct() {        
        $this->log = Log::getInstance();
        $this->db = db::getInstance();
    }

}

?>
