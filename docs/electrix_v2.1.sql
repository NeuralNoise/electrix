SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='TRADITIONAL,ALLOW_INVALID_DATES';


-- -----------------------------------------------------
-- Table `admin`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `admin` (
  `username` VARCHAR(15) NOT NULL ,
  `password` VARCHAR(32) NOT NULL ,
  PRIMARY KEY (`username`) )
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `profile`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `profile` (
  `profileId` INT(3) NOT NULL AUTO_INCREMENT ,
  `fullname` VARCHAR(64) NOT NULL ,
  `displayname` VARCHAR(32) NOT NULL ,
  `dob` DATE NOT NULL ,
  `email1` VARCHAR(255) NOT NULL ,
  `email2` VARCHAR(255) NOT NULL ,
  `phone1` VARCHAR(15) NOT NULL ,
  `phone2` VARCHAR(15) NOT NULL ,
  `currentLocation` TEXT NOT NULL ,
  `contactAddress` TEXT NOT NULL ,
  `notify` TINYINT(1) NOT NULL ,
  PRIMARY KEY (`profileId`) )
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COMMENT = 'This table will have information about the existing/new user';


-- -----------------------------------------------------
-- Table `comments`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `comments` (
  `commentId` INT(5) NOT NULL AUTO_INCREMENT ,
  `type` ENUM('status','photo') NOT NULL ,
  `itemId` INT(5) NOT NULL ,
  `comment` VARCHAR(255) NOT NULL ,
  `author` INT(3) NOT NULL ,
  `date` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP ,
  PRIMARY KEY (`commentId`) ,
  INDEX `fk_comments_author_idx` (`author` ASC) ,
  CONSTRAINT `fk_comments_author`
    FOREIGN KEY (`author` )
    REFERENCES `profile` (`profileId` )
    ON DELETE CASCADE
    ON UPDATE RESTRICT)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `likes`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `likes` (
  `type` ENUM('status','comment','photo') NOT NULL ,
  `itemId` INT(5) NOT NULL ,
  `profileId` INT(5) NOT NULL ,
  PRIMARY KEY (`type`, `itemId`, `profileId`) ,
  INDEX `fk_likes_profileId_idx` (`profileId` ASC) ,
  CONSTRAINT `fk_likes_profileId`
    FOREIGN KEY (`profileId` )
    REFERENCES `profile` (`profileId` )
    ON DELETE CASCADE
    ON UPDATE RESTRICT)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `login`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `login` (
  `username` BIGINT(15) NOT NULL ,
  `password` VARCHAR(32) NOT NULL ,
  `profileId` INT(3) NOT NULL ,
  `lastlogin` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP ,
  PRIMARY KEY (`username`) ,
  INDEX `profile_id` (`profileId` ASC) ,
  CONSTRAINT `login_ibfk_1`
    FOREIGN KEY (`profileId` )
    REFERENCES `profile` (`profileId` )
    ON DELETE CASCADE)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `messages`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `messages` (
  `messageId` INT(3) NOT NULL AUTO_INCREMENT ,
  `subject` VARCHAR(32) NOT NULL ,
  `body` VARCHAR(256) NOT NULL ,
  `date` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP ,
  PRIMARY KEY (`messageId`) )
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `messages_log`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `messages_log` (
  `messageId` INT(3) NOT NULL ,
  `msgfrom` INT(3) NOT NULL ,
  `msgto` INT(3) NOT NULL ,
  `replyto` INT(3) NOT NULL ,
  `visibility` TINYINT(2) NOT NULL ,
  PRIMARY KEY (`messageId`, `msgfrom`, `msgto`) ,
  INDEX `msgfrom` (`msgfrom` ASC) ,
  INDEX `msgto` (`msgto` ASC) ,
  INDEX `replyto` (`replyto` ASC) ,
  INDEX `messageId` (`messageId` ASC) ,
  CONSTRAINT `messages_log_ibfk_1`
    FOREIGN KEY (`messageId` )
    REFERENCES `messages` (`messageId` )
    ON DELETE CASCADE
    ON UPDATE RESTRICT,
  CONSTRAINT `messages_log_ibfk_5`
    FOREIGN KEY (`msgfrom` )
    REFERENCES `profile` (`profileId` )
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  CONSTRAINT `messages_log_ibfk_6`
    FOREIGN KEY (`msgto` )
    REFERENCES `profile` (`profileId` )
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  CONSTRAINT `messages_log_ibfk_7`
    FOREIGN KEY (`messageId` )
    REFERENCES `messages` (`messageId` )
    ON DELETE CASCADE
    ON UPDATE RESTRICT)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `photos`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `photos` (
  `photoId` INT(11) NOT NULL AUTO_INCREMENT ,
  `fileName` VARCHAR(45) NOT NULL ,
  `description` VARCHAR(128) NULL DEFAULT NULL ,
  `uploader` INT(11) NOT NULL ,
  `uploadedDate` DATETIME NOT NULL ,
  PRIMARY KEY (`photoId`) ,
  INDEX `fk_photos_uploader_idx` (`uploader` ASC) ,
  CONSTRAINT `fk_photos_uploader`
    FOREIGN KEY (`uploader` )
    REFERENCES `profile` (`profileId` )
    ON DELETE CASCADE
    ON UPDATE CASCADE)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `profilerequests`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `profilerequests` (
  `univregnno` BIGINT(15) NOT NULL ,
  `fullname` VARCHAR(64) NOT NULL ,
  `dob` DATE NOT NULL ,
  `email` VARCHAR(255) NOT NULL ,
  `phone` VARCHAR(15) NOT NULL ,
  `currentLocation` TEXT NOT NULL ,
  `contactAddress` TEXT NULL DEFAULT NULL ,
  PRIMARY KEY (`univregnno`) ,
  UNIQUE INDEX `email` (`email` ASC) ,
  UNIQUE INDEX `phone` (`phone` ASC) )
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `statusmsgs`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `statusmsgs` (
  `statusId` INT NOT NULL AUTO_INCREMENT ,
  `author` INT(3) NOT NULL ,
  `status` VARCHAR(140) NOT NULL ,
  `statusDate` DATETIME NOT NULL ,
  PRIMARY KEY (`statusId`) ,
  INDEX `profileId` (`author` ASC) ,
  CONSTRAINT `statusmsgs_ibfk_1`
    FOREIGN KEY (`author` )
    REFERENCES `profile` (`profileId` )
    ON DELETE CASCADE
    ON UPDATE RESTRICT)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `updates`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `updates` (
  `updateId` INT(10) NOT NULL AUTO_INCREMENT ,
  `type` ENUM('status','comment','photo') NOT NULL COMMENT 'what type of update ?' ,
  `itemId` INT(5) NOT NULL COMMENT 'this field links to the \\\'itemId\\\' in likes table' ,
  `targetStatusId` INT(11) NOT NULL COMMENT 'to which status id it is meant' ,
  `author` VARCHAR(64) NOT NULL COMMENT 'profile id' ,
  `date` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ,
  PRIMARY KEY (`updateId`) ,
  INDEX `fk_updates_targetstatusId_idx` (`targetStatusId` ASC) ,
  CONSTRAINT `fk_updates_targetstatusId`
    FOREIGN KEY (`targetStatusId` )
    REFERENCES `statusmsgs` (`statusId` )
    ON DELETE CASCADE
    ON UPDATE NO ACTION)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `albums`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `albums` (
  `albumId` INT NOT NULL AUTO_INCREMENT ,
  `albumName` VARCHAR(45) NOT NULL ,
  `description` VARCHAR(256) NULL ,
  `owner` INT(3) NOT NULL ,
  `createdOn` DATETIME NOT NULL ,
  PRIMARY KEY (`albumId`) ,
  INDEX `fk_albums_owner_idx` (`owner` ASC) ,
  CONSTRAINT `fk_albums_owner`
    FOREIGN KEY (`owner` )
    REFERENCES `profile` (`profileId` )
    ON DELETE CASCADE
    ON UPDATE RESTRICT)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `albums_have_photos`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `albums_have_photos` (
  `albumId` INT NOT NULL ,
  `photoId` INT NOT NULL ,
  PRIMARY KEY (`albumId`, `photoId`) ,
  INDEX `fk_albums_albumId_idx` (`albumId` ASC) ,
  INDEX `fk_albums_photoId_idx` (`photoId` ASC) ,
  CONSTRAINT `fk_albums_albumId`
    FOREIGN KEY (`albumId` )
    REFERENCES `albums` (`albumId` )
    ON DELETE CASCADE
    ON UPDATE RESTRICT,
  CONSTRAINT `fk_albums_photoId`
    FOREIGN KEY (`photoId` )
    REFERENCES `photos` (`photoId` )
    ON DELETE CASCADE
    ON UPDATE RESTRICT)
ENGINE = InnoDB
COMMENT = 'logs which photo belongs to which album';


DELIMITER $$


CREATE
DEFINER=`root`@`localhost`
TRIGGER `electrix_v2.1`.`tr_addcommentupdates`
AFTER INSERT ON `electrix_v2.1`.`comments`
FOR EACH ROW
BEGIN
        INSERT INTO updates(type,itemId,targetStatusId,author,date)
        VALUES('comment',new.commentId,new.itemId,(SELECT displayname FROM profile WHERE profileId=new.author),now());
END$$


DELIMITER ;

DELIMITER $$


CREATE
DEFINER=`root`@`localhost`
TRIGGER `electrix_v2.1`.`tr_addupdates`
AFTER INSERT ON `electrix_v2.1`.`statusmsgs`
FOR EACH ROW
BEGIN
        INSERT INTO updates(type,itemId,targetStatusId,author,date)
        VALUES('status',new.statusId,new.statusId,(SELECT displayname FROM profile WHERE profileId=new.author),now());
END$$


DELIMITER ;


SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;
