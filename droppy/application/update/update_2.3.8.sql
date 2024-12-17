DROP TABLE `droppy_pages`;
CREATE TABLE IF NOT EXISTS `droppy_pages` (
    `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
    `type` varchar(100) NOT NULL DEFAULT '',
    `title` varchar(255) DEFAULT NULL,
    `content` longtext DEFAULT NULL,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;