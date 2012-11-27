<?php

/**
 *
 * Uses singleton pattern
 * @author Magesh Ravi
 * @version 1.1
 * @copyright Copyright (c) 2011 Magesh Ravi
 */
class Log
{
    /** @var resource $fileHande */
    private static $fileHandle = NULL;
    
    /**
     * Setting to private 
     * so that no one can create an instance using new 
     */
    private function __construct() {
        
    }
    
    /**
     *
     * @return \Log 
     */
    public static function getInstance() {
        
        if(!self::$fileHandle) {
            
            if(is_dir(SITE_PATH.'/temp/debug')) {
                
                $filename = SITE_PATH.'/temp/debug/'.date("d-M-Y").'-debug.log';

                //OPEN FILE
                self::$fileHandle = fopen ($filename, 'a');
            }
        }
        
        return new Log();
    }

    function __destruct() {
        
        if(is_resource(self::$fileHandle)) {
            fwrite(self::$fileHandle, "- close -\n");
            fclose (self::$fileHandle);
            self::$fileHandle = NULL;
        }
    }
    
    /**
     *
     * @param string $message 
     */
    public function debug($message) {
        
        if(is_resource(self::$fileHandle)) {
            fwrite(self::$fileHandle, date("F j, Y, g:i:s a")." $message\n");
        }
    }
}

?>
