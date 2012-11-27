<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of LoginService
 *
 * @author nancy
 */
   
class LoginService extends BaseService 
{
    
    /**
     *
     * @param Login $login
    */
 
    public function addLogin($login)
    {
        $stmt = $this->db->prepare('
                                INSERT INTO 
                                    login 
                                        (
                                        username,
                                        password,
                                        profileId
                                        ) 
                                VALUES 
                                        (
                                        :username,
                                        :password,
                                        :profileId
                                )');
        $stmt->bindValue(':username',$login->getUsername(),  PDO::PARAM_INT);
        $stmt->bindValue(':password', $login->getPassword(), PDO ::PARAM_STR);
        $stmt->bindValue(':profileId', $login->getProfileId(), PDO::PARAM_STR);
        $stmt->execute();
    }
    /**
     *
     * @param Login $login
     * @return Integer
     */
    public function passwordCheck($login)
    {
        $this->log->debug("Inside " . __CLASS__ . " " . __FUNCTION__ . "()...");
        
        $stmt=$this->db->prepare('
                                SELECT 
                                    username,
                                    password,
                                    profileId 
                                FROM 
                                    login 
                                WHERE 
                                    username=:username 
                                AND 
                                    password=:password'
                                );
        
        $stmt->bindvalue(':username',$login->getUsername(),PDO::PARAM_STR);
        $stmt->bindvalue(':password',$login->getPassword(),PDO::PARAM_STR);
        $this->log->debug($login->getUsername());
        $this->log->debug($login->getPassword());
        $stmt->execute();
        
        if( $stmt->rowCount() == 1)
        {
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            $stmt2 = $this->db->prepare('
                                    UPDATE 
                                        login
                                    SET
                                        lastlogin = :date
                                    WHERE
                                        profileId = :profileid
                                        ');
            
            $stmt2->bindValue(':date', date('Y-m-d H:i:s'),PDO::PARAM_STR);
            $stmt2->bindValue('profileid', $result['profileId'],PDO::PARAM_INT);
            
            $stmt2->execute();
            
            return $result['profileId'];
        }
        else
            return -1;        
    }
    
    /**
     *
     * @param Integer $profileId
     * @return String 
     */
    public function getLastLogin($profileId)
    {
        $stmt = $this->db->prepare('
                                    SELECT 
                                        lastlogin
                                    FROM
                                        login
                                    WHERE
                                        profileId = :profileid
                                    ');
        $stmt->bindvalue(':profileid',$profileId,PDO::PARAM_INT);
        
        $stmt->execute();
        
        $result= $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['lastlogin'];
        
    }
    /**
     *
     * @param String $newPwd
     * @param String $existingPwd
     * @param Integer $profileId
     * @return Integer
     */
    
    public function changePassword($newPwd,$existingPwd,$profileId)
    {
        
        $stmt = $this->db->prepare('
                                UPDATE 
                                    login 
                                SET 
                                    password=:newPwd 
                                WHERE
                                    profileId=:profileId 
                                AND 
                                    password=:existingPwd
                                ');

        $stmt->bindValue(':newPwd', md5($newPwd));
        $stmt->bindValue(':profileId', $profileId,PDO::PARAM_INT);
        $stmt->bindValue(':existingPwd', md5($existingPwd));
        $stmt->execute();
        $count = $stmt->rowCount();
        return $count;	
    }
    
    /**
     *
     * @param Integer $univregnno
     * @return Boolean
     */
    public function checkExistingID($univregnno)
    {
        $this->log->debug("Inside " . __CLASS__ . " " . __FUNCTION__ . "()...");
        $stmt=$this->db->prepare('
                            SELECT
                                username
                            FROM 
                                Login 
                            WHERE
                                username=:username 
                                ');
        $stmt->bindvalue(':username',$univregnno,PDO::PARAM_STR);
        $stmt->execute();
        if( $stmt->rowCount() == 1)
            return TRUE;
        else
            return FALSE;
    }
    
    /**
     *
     * @param Integer $profileId 
     * @return resultSet
     */
    public function getUserDetail($profileId)
    {
        $this->log->debug("Inside " . __CLASS__ . " " . __FUNCTION__ . "()...");
        
        $stmt=$this->db->prepare('
                            SELECT 
                                displayname,currentLocation
                            FROM 
                                profile
                            WHERE 
                                profileId = :profileId
                            ');
        $stmt->bindValue(':profileId', $profileId, PDO::PARAM_INT);
        $stmt->execute();   
        $result= $stmt->fetch(PDO::FETCH_ASSOC);
        
        $stmt2=$this->db->prepare('
                                SELECT 
                                    statusId,
                                    status 
                                FROM
                                    statusmsgs
                                WHERE
                                    author = :profileId 
                                ORDER BY 
                                    statusId DESC 
                                LIMIT 1
                              ');
        $stmt2->bindValue(':profileId', $profileId,PDO::PARAM_INT);
        $stmt2->execute();
        if($stmt2->rowCount()==1)
        {
            $result2=$stmt2->fetch(PDO::FETCH_ASSOC);
            $result['status']=$result2['status'];
            $result['statusId']=$result2['statusId'];
        }
        else
        {
            $result['status']='Set your Status using the text box below';
        }
        return $result;                
    }
}
?>
