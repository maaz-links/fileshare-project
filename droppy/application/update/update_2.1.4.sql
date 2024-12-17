ALTER TABLE droppy_settings ADD `site_keywords` varchar(255) DEFAULT NULL AFTER `site_desc`;
ALTER TABLE droppy_settings ADD `recaptcha_key` varchar(100) DEFAULT NULL;
ALTER TABLE droppy_settings ADD `recaptcha_secret` varchar(100) DEFAULT NULL;
ALTER TABLE droppy_settings ADD `contact_enabled` varchar(10) DEFAULT 'false';
ALTER TABLE droppy_settings ADD `contact_email` varchar(255) DEFAULT NULL;