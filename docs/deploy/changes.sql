ALTER TABLE `tbl_living_plant` 
ADD COLUMN `ipen_type` ENUM('default', 'custom') NOT NULL DEFAULT 'default' AFTER `ipen_locked`

ALTER TABLE `tbl_living_plant_log` 
ADD COLUMN `ipen_type` ENUM('default', 'custom') NOT NULL DEFAULT 'default' AFTER `ipen_locked`
