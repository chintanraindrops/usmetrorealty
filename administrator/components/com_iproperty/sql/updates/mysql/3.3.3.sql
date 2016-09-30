ALTER IGNORE TABLE `#__iproperty_agents` ADD `title` varchar(125) NOT NULL DEFAULT '' AFTER `company`;

ALTER IGNORE TABLE `#__iproperty_settings` ADD `agents_perpage` smallint DEFAULT 20 AFTER `perpage`;
ALTER IGNORE TABLE `#__iproperty_settings` ADD `qs_show_subdivision` tinyint(1) unsigned NOT NULL DEFAULT '0' AFTER `qs_show_region`;
ALTER IGNORE TABLE `#__iproperty_settings` ADD `qs_show_cascade` tinyint(1) unsigned NOT NULL DEFAULT '1' AFTER `qs_show_acres`;
ALTER IGNORE TABLE `#__iproperty_settings` ADD `match_against` tinyint(1) unsigned NOT NULL DEFAULT '1';
