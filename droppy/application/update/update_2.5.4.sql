ALTER TABLE droppy_settings ADD `mobile_logo_path` varchar(255) AFTER logo_path;
UPDATE droppy_settings SET mobile_logo_path = logo_path;

ALTER TABLE droppy_pages ADD `order` int(22) DEFAULT NULL;