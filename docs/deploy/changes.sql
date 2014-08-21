ALTER TABLE `frmwrk_user` ADD `force_password_change` BOOLEAN NOT NULL DEFAULT FALSE ;

ALTER TABLE `frmwrk_user_log` ADD `force_password_change` BOOLEAN NOT NULL DEFAULT FALSE AFTER `organisation_id`;

-- srvc_uuid_minter_type
-- srvc_uuid_minter