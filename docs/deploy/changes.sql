# cleanup old date entries (not allowed anymore)
UPDATE `tbl_living_plant` SET `cultivation_date` = null WHERE `cultivation_date` LIKE '%00-00%';
UPDATE `tbl_separation` SET `date` = null WHERE `date` LIKE '%-00%';

-- MySQL Workbench Synchronization
-- Generated: 2016-12-09 10:52
-- Model: Livingplants
-- Version: 1.0
-- Project: Livingplants
-- Author: Wolfgang Koller

SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='TRADITIONAL,ALLOW_INVALID_DATES';

ALTER TABLE `jacq_input`.`tbl_living_plant`
DROP FOREIGN KEY `fk_livingplant_object1`;

ALTER TABLE `jacq_input`.`tbl_living_plant`
CHANGE COLUMN `reviewed` `reviewed` TINYINT(1) NULL DEFAULT 0 COMMENT '' ;

ALTER TABLE `jacq_input`.`tbl_separation`
ADD COLUMN `derivative_vegetative_id` INT NOT NULL COMMENT '' AFTER `botanical_object_id`,
ADD INDEX `fk_tbl_separation_tbl_derivative_vegetative1_idx` (`derivative_vegetative_id` ASC)  COMMENT '';

CREATE TABLE IF NOT EXISTS `tbl_derivative_vegetative` (
  `derivative_vegetative_id` INT NOT NULL AUTO_INCREMENT COMMENT '',
  `living_plant_id` INT NOT NULL COMMENT '',
  `accesion_number` INT NOT NULL COMMENT '',
  `organisation_id` INT NOT NULL COMMENT '',
  `phenology_id` INT NOT NULL COMMENT '',
  `cultivation_date` DATE NULL DEFAULT NULL COMMENT '',
  `annotation` TEXT NULL DEFAULT NULL COMMENT '',
  UNIQUE INDEX `accesion_number_UNIQUE` (`accesion_number` ASC)  COMMENT '',
  INDEX `fk_tbl_vegetative_derivative_tbl_organisation1_idx` (`organisation_id` ASC)  COMMENT '',
  INDEX `fk_tbl_vegetative_derivative_tbl_phenology1_idx` (`phenology_id` ASC)  COMMENT '',
  PRIMARY KEY (`derivative_vegetative_id`)  COMMENT '',
  INDEX `fk_tbl_derivative_vegetative_tbl_living_plant1_idx` (`living_plant_id` ASC)  COMMENT '',
  CONSTRAINT `fk_tbl_vegetative_derivative_tbl_organisation1`
    FOREIGN KEY (`organisation_id`)
    REFERENCES `tbl_organisation` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_tbl_vegetative_derivative_tbl_phenology1`
    FOREIGN KEY (`phenology_id`)
    REFERENCES `tbl_phenology` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_tbl_derivative_vegetative_tbl_living_plant1`
    FOREIGN KEY (`living_plant_id`)
    REFERENCES `tbl_living_plant` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;

ALTER TABLE `jacq_input`.`tbl_living_plant`
ADD CONSTRAINT `fk_livingplant_object1`
  FOREIGN KEY (`id`)
  REFERENCES `jacq_input`.`tbl_botanical_object` (`id`)
  ON DELETE CASCADE
  ON UPDATE CASCADE;

ALTER TABLE `jacq_input`.`tbl_separation`
ADD CONSTRAINT `fk_tbl_separation_tbl_derivative_vegetative1`
    FOREIGN KEY (`derivative_vegetative_id`)
    REFERENCES `tbl_derivative_vegetative` (`derivative_vegetative_id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION;


DELIMITER $$
USE `jacq_input`$$
CREATE DEFINER=`root`@`localhost` FUNCTION `GetScientificName`(`p_taxonID` INT(11), `p_bAvoidHybridFormula` TINYINT(1) UNSIGNED, `p_bNoAuthor` TINYINT(1) UNSIGNED) RETURNS text CHARSET utf8
    READS SQL DATA
    COMMENT 'mirror function to herbar_view for simplicity'
BEGIN
  DECLARE v_scientificName TEXT;
  DECLARE v_author TEXT;

  CALL `herbar_view`._buildScientificNameComponents(p_taxonID, v_scientificName, v_author);

  IF p_bNoAuthor = 1 THEN
  	return v_scientificName;
  ELSE
  	return CONCAT_WS(' ', v_scientificName, v_author );
  END IF;
END$$

DELIMITER ;

SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;
