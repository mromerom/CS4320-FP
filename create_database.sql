--Created 10/27/2016 by Kevin Free

DROP TABLE IF EXISTS `user`;
create table `user` (
    `user_id` INTEGER NOT NULL,
    `user_type` BOOLEAN, -- 0==researcher, 1==admin
    `username` VARCHAR NOT NULL,
    `f_name` VARCHAR DEFAULT NULL,
    `l_name` VARCHAR NOT NULL,
    PRIMARY KEY(`user_id`)
) ENGINE = INNODB;

DROP TABLE IF EXISTS `manifest`;
create table `manifest` (
    `title` VARCHAR NOT NULL,
    `manifest_id` INTEGER NOT NULL,
    `user_id` INTEGER NOT NULL,
    FOREIGN KEY (`user_id`) REFERENCES `user`(`user_id`) ON DELETE CASCADE,
    PRIMARY KEY(`manifest_id`)
) ENGINE = INNODB;

DROP TABLE IF EXISTS `uploads`;
create table `uploads` (
    `upload_time` DATETIME NOT NULL;
    `manifest_id` INTEGER NOT NULL;
    `user_id` INTEGER NOT NULL;
    FOREIGN KEY (`manifest_id`) REFERENCES `manifest`(`manifest_id`) ON DELETE CASCADE,
    FOREIGN KEY (`user_id`) REFERENCES `user`(`user_id`) ON DELETE CASCADE,
    PRIMARY KEY(`manifest_id`, `user_id`)
) ENGINE = INNODB;

DROP TABLE IF EXISTS `log`;
create table `log` (
    `log_id` INTEGER NOT NULL,
    `time` DATETIME NOT NULL,
    `description` VARCHAR NOT NULL,
    `user_id` INTEGER NOT NULL,
    `manifest_id` INTEGER NOT NULL,
    FOREIGN KEY (`manifest_id`) REFERENCES `manifest`(`manifest_id`) ON DELETE CASCADE,
    PRIMARY KEY(`log_id`)
) ENGINE = INNODB;

DROP TABLE IF EXISTS `password`;
create table `password` (
    `salt` VARCHAR NOT NULL,
    `hashed_password` VARCHAR NOT NULL,
    `user_id` INTEGER NOT NULL,
    FOREIGN KEY (`user_id`) REFERENCES `user`(`user_id`) ON DELETE CASCADE,
    PRIMARY KEY(`user_id`)
) ENGINE = INNODB;
