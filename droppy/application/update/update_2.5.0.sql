ALTER TABLE droppy_backgrounds MODIFY src LONGTEXT;
ALTER TABLE droppy_backgrounds MODIFY `url` LONGTEXT;

ALTER TABLE droppy_files ADD `thumb` int(11) DEFAULT 0 AFTER `original_path`;