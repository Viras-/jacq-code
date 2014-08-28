ALTER TABLE `frmwrk_user` ADD `force_password_change` BOOLEAN NOT NULL DEFAULT FALSE ;

ALTER TABLE `frmwrk_user_log` ADD `force_password_change` BOOLEAN NOT NULL DEFAULT FALSE AFTER `organisation_id`;

-- srvc_uuid_minter_type
-- srvc_uuid_minter

INSERT INTO `srvc_uuid_minter_type` (`uuid_minter_type_id`, `description`, `timestamp`) VALUES (2, 'citation', NOW());
