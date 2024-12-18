CREATE TABLE IF NOT EXISTS `droppy_sessions` (
        `id` varchar(128) NOT NULL,
        `ip_address` varchar(45) NOT NULL,
        `timestamp` int(10) unsigned DEFAULT 0 NOT NULL,
        `data` blob NOT NULL,
        KEY `ci_sessions_timestamp` (`timestamp`)
);

UPDATE droppy_templates SET lang = LOWER(lang);
UPDATE droppy_templates SET lang = REPLACE(lang, '.php', '');

UPDATE droppy_uploads SET lang = LOWER(lang);
UPDATE droppy_uploads SET lang = REPLACE(lang, '.php', '');

UPDATE droppy_uploads SET destruct = LOWER(destruct);
UPDATE droppy_uploads SET password = '' WHERE password = 'EMPTY';

UPDATE droppy_themes SET path = REPLACE(path, '/', '');

TRUNCATE TABLE droppy_accounts;

ALTER TABLE droppy_files ADD `size` int(20) DEFAULT 0;
ALTER TABLE droppy_files ADD `time` int(20) DEFAULT NULL;

ALTER TABLE droppy_backgrounds ADD `duration` int(10) DEFAULT NULL;

ALTER TABLE droppy_settings ADD `timezone` varchar(255) DEFAULT NULL;

UPDATE droppy_settings SET `language` = LOWER(`language`);
UPDATE droppy_settings SET `language` = REPLACE(`language`, '.php', '');

UPDATE droppy_language SET `path` = LOWER(`path`);
UPDATE droppy_language SET `path` = REPLACE(`path`, '.php', '');

/** V2.0.3 **/

ALTER TABLE droppy_settings ADD `timezone` varchar(255) DEFAULT NULL;


INSERT INTO `droppy_updates` (`version`, `type`, `date`) VALUES ('2.1.6', '', CURRENT_TIMESTAMP);
UPDATE droppy_settings SET version = '2.1.3' LIMIT 1;