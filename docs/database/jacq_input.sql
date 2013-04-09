SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='TRADITIONAL';

DROP SCHEMA IF EXISTS `jacq_input` ;
CREATE SCHEMA IF NOT EXISTS `jacq_input` DEFAULT CHARACTER SET utf8 ;
USE `jacq_input` ;

-- -----------------------------------------------------
-- Table `frmwrk_user`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `frmwrk_user` ;

CREATE  TABLE IF NOT EXISTS `frmwrk_user` (
  `id` INT NOT NULL AUTO_INCREMENT ,
  `username` VARCHAR(128) NOT NULL ,
  `password` VARCHAR(64) NOT NULL COMMENT 'SHA-256 hash' ,
  `salt` VARCHAR(64) NOT NULL COMMENT 'random salt' ,
  PRIMARY KEY (`id`) )
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `tbl_organisation`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `tbl_organisation` ;

CREATE  TABLE IF NOT EXISTS `tbl_organisation` (
  `id` INT NOT NULL AUTO_INCREMENT ,
  `description` VARCHAR(255) NULL ,
  `department` VARCHAR(255) NULL ,
  `greenhouse` TINYINT(1) NOT NULL DEFAULT 0 ,
  `parent_organisation_id` INT NULL DEFAULT NULL ,
  `gardener_id` INT NULL ,
  `ipen_code` VARCHAR(5) NULL DEFAULT NULL ,
  PRIMARY KEY (`id`) ,
  INDEX `fk_garden_site_id_idx` (`parent_organisation_id` ASC) ,
  INDEX `fk_user_id_idx` (`gardener_id` ASC) ,
  CONSTRAINT `fk_garden_site_id`
    FOREIGN KEY (`parent_organisation_id` )
    REFERENCES `tbl_organisation` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_user_id`
    FOREIGN KEY (`gardener_id` )
    REFERENCES `frmwrk_user` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
COMMENT = 'Table for maintaining garden-places';


-- -----------------------------------------------------
-- Table `tbl_acquisition_date`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `tbl_acquisition_date` ;

CREATE  TABLE IF NOT EXISTS `tbl_acquisition_date` (
  `id` INT NOT NULL AUTO_INCREMENT ,
  `year` VARCHAR(4) NULL ,
  `month` VARCHAR(2) NULL ,
  `day` VARCHAR(2) NULL ,
  `custom` VARCHAR(20) NULL ,
  PRIMARY KEY (`id`) )
ENGINE = InnoDB
COMMENT = 'Date table for entering even incomplete information';


-- -----------------------------------------------------
-- Table `tbl_acquisition_type`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `tbl_acquisition_type` ;

CREATE  TABLE IF NOT EXISTS `tbl_acquisition_type` (
  `id` INT NOT NULL AUTO_INCREMENT ,
  `type` VARCHAR(45) NULL ,
  PRIMARY KEY (`id`) )
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `tbl_location`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `tbl_location` ;

CREATE  TABLE IF NOT EXISTS `tbl_location` (
  `id` INT NOT NULL AUTO_INCREMENT ,
  `location` TEXT NOT NULL ,
  PRIMARY KEY (`id`) )
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `tbl_location_coordinates`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `tbl_location_coordinates` ;

CREATE  TABLE IF NOT EXISTS `tbl_location_coordinates` (
  `id` INT NOT NULL AUTO_INCREMENT ,
  `altitude_min` INT NULL ,
  `altitude_max` INT NULL ,
  `exactness` INT NULL ,
  `latitude_degrees` INT NULL ,
  `latitude_minutes` INT NULL ,
  `latitude_seconds` INT NULL ,
  `latitude_half` ENUM('N','S') NULL ,
  `longitude_degrees` INT NULL ,
  `longitude_minutes` INT NULL ,
  `longitude_seconds` INT NULL ,
  `longitude_half` ENUM('E','W') NULL ,
  PRIMARY KEY (`id`) )
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `tbl_acquisition_event`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `tbl_acquisition_event` ;

CREATE  TABLE IF NOT EXISTS `tbl_acquisition_event` (
  `id` INT NOT NULL AUTO_INCREMENT ,
  `acquisition_date_id` INT NOT NULL ,
  `acquisition_type_id` INT NOT NULL ,
  `location_id` INT NULL DEFAULT NULL ,
  `number` TEXT NULL ,
  `annotation` TEXT NULL ,
  `location_coordinates_id` INT NOT NULL ,
  PRIMARY KEY (`id`) ,
  INDEX `fk_tbl_acquisition_event_tbl_acquisition_date1_idx` (`acquisition_date_id` ASC) ,
  INDEX `fk_tbl_acquisition_event_tbl_acquisition_type1_idx` (`acquisition_type_id` ASC) ,
  INDEX `fk_tbl_acquisition_event_tbl_location1_idx` (`location_id` ASC) ,
  INDEX `fk_tbl_acquisition_event_tbl_location_coordinates` (`location_coordinates_id` ASC) ,
  CONSTRAINT `fk_tbl_acquisition_event_tbl_acquisition_date`
    FOREIGN KEY (`acquisition_date_id` )
    REFERENCES `tbl_acquisition_date` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_tbl_acquisition_event_tbl_acquisition_type`
    FOREIGN KEY (`acquisition_type_id` )
    REFERENCES `tbl_acquisition_type` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_tbl_acquisition_event_tbl_location`
    FOREIGN KEY (`location_id` )
    REFERENCES `tbl_location` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_tbl_acquisition_event_tbl_location_coordinates`
    FOREIGN KEY (`location_coordinates_id` )
    REFERENCES `tbl_location_coordinates` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `tbl_phenology`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `tbl_phenology` ;

CREATE  TABLE IF NOT EXISTS `tbl_phenology` (
  `id` INT NOT NULL AUTO_INCREMENT ,
  `phenology` VARCHAR(45) NULL ,
  PRIMARY KEY (`id`) ,
  UNIQUE INDEX `phenology_UNIQUE` (`phenology` ASC) )
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `tbl_person`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `tbl_person` ;

CREATE  TABLE IF NOT EXISTS `tbl_person` (
  `id` INT NOT NULL AUTO_INCREMENT ,
  `name` VARCHAR(255) NOT NULL ,
  `collNr` VARCHAR(45) NULL DEFAULT NULL ,
  PRIMARY KEY (`id`) ,
  INDEX `name` (`name` ASC) ,
  UNIQUE INDEX `name_UNIQUE` (`name` ASC) )
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `tbl_botanical_object`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `tbl_botanical_object` ;

CREATE  TABLE IF NOT EXISTS `tbl_botanical_object` (
  `id` INT NOT NULL AUTO_INCREMENT ,
  `acquisition_event_id` INT NOT NULL ,
  `phenology_id` INT NULL ,
  `scientific_name_id` INT NOT NULL COMMENT 'Pointer to taxonID in old system' ,
  `determined_by_id` INT NULL ,
  `determination_date` DATE NULL DEFAULT NULL ,
  `habitat` VARCHAR(45) NULL ,
  `habitus` VARCHAR(45) NULL ,
  `annotation` TEXT NULL ,
  `recording_date` DATETIME NOT NULL ,
  `organisation_id` INT NULL DEFAULT 1 ,
  `accessible` TINYINT(1) NOT NULL DEFAULT 0 ,
  PRIMARY KEY (`id`) ,
  INDEX `fk_tbl_object_tbl_acquisition_event1_idx` (`acquisition_event_id` ASC) ,
  INDEX `fk_tbl_object_tbl_phenology1_idx` (`phenology_id` ASC) ,
  INDEX `fk_tbl_botanical_object_tbl_person1_idx` (`determined_by_id` ASC) ,
  INDEX `fk_tbl_botanical_object_tbl_garden_site1_idx` (`organisation_id` ASC) ,
  CONSTRAINT `fk_tbl_object_tbl_acquisition_event1`
    FOREIGN KEY (`acquisition_event_id` )
    REFERENCES `tbl_acquisition_event` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_tbl_object_tbl_phenology1`
    FOREIGN KEY (`phenology_id` )
    REFERENCES `tbl_phenology` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_tbl_botanical_object_tbl_person1`
    FOREIGN KEY (`determined_by_id` )
    REFERENCES `tbl_person` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_tbl_botanical_object_tbl_garden_site1`
    FOREIGN KEY (`organisation_id` )
    REFERENCES `tbl_organisation` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `tbl_index_seminum_type`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `tbl_index_seminum_type` ;

CREATE  TABLE IF NOT EXISTS `tbl_index_seminum_type` (
  `id` INT NOT NULL AUTO_INCREMENT ,
  `type` VARCHAR(3) NOT NULL ,
  PRIMARY KEY (`id`) )
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `tbl_living_plant`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `tbl_living_plant` ;

CREATE  TABLE IF NOT EXISTS `tbl_living_plant` (
  `id` INT NOT NULL ,
  `accession_number` INT NOT NULL AUTO_INCREMENT ,
  `ipen_number` VARCHAR(20) NULL ,
  `ipen_locked` TINYINT(1) NOT NULL DEFAULT 0 COMMENT 'No further editing possible' ,
  `phyto_control` TINYINT(1) NOT NULL DEFAULT 0 ,
  `place_number` VARCHAR(20) NULL DEFAULT NULL ,
  `index_seminum` TINYINT(1) NOT NULL DEFAULT 0 ,
  `culture_notes` TEXT NULL DEFAULT NULL ,
  `cultivation_date` DATE NULL DEFAULT NULL ,
  `index_seminum_type_id` INT NULL DEFAULT NULL ,
  `incoming_date` DATE NULL DEFAULT NULL ,
  PRIMARY KEY (`id`) ,
  INDEX `fk_tbl_living_plant_tbl_index_seminum_type1` (`index_seminum_type_id` ASC) ,
  UNIQUE INDEX `accession_number_UNIQUE` (`accession_number` ASC) ,
  CONSTRAINT `fk_livingplant_object1`
    FOREIGN KEY (`id` )
    REFERENCES `tbl_botanical_object` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_tbl_living_plant_tbl_index_seminum_type1`
    FOREIGN KEY (`index_seminum_type_id` )
    REFERENCES `tbl_index_seminum_type` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `tbl_sex`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `tbl_sex` ;

CREATE  TABLE IF NOT EXISTS `tbl_sex` (
  `id` INT NOT NULL AUTO_INCREMENT ,
  `sex` VARCHAR(30) NOT NULL ,
  PRIMARY KEY (`id`) ,
  UNIQUE INDEX `sex_UNIQUE` (`sex` ASC) )
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `tbl_botanical_object_sex`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `tbl_botanical_object_sex` ;

CREATE  TABLE IF NOT EXISTS `tbl_botanical_object_sex` (
  `id` INT NOT NULL AUTO_INCREMENT ,
  `sex_id` INT NOT NULL ,
  `botanical_object_id` INT NOT NULL ,
  INDEX `fk_table1_tbl_sex1_idx` (`sex_id` ASC) ,
  INDEX `fk_table1_tbl_object1_idx` (`botanical_object_id` ASC) ,
  PRIMARY KEY (`id`) ,
  UNIQUE INDEX `object_id_sex_id_UNIQUE` (`sex_id` ASC, `botanical_object_id` ASC) ,
  CONSTRAINT `fk_table1_tbl_sex1`
    FOREIGN KEY (`sex_id` )
    REFERENCES `tbl_sex` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_table1_tbl_object1`
    FOREIGN KEY (`botanical_object_id` )
    REFERENCES `tbl_botanical_object` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `tbl_diaspora_bank`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `tbl_diaspora_bank` ;

CREATE  TABLE IF NOT EXISTS `tbl_diaspora_bank` (
  `id` INT NOT NULL AUTO_INCREMENT ,
  `name` VARCHAR(45) NULL ,
  PRIMARY KEY (`id`) )
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `tbl_diaspora`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `tbl_diaspora` ;

CREATE  TABLE IF NOT EXISTS `tbl_diaspora` (
  `id` INT NOT NULL AUTO_INCREMENT ,
  `diaspora_bank_id` INT NOT NULL ,
  PRIMARY KEY (`id`) ,
  INDEX `fk_tbl_diaspora_tbl_diaspora_bank1_idx` (`diaspora_bank_id` ASC) ,
  CONSTRAINT `fk_tbl_diaspora_tbl_object1`
    FOREIGN KEY (`id` )
    REFERENCES `tbl_botanical_object` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_tbl_diaspora_tbl_diaspora_bank1`
    FOREIGN KEY (`diaspora_bank_id` )
    REFERENCES `tbl_diaspora_bank` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `tbl_separation_type`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `tbl_separation_type` ;

CREATE  TABLE IF NOT EXISTS `tbl_separation_type` (
  `id` INT NOT NULL AUTO_INCREMENT ,
  `type` VARCHAR(15) NULL ,
  PRIMARY KEY (`id`) )
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `tbl_separation`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `tbl_separation` ;

CREATE  TABLE IF NOT EXISTS `tbl_separation` (
  `id` INT NOT NULL AUTO_INCREMENT ,
  `botanical_object_id` INT NOT NULL ,
  `separation_type_id` INT NOT NULL ,
  `date` DATE NULL DEFAULT NULL ,
  `annotation` TEXT NULL ,
  PRIMARY KEY (`id`) ,
  INDEX `fk_tbl_separation_tbl_separation_type1_idx` (`separation_type_id` ASC) ,
  INDEX `fk_tbl_separation_tbl_botanical_object1_idx` (`botanical_object_id` ASC) ,
  CONSTRAINT `fk_tbl_separation_tbl_separation_type1`
    FOREIGN KEY (`separation_type_id` )
    REFERENCES `tbl_separation_type` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_tbl_separation_tbl_botanical_object1`
    FOREIGN KEY (`botanical_object_id` )
    REFERENCES `tbl_botanical_object` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `tbl_image`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `tbl_image` ;

CREATE  TABLE IF NOT EXISTS `tbl_image` (
  `id` INT NOT NULL AUTO_INCREMENT ,
  `image_id` INT NOT NULL ,
  `botanical_object_id` INT NOT NULL ,
  PRIMARY KEY (`id`) ,
  INDEX `fk_tbl_image_tbl_botanical_object1_idx` (`botanical_object_id` ASC) ,
  UNIQUE INDEX `botanical_object_image_id_UNIQUE` (`image_id` ASC, `botanical_object_id` ASC) ,
  CONSTRAINT `fk_tbl_image_tbl_botanical_object1`
    FOREIGN KEY (`botanical_object_id` )
    REFERENCES `tbl_botanical_object` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `tbl_relevancy_type`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `tbl_relevancy_type` ;

CREATE  TABLE IF NOT EXISTS `tbl_relevancy_type` (
  `id` INT NOT NULL AUTO_INCREMENT ,
  `type` VARCHAR(20) NULL ,
  PRIMARY KEY (`id`) )
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `tbl_relevancy`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `tbl_relevancy` ;

CREATE  TABLE IF NOT EXISTS `tbl_relevancy` (
  `id` INT NOT NULL AUTO_INCREMENT ,
  `relevancy_type_id` INT NOT NULL ,
  `living_plant_id` INT NOT NULL ,
  PRIMARY KEY (`id`) ,
  INDEX `fk_tbl_relevancy_has_tbl_livingplant_tbl_livingplant1_idx` (`living_plant_id` ASC) ,
  INDEX `fk_tbl_relevancy_has_tbl_livingplant_tbl_relevancy1_idx` (`relevancy_type_id` ASC) ,
  UNIQUE INDEX `livingplant_relevancy_type_id_UNIQUE` (`relevancy_type_id` ASC, `living_plant_id` ASC) ,
  CONSTRAINT `fk_tbl_relevancy_has_tbl_livingplant_tbl_relevancy1`
    FOREIGN KEY (`relevancy_type_id` )
    REFERENCES `tbl_relevancy_type` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_tbl_relevancy_has_tbl_livingplant_tbl_livingplant1`
    FOREIGN KEY (`living_plant_id` )
    REFERENCES `tbl_living_plant` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `tbl_tree_record_file`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `tbl_tree_record_file` ;

CREATE  TABLE IF NOT EXISTS `tbl_tree_record_file` (
  `id` INT NOT NULL AUTO_INCREMENT ,
  `year` YEAR NULL ,
  `name` VARCHAR(255) NULL ,
  `document_number` VARCHAR(20) NULL ,
  PRIMARY KEY (`id`) )
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `tbl_tree_record_file_page`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `tbl_tree_record_file_page` ;

CREATE  TABLE IF NOT EXISTS `tbl_tree_record_file_page` (
  `id` INT NOT NULL AUTO_INCREMENT ,
  `tree_record_file_id` INT NOT NULL ,
  `page` INT NOT NULL ,
  PRIMARY KEY (`id`) ,
  INDEX `fk_tbl_tree_record_file_page_tbl_tree_record_file1_idx` (`tree_record_file_id` ASC) ,
  CONSTRAINT `fk_tbl_tree_record_file_page_tbl_tree_record_file1`
    FOREIGN KEY (`tree_record_file_id` )
    REFERENCES `tbl_tree_record_file` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `tbl_living_plant_tree_record_file_page`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `tbl_living_plant_tree_record_file_page` ;

CREATE  TABLE IF NOT EXISTS `tbl_living_plant_tree_record_file_page` (
  `id` INT NOT NULL AUTO_INCREMENT ,
  `living_plant_id` INT NOT NULL ,
  `tree_record_file_page_id` INT NOT NULL ,
  `corrections_done` TINYINT(1) NOT NULL DEFAULT 0 ,
  `corrections_date` DATE NULL DEFAULT NULL ,
  PRIMARY KEY (`id`) ,
  INDEX `fk_tbl_tree_record_tbl_tree_record_file_page1_idx` (`tree_record_file_page_id` ASC) ,
  INDEX `fk_tbl_living_plant_tree_record_tbl_living_plant1_idx` (`living_plant_id` ASC) ,
  CONSTRAINT `fk_tbl_tree_record_tbl_tree_record_file_page1`
    FOREIGN KEY (`tree_record_file_page_id` )
    REFERENCES `tbl_tree_record_file_page` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_tbl_living_plant_tree_record_tbl_living_plant1`
    FOREIGN KEY (`living_plant_id` )
    REFERENCES `tbl_living_plant` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `tbl_sequence`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `tbl_sequence` ;

CREATE  TABLE IF NOT EXISTS `tbl_sequence` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT ,
  PRIMARY KEY (`id`) )
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `tbl_alternative_accession_number`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `tbl_alternative_accession_number` ;

CREATE  TABLE IF NOT EXISTS `tbl_alternative_accession_number` (
  `id` INT NOT NULL AUTO_INCREMENT ,
  `living_plant_id` INT NOT NULL ,
  `number` VARCHAR(255) NOT NULL ,
  PRIMARY KEY (`id`) ,
  INDEX `fk_tbl_alternative_accession_number_tbl_living_plant1` (`living_plant_id` ASC) ,
  CONSTRAINT `fk_tbl_alternative_accession_number_tbl_living_plant1`
    FOREIGN KEY (`living_plant_id` )
    REFERENCES `tbl_living_plant` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
COMMENT = 'counter for ipen number';


-- -----------------------------------------------------
-- Table `tbl_location_geonames`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `tbl_location_geonames` ;

CREATE  TABLE IF NOT EXISTS `tbl_location_geonames` (
  `id` INT NOT NULL ,
  `service_data` TEXT NOT NULL ,
  `geonameId` INT NOT NULL ,
  `countryCode` VARCHAR(2) NOT NULL ,
  PRIMARY KEY (`id`) ,
  UNIQUE INDEX `geonameId_UNIQUE` (`geonameId` ASC) ,
  CONSTRAINT `fk_tbl_geonames_location_tbl_location1`
    FOREIGN KEY (`id` )
    REFERENCES `tbl_location` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `tbl_acquisition_event_person`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `tbl_acquisition_event_person` ;

CREATE  TABLE IF NOT EXISTS `tbl_acquisition_event_person` (
  `acquisition_event_id` INT NOT NULL ,
  `person_id` INT NOT NULL ,
  PRIMARY KEY (`acquisition_event_id`, `person_id`) ,
  INDEX `fk_tbl_acquisition_event_person_tbl_person1_idx` (`person_id` ASC) ,
  CONSTRAINT `fk_tbl_acquisition_event_person_tbl_acquisition_event1`
    FOREIGN KEY (`acquisition_event_id` )
    REFERENCES `tbl_acquisition_event` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_tbl_acquisition_event_person_tbl_person1`
    FOREIGN KEY (`person_id` )
    REFERENCES `tbl_person` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `tbl_certificate_type`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `tbl_certificate_type` ;

CREATE  TABLE IF NOT EXISTS `tbl_certificate_type` (
  `id` INT NOT NULL AUTO_INCREMENT ,
  `type` VARCHAR(15) NOT NULL ,
  PRIMARY KEY (`id`) ,
  UNIQUE INDEX `type_UNIQUE` (`type` ASC) )
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `tbl_certificate`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `tbl_certificate` ;

CREATE  TABLE IF NOT EXISTS `tbl_certificate` (
  `id` INT NOT NULL AUTO_INCREMENT ,
  `living_plant_id` INT NOT NULL ,
  `certificate_type_id` INT NOT NULL ,
  `number` TEXT NULL ,
  `annotation` TEXT NULL ,
  PRIMARY KEY (`id`) ,
  INDEX `fk_tbl_certificate_tbl_certificate_type1_idx` (`certificate_type_id` ASC) ,
  INDEX `fk_tbl_certificate_tbl_living_plant1_idx` (`living_plant_id` ASC) ,
  CONSTRAINT `fk_tbl_certificate_tbl_certificate_type1`
    FOREIGN KEY (`certificate_type_id` )
    REFERENCES `tbl_certificate_type` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_tbl_certificate_tbl_living_plant1`
    FOREIGN KEY (`living_plant_id` )
    REFERENCES `tbl_living_plant` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `frmwrk_AuthItem`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `frmwrk_AuthItem` ;

CREATE  TABLE IF NOT EXISTS `frmwrk_AuthItem` (
  `name` VARCHAR(64) NOT NULL ,
  `type` INT(11) NOT NULL ,
  `description` TEXT NULL DEFAULT NULL ,
  `bizrule` TEXT NULL DEFAULT NULL ,
  `data` TEXT NULL DEFAULT NULL ,
  PRIMARY KEY (`name`) )
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `frmwrk_AuthAssignment`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `frmwrk_AuthAssignment` ;

CREATE  TABLE IF NOT EXISTS `frmwrk_AuthAssignment` (
  `itemname` VARCHAR(64) NOT NULL ,
  `userid` INT NOT NULL ,
  `bizrule` TEXT NULL DEFAULT NULL ,
  `data` TEXT NULL DEFAULT NULL ,
  PRIMARY KEY (`itemname`) ,
  INDEX `fk_frmwrk_AuthAssignment_frmwrk_user1_idx` (`userid` ASC) ,
  CONSTRAINT `frmwrk_AuthAssignment_ibfk_1`
    FOREIGN KEY (`itemname` )
    REFERENCES `frmwrk_AuthItem` (`name` )
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  CONSTRAINT `fk_frmwrk_AuthAssignment_frmwrk_user1`
    FOREIGN KEY (`userid` )
    REFERENCES `frmwrk_user` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `frmwrk_AuthItemChild`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `frmwrk_AuthItemChild` ;

CREATE  TABLE IF NOT EXISTS `frmwrk_AuthItemChild` (
  `parent` VARCHAR(64) NOT NULL ,
  `child` VARCHAR(64) NOT NULL ,
  PRIMARY KEY (`parent`, `child`) ,
  INDEX `child` (`child` ASC) ,
  CONSTRAINT `frmwrk_AuthItemChild_ibfk_1`
    FOREIGN KEY (`parent` )
    REFERENCES `frmwrk_AuthItem` (`name` )
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  CONSTRAINT `frmwrk_AuthItemChild_ibfk_2`
    FOREIGN KEY (`child` )
    REFERENCES `frmwrk_AuthItem` (`name` )
    ON DELETE CASCADE
    ON UPDATE CASCADE)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `frmwrk_accessOrganisation`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `frmwrk_accessOrganisation` ;

CREATE  TABLE IF NOT EXISTS `frmwrk_accessOrganisation` (
  `id` INT NOT NULL AUTO_INCREMENT ,
  `AuthItem_name` VARCHAR(64) NULL DEFAULT NULL ,
  `user_id` INT NULL DEFAULT NULL ,
  `allowDeny` TINYINT(1) NOT NULL DEFAULT 0 COMMENT '0 = deny, 1 = allow' ,
  `organisation_id` INT NOT NULL DEFAULT 0 ,
  PRIMARY KEY (`id`) ,
  INDEX `fk_frmwrk_AccessOrganisation_frmwrk_AuthItem1` (`AuthItem_name` ASC) ,
  INDEX `fk_frmwrk_AccessOrganisation_frmwrk_user1` (`user_id` ASC) ,
  INDEX `fk_frmwrk_accessOrganisation_tbl_organisation` (`organisation_id` ASC) ,
  UNIQUE INDEX `user_assignment_UNIQUE` (`organisation_id` ASC, `user_id` ASC) ,
  INDEX `group_assignment_UNIQUE` (`organisation_id` ASC, `AuthItem_name` ASC) ,
  CONSTRAINT `fk_frmwrk_AccessOrganisation_frmwrk_AuthItem1`
    FOREIGN KEY (`AuthItem_name` )
    REFERENCES `frmwrk_AuthItem` (`name` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_frmwrk_AccessOrganisation_frmwrk_user1`
    FOREIGN KEY (`user_id` )
    REFERENCES `frmwrk_user` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_frmwrk_accessOrganisation_tbl_organisation`
    FOREIGN KEY (`organisation_id` )
    REFERENCES `tbl_organisation` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
COMMENT = 'access assignment for organisation level';


-- -----------------------------------------------------
-- Table `frmwrk_accessLivingplant`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `frmwrk_accessLivingplant` ;

CREATE  TABLE IF NOT EXISTS `frmwrk_accessLivingplant` (
  `id` INT NOT NULL AUTO_INCREMENT ,
  `AuthItem_name` VARCHAR(64) NULL ,
  `user_id` INT NULL ,
  `allowDeny` TINYINT(1) NOT NULL DEFAULT 0 ,
  `living_plant_id` INT NOT NULL ,
  PRIMARY KEY (`id`) ,
  INDEX `fk_frmwrk_accessLivingplant_frmwrk_AuthItem1` (`AuthItem_name` ASC) ,
  INDEX `fk_frmwrk_accessLivingplant_frmwrk_user1` (`user_id` ASC) ,
  INDEX `fk_frmwrk_accessLivingplant_tbl_living_plant` (`living_plant_id` ASC) ,
  UNIQUE INDEX `user_assignment_UNIQUE` (`user_id` ASC, `living_plant_id` ASC) ,
  UNIQUE INDEX `group_assignment_UNIQUE` (`living_plant_id` ASC, `AuthItem_name` ASC) ,
  CONSTRAINT `fk_frmwrk_accessLivingplant_frmwrk_AuthItem1`
    FOREIGN KEY (`AuthItem_name` )
    REFERENCES `frmwrk_AuthItem` (`name` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_frmwrk_accessLivingplant_frmwrk_user1`
    FOREIGN KEY (`user_id` )
    REFERENCES `frmwrk_user` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_frmwrk_accessLivingplant_tbl_living_plant`
    FOREIGN KEY (`living_plant_id` )
    REFERENCES `tbl_living_plant` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `tbl_import_properties`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `tbl_import_properties` ;

CREATE  TABLE IF NOT EXISTS `tbl_import_properties` (
  `id` INT NOT NULL AUTO_INCREMENT ,
  `botanical_object_id` INT NOT NULL ,
  `IDPflanze` INT NULL ,
  `species_name` VARCHAR(255) NULL ,
  `Revier` TEXT NULL ,
  PRIMARY KEY (`id`) ,
  INDEX `fk_tbl_import_properties_tbl_botanical_object1` (`botanical_object_id` ASC) ,
  CONSTRAINT `fk_tbl_import_properties_tbl_botanical_object1`
    FOREIGN KEY (`botanical_object_id` )
    REFERENCES `tbl_botanical_object` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `tbl_import_error`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `tbl_import_error` ;

CREATE  TABLE IF NOT EXISTS `tbl_import_error` (
  `id` INT NOT NULL ,
  `IDPflanze` INT NOT NULL ,
  `message` TEXT NOT NULL ,
  PRIMARY KEY (`id`) )
ENGINE = InnoDB;



SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;

-- -----------------------------------------------------
-- Data for table `tbl_acquisition_type`
-- -----------------------------------------------------
START TRANSACTION;
USE `jacq_input`;
INSERT INTO `tbl_acquisition_type` (`id`, `type`) VALUES (1, 'unknown');
INSERT INTO `tbl_acquisition_type` (`id`, `type`) VALUES (2, 'full extraction');
INSERT INTO `tbl_acquisition_type` (`id`, `type`) VALUES (3, 'partial extraction');
INSERT INTO `tbl_acquisition_type` (`id`, `type`) VALUES (4, 'photograph');

COMMIT;

-- -----------------------------------------------------
-- Data for table `tbl_phenology`
-- -----------------------------------------------------
START TRANSACTION;
USE `jacq_input`;
INSERT INTO `tbl_phenology` (`id`, `phenology`) VALUES (1, 'unknown');
INSERT INTO `tbl_phenology` (`id`, `phenology`) VALUES (2, 'florid');

COMMIT;

-- -----------------------------------------------------
-- Data for table `tbl_index_seminum_type`
-- -----------------------------------------------------
START TRANSACTION;
USE `jacq_input`;
INSERT INTO `tbl_index_seminum_type` (`id`, `type`) VALUES (1, 'WL');
INSERT INTO `tbl_index_seminum_type` (`id`, `type`) VALUES (2, 'KL');
INSERT INTO `tbl_index_seminum_type` (`id`, `type`) VALUES (3, 'WKL');
INSERT INTO `tbl_index_seminum_type` (`id`, `type`) VALUES (4, 'WS');
INSERT INTO `tbl_index_seminum_type` (`id`, `type`) VALUES (5, 'KS');
INSERT INTO `tbl_index_seminum_type` (`id`, `type`) VALUES (6, 'WKS');

COMMIT;

-- -----------------------------------------------------
-- Data for table `tbl_sex`
-- -----------------------------------------------------
START TRANSACTION;
USE `jacq_input`;
INSERT INTO `tbl_sex` (`id`, `sex`) VALUES (1, 'male');
INSERT INTO `tbl_sex` (`id`, `sex`) VALUES (2, 'female');

COMMIT;

-- -----------------------------------------------------
-- Data for table `tbl_separation_type`
-- -----------------------------------------------------
START TRANSACTION;
USE `jacq_input`;
INSERT INTO `tbl_separation_type` (`id`, `type`) VALUES (1, 'none');
INSERT INTO `tbl_separation_type` (`id`, `type`) VALUES (2, 'sold');

COMMIT;

-- -----------------------------------------------------
-- Data for table `tbl_relevancy_type`
-- -----------------------------------------------------
START TRANSACTION;
USE `jacq_input`;
INSERT INTO `tbl_relevancy_type` (`id`, `type`) VALUES (1, 'teachers');
INSERT INTO `tbl_relevancy_type` (`id`, `type`) VALUES (2, 'students');
INSERT INTO `tbl_relevancy_type` (`id`, `type`) VALUES (3, 'public');

COMMIT;

-- -----------------------------------------------------
-- Data for table `tbl_sequence`
-- -----------------------------------------------------
START TRANSACTION;
USE `jacq_input`;
INSERT INTO `tbl_sequence` (`id`) VALUES (0);

COMMIT;

-- -----------------------------------------------------
-- Data for table `tbl_certificate_type`
-- -----------------------------------------------------
START TRANSACTION;
USE `jacq_input`;
INSERT INTO `tbl_certificate_type` (`id`, `type`) VALUES (1, 'cites');
INSERT INTO `tbl_certificate_type` (`id`, `type`) VALUES (2, 'phyto');
INSERT INTO `tbl_certificate_type` (`id`, `type`) VALUES (3, 'pic');
INSERT INTO `tbl_certificate_type` (`id`, `type`) VALUES (4, 'abs');
INSERT INTO `tbl_certificate_type` (`id`, `type`) VALUES (5, 'zoll');
INSERT INTO `tbl_certificate_type` (`id`, `type`) VALUES (6, 'ipen');
INSERT INTO `tbl_certificate_type` (`id`, `type`) VALUES (7, 'custom');

COMMIT;
