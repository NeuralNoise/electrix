<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of ProfilerequestService
 *
 * @author nancy
 */
class ProfilerequestService extends BaseService 
{
    /**
    *
    * @param Profilerequest $profilerequest
    * @return arrayOfObjects (Profilerequest)
    */
    public function insertProfile($profilerequest)
    {
      $this->log->debug("Inside " . __CLASS__ . " " . __FUNCTION__ . "()...");
        $stmt=$this->db->prepare('            
                            INSERT INTO  profilerequests
                                (
                                     univregnno,
                                     fullname,
                                     email,
                                     dob,
                                     phone,
                                     currentLocation,
                                     contactAddress
                                 )
                            values
                                (
                                     :univregnno,
                                     :fullname,
                                     :email,
                                     :dob,
                                     :phone,
                                     :currentLocation,
                                     :contactAddress
                                 )
                             ');
           
        $stmt->bindValue(':univregnno',$profilerequest->getUnivregnno(), PDO::PARAM_INT);  
        $stmt->bindValue(':fullname',$profilerequest->getFullname(), PDO::PARAM_STR);
        $stmt->bindValue(':dob', $profilerequest->getdob(), PDO::PARAM_STR);
        $stmt->bindValue(':email', $profilerequest->getEmail(),PDO::PARAM_STR);
        $stmt->bindValue(':phone', $profilerequest->getPhone(),PDO::PARAM_STR);
        $stmt->bindValue(':currentLocation', $profilerequest->getCurrentLocation(),PDO::PARAM_STR);
        $stmt->bindValue(':contactAddress', $profilerequest->getContactAddress(),  PDO::PARAM_STR);
        $stmt->execute();
        //loadClass('Profilerequest');
        //return $stmt->fetchAll(PDO::FETCH_CLASS|PDO::FETCH_PROPS_LATE, 'Profilerequest', array('univregnno', 'fullname'));
        
    }    
    
    public function displayRegname()
    {
        $stmt=$this->db->prepare('
                            SELECT 
                                univregnno,
                                fullname
                            FROM
                                profilerequests
                                ');
        $stmt->execute();
        loadClass('Profilerequest');
        return $stmt->fetchAll(PDO::FETCH_CLASS|PDO::FETCH_PROPS_LATE, 'Profilerequest', array('univregnno', 'fullname'));        
    }    
    /**
     *
     * @param Integer $univregnno 
     * @return arrayOfObjects Profilerequest 
     */
    public function getProfilerequestDetails($univregnno)
    {
        $this->log->debug("Inside " . __CLASS__ . " " . __FUNCTION__ . "()...");
        $stmt=$this->db->prepare('
                                SELECT 
                                    *
                                FROM
                                    profilerequests
                                WHERE
                                    univregnno=:univregnno
                                LIMIT 1
                                ');
        $stmt->bindValue('univregnno', $univregnno, PDO::PARAM_INT);
        $stmt->execute();
        loadClass('Profilerequest');
        return $stmt->fetchAll(PDO::FETCH_CLASS|PDO::FETCH_PROPS_LATE,'Profilerequest', array('univregnno', 'fullname', 'dob','email','phone','currentLocation','contactAddress'));
      
    }
    /**
     *
     * @param Integer $univregnno 
    */
    public function removeApprovedRequest($univregnno)
    {
        $stmt=$this->db->prepare('
                                DELETE FROM
                                    profilerequests 
                                WHERE 
                                    univregnno=:univregnno
                                    ');
        $stmt->bindvalue('univregnno',$univregnno,  PDO::PARAM_INT);
        $stmt->execute();
    }
}
?>
