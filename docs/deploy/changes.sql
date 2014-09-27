
-- -----------------------------------------------------
-- Table `tbl_index_seminum_revision`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `tbl_index_seminum_revision` (
  `index_seminum_revision_id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` INT UNSIGNED NOT NULL,
  `timestamp` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`index_seminum_revision_id`),
  INDEX `fk_tbl_index_seminum_revision_frmwrk_user_idx` (`user_id` ASC),
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
  `index_seminum_revision_id` INT UNSIGNED NOT NULL,
  `botanical_object_id` INT NOT NULL,
  `accession_number` INT NOT NULL,
  `scientific_name` TEXT NOT NULL,
  `index_seminum_type` VARCHAR(3) NOT NULL,
  `ipen_number` VARCHAR(20) NOT NULL,
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
