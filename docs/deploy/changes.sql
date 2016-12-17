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
  `accession_number` INT NOT NULL COMMENT '',
  `organisation_id` INT NOT NULL COMMENT '',
  `phenology_id` INT NOT NULL COMMENT '',
  `cultivation_date` DATE NULL DEFAULT NULL COMMENT '',
  `index_seminum` TINYINT(1) NOT NULL DEFAULT 0 COMMENT '',
  `annotation` TEXT NULL DEFAULT NULL COMMENT '',
  UNIQUE INDEX `accession_number_UNIQUE` (`accession_number` ASC)  COMMENT '',
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

ALTER TABLE `tbl_index_seminum_content`
CHANGE COLUMN `accession_number` `accession_number` TEXT NOT NULL COMMENT '' ;

UPDATE `tbl_index_seminum_content`
SET `accession_number` = LPAD(`accession_number`, 7, '0');

ALTER TABLE `jacq_input`.`tbl_separation`
DROP FOREIGN KEY `fk_tbl_separation_tbl_derivative_vegetative1`;

ALTER TABLE `jacq_input`.`tbl_separation`
CHANGE COLUMN `derivative_vegetative_id` `derivative_vegetative_id` INT(11) NULL DEFAULT NULL COMMENT '' ,
CHANGE COLUMN `separation_type_id` `separation_type_id` INT(11) NULL DEFAULT NULL COMMENT '' ;

ALTER TABLE `jacq_input`.`tbl_separation`
DROP FOREIGN KEY `fk_tbl_separation_tbl_separation_type1`;

ALTER TABLE `jacq_input`.`tbl_separation` ADD CONSTRAINT `fk_tbl_separation_tbl_separation_type1`
  FOREIGN KEY (`separation_type_id`)
  REFERENCES `jacq_input`.`tbl_separation_type` (`id`)
  ON DELETE NO ACTION
  ON UPDATE NO ACTION,
ADD CONSTRAINT `fk_tbl_separation_tbl_derivative_vegetative1`
  FOREIGN KEY (`derivative_vegetative_id`)
  REFERENCES `jacq_input`.`tbl_derivative_vegetative` (`derivative_vegetative_id`)
  ON DELETE NO ACTION
  ON UPDATE NO ACTION;

ALTER TABLE `jacq_input`.`tbl_separation`
DROP FOREIGN KEY `fk_tbl_separation_tbl_separation_type1`;

ALTER TABLE `jacq_input`.`tbl_separation`
CHANGE COLUMN `botanical_object_id` `botanical_object_id` INT(11) NULL DEFAULT NULL COMMENT '' ,
CHANGE COLUMN `separation_type_id` `separation_type_id` INT(11) NOT NULL COMMENT '' ;

ALTER TABLE `jacq_input`.`tbl_separation`
DROP FOREIGN KEY `fk_tbl_separation_tbl_botanical_object1`;

ALTER TABLE `jacq_input`.`tbl_separation` ADD CONSTRAINT `fk_tbl_separation_tbl_separation_type1`
  FOREIGN KEY (`separation_type_id`)
  REFERENCES `jacq_input`.`tbl_separation_type` (`id`)
  ON DELETE NO ACTION
  ON UPDATE NO ACTION,
ADD CONSTRAINT `fk_tbl_separation_tbl_botanical_object1`
  FOREIGN KEY (`botanical_object_id`)
  REFERENCES `jacq_input`.`tbl_botanical_object` (`id`)
  ON DELETE CASCADE
  ON UPDATE CASCADE;


-- MySQL Workbench Synchronization
-- Generated: 2016-12-17 11:03
-- Model: Livingplants
-- Version: 1.0
-- Project: Livingplants
-- Author: Wolfgang Koller

SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='TRADITIONAL,ALLOW_INVALID_DATES';

ALTER TABLE `jacq_input`.`tbl_specimen`
DROP COLUMN `curatorial_unit_id`,
CHANGE COLUMN `barcode` `herbar_number` VARCHAR(20) NOT NULL COMMENT '' ,
DROP INDEX `fk_tbl_specimen_tbl_curatorial_unit1_idx` ;

DROP TABLE IF EXISTS `jacq_input`.`tbl_curatorial_unit_type` ;

DROP TABLE IF EXISTS `jacq_input`.`tbl_curatorial_unit` ;


SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;
