ALTER TABLE droppy_settings ADD `date_format` varchar(255) DEFAULT 'Y-m-d H:i' AFTER `timezone`;
ALTER TABLE droppy_settings ADD `session_expiration` int(25) DEFAULT 7200 AFTER `date_format`;