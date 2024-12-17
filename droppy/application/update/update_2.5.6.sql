ALTER TABLE droppy_language ADD `locale` varchar(255) DEFAULT NULL;

UPDATE droppy_language SET locale = 'en-US,en-GB,en-AU,en-CA,en-NZ,en-ZA' WHERE `path` = 'english';
UPDATE droppy_language SET locale = 'de-DE' WHERE `path` = 'german';
UPDATE droppy_language SET locale = 'fr-FR' WHERE `path` = 'french';
UPDATE droppy_language SET locale = 'nl-NL' WHERE `path` = 'dutch';
UPDATE droppy_language SET locale = 'es-ES' WHERE `path` = 'spanish';
UPDATE droppy_language SET locale = 'it-IT' WHERE `path` = 'italian';
UPDATE droppy_language SET locale = 'pt-PT' WHERE `path` = 'portuguese';
UPDATE droppy_language SET locale = 'ru-RU' WHERE `path` = 'russian';
UPDATE droppy_language SET locale = 'tr-TR' WHERE `path` = 'turkish';

ALTER TABLE droppy_accounts ADD `reset_id` varchar(255) DEFAULT NULL;
