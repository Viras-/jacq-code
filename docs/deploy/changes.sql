ALTER TABLE `tbl_relevancy_type` ADD `important` TINYINT(1) NOT NULL DEFAULT '0' ; 

ALTER TABLE `tbl_living_plant` 
ADD COLUMN `bcgi` TINYINT(1) NOT NULL DEFAULT 0 AFTER `label_annotation`;
