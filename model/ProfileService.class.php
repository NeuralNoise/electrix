<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of ProfileService
 *
 * @author nancy
 */
class ProfileService extends BaseService {

    /**
     *
     * @param Profile $profile 
     * @return Integer profileId 
     */
    public function insertProfile($profile) {
        $this->log->debug("Inside " . __CLASS__ . " " . __FUNCTION__ . "()...");

        $stmt = $this->db->prepare('
                                   INSERT INTO profile 
                                   (
                                        fullname,
                                        displayname,
                                        dob,
                                        email1,
                                        email2,
                                        phone1,
                                        phone2,
                                        currentLocation,
                                        contactAddress,
                                        notify
                                    ) 
                                    VALUES 
                                    (
                                        :fullname,
                                        :displayname,
                                        :dob, 
                                        :email1, 
                                        :email2, 
                                        :phone1, 
                                        :phone2, 
                                        :currentLocation, 
                                        :contactAddress,
                                        :notify
                                     )
                                     ');

        $stmt->bindValue(':fullname', $profile->getFullname(), PDO::PARAM_STR);
        $stmt->bindValue(':displayname', $profile->getDisplayname(), PDO ::PARAM_STR);
        $stmt->bindValue(':dob', $profile->getDob(), PDO::PARAM_STR);
        $stmt->bindValue(':email1', $profile->getEmail1(), PDO::PARAM_STR);
        $stmt->bindValue(':email2', $profile->getEmail2(), PDO::PARAM_STR);
        $stmt->bindValue(':phone1', $profile->getPhone1(), PDO::PARAM_STR);
        $stmt->bindvalue(':phone2', $profile->getPhone2(), PDO::PARAM_STR);
        $stmt->bindvalue(':currentLocation', $profile->getCurrentLocation(), PDO::PARAM_STR);
        $stmt->bindvalue(':contactAddress', $profile->getContactAddress(), PDO::PARAM_STR);
        $stmt->bindvalue(':notify', $profile->getnotify(), PDO::PARAM_STR);
        $stmt->execute();

        return $this->db->lastInsertId();
    }

    /**
     *
     * @param Integer profileId
     * @return Profile
     *
     */
    public function viewProfile($profileId) {
        $this->log->debug("Inside " . __CLASS__ . " " . __FUNCTION__ . "()...");
        $stmt = $this->db->prepare('
                                    SELECT
                                        *
                                    FROM
                                        profile
                                    WHERE
                                        profileId = :profileId 
                                    LIMIT 1
                                    ');
        $stmt->bindValue(':profileId', $profileId, PDO::PARAM_INT);
        $stmt->execute();

        loadClass('Profile');
        $stmt->setFetchMode(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, 'Profile', Profile::$const_args);

        return $stmt->fetch();
        //return $stmt->fetchAll(PDO::FETCH_CLASS|PDO::FETCH_PROPS_LATE, 'Profile', Profile::$const_args);
    }

    /**
     *
     * @param Profile $profile 
     */
    public function updateProfile($profile) {
        $this->log->debug("Inside " . __CLASS__ . " " . __FUNCTION__ . "()...");

        $stmt = $this->db->prepare('
                                   UPDATE profile SET
                                        fullname= :fullname,
                                        email1= :email1,
                                        email2= :email2,
                                        phone1= :phone1,
                                        phone2= :phone2,
                                        currentLocation= :currentLocation, 
                                        contactAddress= :contactAddress,
                                        notify = :notify
                                    WHERE
                                        profileId= :profileId
                                    ');

        $this->log->debug('Inside Update profile');
        $stmt->bindValue(':profileId', $profile->getProfileId(), PDO::PARAM_INT);
        $stmt->bindValue(':fullname', $profile->getFullname(), PDO::PARAM_STR);

        $stmt->bindValue(':email1', $profile->getEmail1(), PDO::PARAM_STR);
        $stmt->bindValue(':email2', $profile->getEmail2(), PDO::PARAM_STR);
        $stmt->bindValue(':phone1', $profile->getPhone1(), PDO::PARAM_STR);
        $stmt->bindvalue(':phone2', $profile->getPhone2(), PDO::PARAM_STR);
        $stmt->bindvalue(':currentLocation', $profile->getCurrentLocation(), PDO::PARAM_STR);
        $stmt->bindvalue(':contactAddress', $profile->getContactAddress(), PDO::PARAM_STR);
        $stmt->bindvalue(':notify', $profile->getNotify(), PDO::PARAM_INT);
        $stmt->execute();
    }

    /**
     *
     * @param Integer profileId
     * @return arrayOfObjects (Profile)
     *
     */
    public function viewFriendsList($profileId) {
        
        $this->log->debug("Inside " . __CLASS__ . " " . __FUNCTION__ . "()...");
        
        $stmt = $this->db->prepare('
                                        SELECT 
                                            *
                                        FROM 
                                            profile 
                                        WHERE
                                            profileId != :profileId
                                        ORDER BY 
                                             profileId
                                         ');
        $stmt->bindValue(':profileId', $profileId, PDO::PARAM_INT);
        $stmt->execute();
        $this->log->debug("{$stmt->rowCount()}");
        loadClass('Profile');
        return $stmt->fetchAll(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, 'Profile', Profile::$const_args);
    }

    /**
     *
     * @return arrayOfObjects Profile
     */
    public function getNameSuggestion() {
        $this->log->debug("Inside " . __CLASS__ . " " . __FUNCTION__ . "()...");
        $stmt = $this->db->prepare('
                                       SELECT
                                                fullname,displayname
                                       FROM
                                                profile
                                       ');
        $stmt->execute();
        loadClass('Profile');
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     *
     * @param Integer profileId
     * @return arrayOfObjects Profile
     *
     */
    public function viewFriendProfile($profileId) {
        $this->log->debug("Inside " . __CLASS__ . " " . __FUNCTION__ . "()...");

        $stmt = $this->db->prepare('
                                        SELECT 
                                               *
                                        FROM
                                                profile 
                                        WHERE 
                                                profileId = :profileId 
                                        LIMIT 1
                                        ');
        $stmt->bindValue(':profileId', $profileId, PDO::PARAM_INT);
        $stmt->execute();
        loadClass('Profile');
        return $stmt->fetchAll(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, 'Profile', Profile::$const_args);
    }

    /**
     * @param Integer profileId
     * @return arrayOfBirthdayVO
     */
    public function retrieveBirthdays($profileId) {
        $this->log->debug("Inside " . __CLASS__ . " " . __FUNCTION__ . "()...");

        $stmt = $this->db->prepare('
                                    SELECT 
                                        displayname
                                    FROM
                                        profile
                                    WHERE
                                        profileId != :profileId
                                    AND
                                        RIGHT(dob,5) = RIGHT(:dateToday,5) 
                                    ORDER BY 
                                        profileId
                                    ');
        $stmt->bindValue(':profileId', $profileId, PDO::PARAM_INT);
        $stmt->bindValue(':dateToday', date('Y-m-d'), PDO::PARAM_STR); //USE THIS INSTEAD OF MYSQL SYSDATE() FOR PROPER TIMEZONE VALUE
        $stmt->execute();
        $this->log->debug($stmt->rowCount());
        loadClass('viewObjects/BirthdayVO');
        return $stmt->fetchAll(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, 'BirthdayVO', BirthdayVO::$constArgs);
    }

    /**
     *
     * @param Integer $profileId
     * @param Integer $offset
     * @param Integer $limit
     * @return arrayOfUpdateVOStatus
     */
    public function getStatusUpdates($profileId,$offset = 0, $limit = 10) {
        $this->log->debug("Inside " . __CLASS__ . " " . __FUNCTION__ . "()...");

        $stmt = $this->db->prepare("
                SELECT
                    p.profileId AS author,
                    p.displayname,
                    s.statusId,
                    s.status,
                    (
                        SELECT 
                            count(profileId)
                        FROM 
                            likes l
                        WHERE 
                            l.type = 'status' AND
                            l.itemId = s.statusId
                    ) AS noOfLikes,
                    (
                        SELECT 
                            count(profileId)
                        FROM 
                            likes l
                        WHERE 
                            l.type = 'status' AND
                            l.itemId = s.statusId AND l.profileId = :profileId
                    ) AS iLiked,
                    s.statusDate AS date,
                    u.date AS sortDate
                FROM
                    updates u,
                    profile p,
                    statusmsgs s
                WHERE
                    (
                        (
                        u.type = 'status' AND
                        u.itemId = s.statusId AND
                        u.author = p.displayname
                        ) 
                        OR
                        (
                        u.type = 'comment' AND 
                        u.targetStatusId = s.statusId AND
                        s.author = p.profileId
                        )
                    )
                    AND
                    u.updateId IN
                    (
                        SELECT
                            u.updateId
                        FROM 
                        (
                            SELECT 
                                * 
                            FROM 
                                updates 
                            ORDER BY date DESC
                        ) AS u
                        GROUP BY
                            u.targetStatusId
                        ORDER BY
                            u.date DESC
                    )
                ORDER BY sortDate DESC 
                LIMIT $offset, $limit;
               ");

        $stmt->bindValue(':profileId', $profileId, PDO::PARAM_INT);
        $stmt->execute();

        $this->log->debug('No. of status messages: ' . $stmt->rowCount());

        loadClass('viewObjects/UpdatesVOStatus');
        return $stmt->fetchAll(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, 'UpdatesVOStatus', UpdatesVOStatus::$constArgs);
    }

}

?>
