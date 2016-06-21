ALTER TABLE `tbl_living_plant`  ADD `reviewed` BOOLEAN NOT NULL DEFAULT FALSE  AFTER `bgci`;

ALTER TABLE `tbl_living_plant_log`  ADD `reviewed` BOOLEAN NOT NULL DEFAULT FALSE  AFTER `bgci`;
