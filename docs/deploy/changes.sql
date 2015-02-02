ALTER TABLE `tbl_relevancy_type` ADD `important` TINYINT(1) NOT NULL DEFAULT '0' ; 

ALTER TABLE `tbl_living_plant` ADD COLUMN `bgci` TINYINT(1) NOT NULL DEFAULT 0 AFTER `label_annotation`;
ALTER TABLE `tbl_living_plant_log` ADD COLUMN `bgci` TINYINT(1) NOT NULL DEFAULT 0 AFTER `label_annotation`; 

CREATE DEFINER=`root`@`localhost` PROCEDURE `GetScientificNameComponents`(IN `p_taxonID` INT(11), OUT `p_genericEpithet` TEXT CHARSET utf8, OUT `p_specificEpithet` TEXT CHARSET utf8, OUT `p_infraspecificRank` TEXT CHARSET utf8, OUT `p_infraspecificEpithet` TEXT CHARSET utf8, OUT `p_author` TEXT CHARSET utf8)
    READS SQL DATA
BEGIN
  DECLARE v_genus varchar(100);
  DECLARE v_DallaTorreIDs int(11);
  DECLARE v_family varchar(100);
  DECLARE v_category varchar(2);
  DECLARE v_author_g varchar(255);
  DECLARE v_epithet varchar(100);
  DECLARE v_author varchar(100);
  DECLARE v_epithet1 varchar(100);
  DECLARE v_author1 varchar(100);
  DECLARE v_epithet2 varchar(100);
  DECLARE v_author2 varchar(100);
  DECLARE v_epithet3 varchar(100);
  DECLARE v_author3 varchar(100);
  DECLARE v_epithet4 varchar(100);
  DECLARE v_author4 varchar(100);
  DECLARE v_epithet5 varchar(100);
  DECLARE v_author5 varchar(100);
  DECLARE v_rank_abbr varchar(255);
  
  SELECT
    vt.`genus`, vt.`DallaTorreIDs`, vt.`family`, vt.`author_g`, vt.`epithet`, vt.`author`,
    vt.`epithet1`, vt.`author1`,vt.`epithet2`, vt.`author2`, vt.`epithet3`, vt.`author3`,
    vt.`epithet4`, vt.`author4`, vt.`epithet5`, vt.`author5`, vt.`rank_abbr`
  INTO
    v_genus, v_DallaTorreIDs, v_family, v_author_g, v_epithet, v_author, v_epithet1, v_author1,
    v_epithet2, v_author2, v_epithet3, v_author3, v_epithet4, v_author4, v_epithet5, v_author5,
    v_rank_abbr
  FROM
    `view_taxon` vt
  WHERE
    vt.`taxonID` = p_taxonID
  LIMIT 1;

  SET p_genericEpithet = v_genus;
  
  IF
    ( v_epithet IS NULL AND v_epithet1 IS NULL AND v_epithet2 IS NULL
    AND v_epithet3 IS NULL AND v_epithet4 IS NULL AND v_epithet5 IS NULL )
  THEN
    SET p_author = v_author_g;
  ELSE
    SET p_specificEpithet = v_epithet;
    SET p_infraspecificRank = v_rank_abbr;
    
    IF v_epithet IS NOT NULL THEN
      SET p_author = v_author;
    END IF;
    IF v_epithet1 IS NOT NULL THEN
      IF v_author1 IS NULL AND v_epithet1 = v_epithet THEN
        SET v_author1 = v_author;
      END IF;
      SET p_infraspecificEpithet = v_epithet1;
      SET p_author = v_author1;
    END IF;
    IF v_epithet2 IS NOT NULL THEN
      IF v_author2 IS NULL AND v_epithet2 = v_epithet THEN
        SET v_author2 = v_author;
      END IF;
      SET p_infraspecificEpithet = v_epithet2;
      SET p_author = v_author2;
    END IF;
    IF v_epithet3 IS NOT NULL THEN
      IF v_author3 IS NULL AND v_epithet3 = v_epithet THEN
        SET v_author3 = v_author;
      END IF;
      SET p_infraspecificEpithet = v_epithet3;
      SET p_author = v_author3;
    END IF;
    IF v_epithet4 IS NOT NULL THEN
      IF v_author4 IS NULL AND v_epithet4 = v_epithet THEN
        SET v_author4 = v_author;
      END IF;
      SET p_infraspecificEpithet = v_epithet4;
      SET p_author = v_author4;
    END IF;
    IF v_epithet5 IS NOT NULL THEN
      IF v_author5 IS NULL AND v_epithet5 = v_epithet THEN
        SET v_author5 = v_author;
      END IF;
      SET p_infraspecificEpithet = v_epithet5;
      SET p_author = v_author5;
    END IF;
  END IF;
END

ALTER TABLE `tbl_inventory_object` 
DROP FOREIGN KEY `fk_tbl_inventory_object_tbl_botanical_object1`;

ALTER TABLE `tbl_specimen` 
CHANGE COLUMN `id_specimen` `specimen_id` INT(11) NOT NULL AUTO_INCREMENT ,
CHANGE COLUMN `herbar_number` `barcode` VARCHAR(20) NULL DEFAULT NULL ,
ADD COLUMN `curatorial_unit_id` INT(11) NOT NULL AFTER `botanical_object_id`,
ADD COLUMN `timestamp` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP AFTER `barcode`,
ADD INDEX `fk_tbl_specimen_tbl_curatorial_unit1_idx` (`curatorial_unit_id` ASC);

ALTER TABLE `tbl_inventory_object` 
DROP COLUMN `timestamp`,
CHANGE COLUMN `botanical_object_id` `botanical_object_id` INT(11) NULL DEFAULT NULL ,
CHANGE COLUMN `log_id` `message` INT(11) NOT NULL COMMENT 'Logging message produced by inventory type handler' ;

CREATE TABLE IF NOT EXISTS `tbl_curatorial_unit` (
  `curatorial_unit_id` INT(11) NOT NULL AUTO_INCREMENT,
  `curatorial_unit_type_id` INT(11) NOT NULL,
  `inventory_number` VARCHAR(45) NULL DEFAULT NULL,
  `acquisition_number` VARCHAR(45) NULL DEFAULT NULL,
  `barcode` VARCHAR(20) NULL DEFAULT NULL,
  `timestamp` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`curatorial_unit_id`),
  INDEX `fk_tbl_curatorial_unit_tbl_curatorial_unit_type1_idx` (`curatorial_unit_type_id` ASC),
  CONSTRAINT `fk_tbl_curatorial_unit_tbl_curatorial_unit_type1`
    FOREIGN KEY (`curatorial_unit_type_id`)
    REFERENCES `tbl_curatorial_unit_type` (`curatorial_unit_type_id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_general_ci;

CREATE TABLE IF NOT EXISTS `tbl_curatorial_unit_type` (
  `curatorial_unit_type_id` INT(11) NOT NULL AUTO_INCREMENT,
  `type_name` VARCHAR(45) NOT NULL,
  `timestamp` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`curatorial_unit_type_id`))
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_general_ci;

ALTER TABLE `tbl_specimen` 
ADD CONSTRAINT `fk_tbl_specimen_tbl_curatorial_unit1`
  FOREIGN KEY (`curatorial_unit_id`)
  REFERENCES `tbl_curatorial_unit` (`curatorial_unit_id`)
  ON DELETE NO ACTION
  ON UPDATE NO ACTION;

ALTER TABLE `tbl_inventory_object` 
ADD CONSTRAINT `fk_tbl_inventory_object_tbl_botanical_object1`
  FOREIGN KEY (`botanical_object_id`)
  REFERENCES `tbl_botanical_object` (`id`)
  ON DELETE NO ACTION
  ON UPDATE NO ACTION;

ALTER TABLE `jacq_input`.`tbl_inventory_object` 
ADD COLUMN `timestamp` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP AFTER `message`;
