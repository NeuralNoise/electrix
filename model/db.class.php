<?php

class db {

    /*** Declare instance ***/
    private static $instance = NULL;

    /**
    *
    * the constructor is set to private so
    * so nobody can create a new instance using new
    *
    */
    private function __construct() {
        /*** maybe set the db name here later ***/
    }

    /**
    *
    * Return DB instance or create intitial connection
    *
    * @return object (PDO)
    *
    * @access public
    *
    */
    public static function getInstance() {

        if (!self::$instance) {

            try {

                self::$instance = new PDO("mysql:host=localhost;dbname=".MYSQL_DB, MYSQL_USERNAME, MYSQL_PASSWORD);
                self::$instance-> setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            } catch(Exception $e) {

                require_once ('includes/commonFunctions.inc.php');
                die('Connection error!');
            }
        }
        return self::$instance;
    }

    /**
    *
    * Like the constructor, we make __clone private
    * so nobody can clone the instance
    *
    */
    private function __clone(){
        
    }

}

/*** end of class ***/

?>
