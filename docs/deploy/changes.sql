
-- -----------------------------------------------------
-- Table `tbl_index_seminum_revision`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `tbl_index_seminum_revision` (
  `index_seminum_revision_id` INT NOT NULL AUTO_INCREMENT,
  `user_id` INT NOT NULL,
  `name` VARCHAR(50) NOT NULL,
  `timestamp` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`index_seminum_revision_id`),
  INDEX `fk_tbl_index_seminum_revision_frmwrk_user_idx` (`user_id` ASC),
  UNIQUE INDEX `revision_name_UNIQUE` (`name` ASC),
  CONSTRAINT `fk_tbl_index_seminum_revision_frmwrk_user`
    FOREIGN KEY (`user_id`)
    REFERENCES `frmwrk_user` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;

-- -----------------------------------------------------
-- Table `tbl_index_seminum_content`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `tbl_index_seminum_content` (
  `index_seminum_content_id` INT NOT NULL AUTO_INCREMENT,
  `index_seminum_revision_id` INT NOT NULL,
  `botanical_object_id` INT NOT NULL,
  `accession_number` INT NOT NULL,
  `family` TEXT NOT NULL,
  `scientific_name` TEXT NOT NULL,
  `index_seminum_type` VARCHAR(3) NOT NULL,
  `ipen_number` VARCHAR(28) NOT NULL,
  `acquisition_number` TEXT NULL,
  `acquisition_locality` VARCHAR(45) NULL,
  `habitat` VARCHAR(45) NULL,
  `altitude_min` INT NULL,
  `altitude_max` INT NULL,
  `latitude` VARCHAR(45) NULL,
  `longitude` VARCHAR(45) NULL,
  `acquisition_date` VARCHAR(45) NULL,
  `timestamp` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`index_seminum_content_id`),
  INDEX `fk_tbl_index_seminum_content_tbl_botanical_object1_idx` (`botanical_object_id` ASC),
  INDEX `fk_tbl_index_seminum_content_tbl_index_seminum_revision1_idx` (`index_seminum_revision_id` ASC),
  CONSTRAINT `fk_tbl_index_seminum_content_tbl_botanical_object1`
    FOREIGN KEY (`botanical_object_id`)
    REFERENCES `tbl_botanical_object` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_tbl_index_seminum_content_tbl_index_seminum_revision1`
    FOREIGN KEY (`index_seminum_revision_id`)
    REFERENCES `tbl_index_seminum_revision` (`index_seminum_revision_id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;

-- -----------------------------------------------------
-- Table `tbl_index_seminum_person`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `tbl_index_seminum_person` (
  `index_seminum_person_id` INT NOT NULL AUTO_INCREMENT,
  `index_seminum_content_id` INT NOT NULL,
  `name` VARCHAR(255) NOT NULL,
  `timestamp` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`index_seminum_person_id`),
  INDEX `fk_tbl_index_seminum_person_tbl_index_seminum_content_idx` (`index_seminum_content_id` ASC),
  CONSTRAINT `fk_tbl_index_seminum_person_tbl_index_seminum_content`
    FOREIGN KEY (`index_seminum_content_id`)
    REFERENCES `tbl_index_seminum_content` (`index_seminum_content_id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;

INSERT INTO `frmwrk_AuthItem` (`name`, `type`, `description`, `bizrule`, `data`) VALUES
('oprtn_indexSeminum', 0, 'Access to index seminum actions', NULL, 'N;');

INSERT INTO `frmwrk_AuthItemChild` (`parent`, `child`) VALUES
('grp_admin', 'oprtn_indexSeminum');

ALTER TABLE `jacq_input`.`tbl_relevancy_type` 
ADD COLUMN `important` TINYINT(1) NOT NULL DEFAULT 0 AFTER `type`;
