CREATE TABLE IF NOT EXISTS posts_statistics
(
    id INT AUTO_INCREMENT NOT NULL,
    original_id VARCHAR(100) NOT NULL,
    message_length INT DEFAULT 0,
    from_name VARCHAR(255) DEFAULT NULL,
    from_id VARCHAR(255) DEFAULT NULL,
    created_time DATETIME NOT NULL,
    PRIMARY KEY(id),
    UNIQUE KEY `original_id_ux` (`original_id`)
)
DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB;
