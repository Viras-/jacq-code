ALTER TABLE `tbl_living_plant` 
ADD COLUMN `ipen_type` ENUM('default', 'custom') NOT NULL DEFAULT 'default' AFTER `ipen_locked`

ALTER TABLE `tbl_living_plant_log` 
ADD COLUMN `ipen_type` ENUM('default', 'custom') NOT NULL DEFAULT 'default' AFTER `ipen_locked`

CREATE TABLE IF NOT EXISTS `frmwrk_config` (
  `fc_id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `fc_name` VARCHAR(45) NOT NULL,
  `fc_value` TEXT NULL DEFAULT NULL,
  PRIMARY KEY (`fc_id`),
  UNIQUE INDEX `fc_name_UNIQUE` (`fc_name` ASC))
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_general_ci
COMMENT = 'Global config table for holding all configuration values of the JACQ system.';

CREATE TABLE IF NOT EXISTS `frmwrk_template` (
  `ft_id` INT(11) NOT NULL,
  `ft_name` VARCHAR(45) NOT NULL,
  `ft_template` BLOB NOT NULL,
  PRIMARY KEY (`ft_id`),
  UNIQUE INDEX `ft_name_UNIQUE` (`ft_name` ASC))
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_general_ci
COMMENT = 'Generic template table for holding binary template files for processing (e.g. BIRT report layouts).';

INSERT INTO `frmwrk_config` (`fc_id`, `fc_name`, `fc_value`) VALUES (1, 'birt.work_label', '/opt/jacq-javaee/jacq-birt/hbv_worklabel.rptdesign');
