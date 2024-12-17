ALTER TABLE droppy_settings MODIFY expire LONGTEXT;
ALTER TABLE droppy_files ADD COLUMN `original_path` LONGTEXT DEFAULT NULL AFTER `file`;