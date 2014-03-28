ALTER TABLE `tbl_living_plant` CHANGE `accession_number` `accession_number_old` INT(11) NOT NULL;

ALTER TABLE `tbl_living_plant` DROP INDEX `accession_number_UNIQUE`;

