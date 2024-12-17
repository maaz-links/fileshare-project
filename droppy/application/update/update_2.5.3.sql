ALTER TABLE droppy_pages ADD `lang` varchar(255) DEFAULT 'english';
ALTER TABLE droppy_pages ADD `content` longtext DEFAULT 'english';

INSERT INTO droppy_pages (type, title, content)
VALUES ('terms_page', 'Terms of service', (SELECT terms_text FROM droppy_settings LIMIT 1));

INSERT INTO droppy_pages (type, title, content)
VALUES ('page', 'About Us', (SELECT about_text FROM droppy_settings LIMIT 1));