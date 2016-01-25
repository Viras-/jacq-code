CREATE VIEW `jacq_input`.`view_person` AS
    SELECT
        `jacq_input`.`tbl_person`.`id` AS `id`,
        `jacq_input`.`tbl_person`.`name` AS `name`
    FROM
        `jacq_input`.`tbl_person`
    UNION SELECT
        `herbarinput`.`tbl_collector`.`SammlerID` AS `SammlerID`,
        `herbarinput`.`tbl_collector`.`Sammler` AS `Sammler`
    FROM
        `herbarinput`.`tbl_collector`
;

