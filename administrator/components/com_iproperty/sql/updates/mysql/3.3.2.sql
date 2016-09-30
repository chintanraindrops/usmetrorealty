ALTER IGNORE TABLE `#__iproperty_settings` DROP `gallery_width`;
ALTER IGNORE TABLE `#__iproperty_settings` DROP `gallery_height`;

ALTER IGNORE TABLE `#__iproperty_settings` ADD `gallery_s3_key` varchar(125) NOT NULL DEFAULT '' AFTER `gallerytype`;
ALTER IGNORE TABLE `#__iproperty_settings` ADD `gallery_s3_secret` varchar(125) NOT NULL DEFAULT '' AFTER `gallerytype`;
ALTER IGNORE TABLE `#__iproperty_settings` ADD `gallery_s3_bucket` varchar(125) NOT NULL DEFAULT '' AFTER `gallerytype`;
ALTER IGNORE TABLE `#__iproperty_settings` ADD `gallery_use_s3` tinyint(1) unsigned NOT NULL DEFAULT '0' AFTER `gallerytype`;
ALTER IGNORE TABLE `#__iproperty_settings` ADD `adv_show_keyword` tinyint(1) unsigned NOT NULL DEFAULT '0' AFTER `adv_show_stype`;
ALTER IGNORE TABLE `#__iproperty_settings` ADD `qs_show_lotsize` tinyint(1) unsigned NOT NULL DEFAULT '0' AFTER `qs_show_sqft`;
ALTER IGNORE TABLE `#__iproperty_settings` ADD `qs_show_acres` tinyint(1) unsigned NOT NULL DEFAULT '0' AFTER `qs_show_lotsize`;

ALTER IGNORE TABLE `#__iproperty` ADD `subdivision` varchar(55) NOT NULL AFTER `agent_notes`;
