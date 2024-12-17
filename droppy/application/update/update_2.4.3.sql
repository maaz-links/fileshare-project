ALTER TABLE droppy_settings ADD COLUMN `file_previews` varchar(10) DEFAULT 'false' AFTER `share_enabled`;

ALTER TABLE droppy_downloads ALTER COLUMN email SET DEFAULT NULL;
ALTER TABLE droppy_downloads ALTER COLUMN ip SET DEFAULT NULL;

ALTER TABLE droppy_uploads ADD COLUMN `file_previews` varchar(10) DEFAULT 'false';