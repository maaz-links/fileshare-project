ALTER TABLE droppy_settings ADD COLUMN `allow_iframe` VARCHAR(10) NULL DEFAULT 'false';
ALTER TABLE droppy_settings ADD COLUMN `custom_css_code` TEXT NULL;
ALTER TABLE droppy_settings ADD COLUMN `download_report` VARCHAR(10) NULL DEFAULT 'false';