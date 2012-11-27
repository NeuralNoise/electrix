<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of AdminService
 *
 * @author mageshravi
 */
class AdminService extends BaseService {

    /**
     * 
     * @param Admin $admin
     * @return Boolean 
     */
    public function passwordCheck($admin) {
        
        $this->log->debug("Inside " . __CLASS__ . " " . __FUNCTION__ . "()...");
        
        $stmt = $this->db->prepare('
                                SELECT
                                    *
                                FROM
                                    admin
                                WHERE
                                    username=:username
                                AND
                                    password=:password
                                ');
        $stmt->bindvalue(':username', $admin->getUsername(), PDO::PARAM_STR);
        $stmt->bindvalue(':password', md5($admin->getPassword()), PDO::PARAM_STR);
        $stmt->execute();
        
        if ($stmt->rowCount() == 1)
            return TRUE;
        else
            return FALSE;
    }

}

?>
