
# This is a fix for InnoDB in MySQL >= 4.1.x
# It "suspends judgement" for fkey relationships until are tables are set.
SET FOREIGN_KEY_CHECKS = 0;

-- ---------------------------------------------------------------------
-- guild
-- ---------------------------------------------------------------------

DROP TABLE IF EXISTS `guild`;

CREATE TABLE `guild`
(
    `id` BIGINT NOT NULL,
    `name` VARCHAR(255) NOT NULL,
    `sheet_id` VARCHAR(255),
    `admin_roles` TEXT,
    `member_roles` TEXT,
    `channels` TEXT,
    `active` TINYINT(1) DEFAULT 1 NOT NULL,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB;

-- ---------------------------------------------------------------------
-- announcement
-- ---------------------------------------------------------------------

DROP TABLE IF EXISTS `announcement`;

CREATE TABLE `announcement`
(
    `id` BIGINT NOT NULL AUTO_INCREMENT,
    `message` VARCHAR(255) NOT NULL,
    `guild_id` BIGINT,
    `created_at` DATETIME,
    `broadcasted_at` DATETIME,
    PRIMARY KEY (`id`),
    INDEX `fi_ld_id` (`guild_id`),
    CONSTRAINT `guild_id`
        FOREIGN KEY (`guild_id`)
        REFERENCES `guild` (`id`)
        ON UPDATE SET NULL
        ON DELETE SET NULL
) ENGINE=InnoDB;

-- ---------------------------------------------------------------------
-- sheet_config
-- ---------------------------------------------------------------------

DROP TABLE IF EXISTS `sheet_config`;

CREATE TABLE `sheet_config`
(
    `id` BIGINT NOT NULL AUTO_INCREMENT,
    `field` VARCHAR(255) NOT NULL,
    `type` VARCHAR(10) DEFAULT 'string',
    `default_value` VARCHAR(255),
    `column` VARCHAR(3) DEFAULT 'A',
    `guild_id` BIGINT NOT NULL,
    PRIMARY KEY (`id`),
    INDEX `fi_ld_id_sheet` (`guild_id`),
    CONSTRAINT `guild_id_sheet`
        FOREIGN KEY (`guild_id`)
        REFERENCES `guild` (`id`)
        ON UPDATE CASCADE
        ON DELETE CASCADE
) ENGINE=InnoDB;

# This restores the fkey checks, after having unset them earlier
SET FOREIGN_KEY_CHECKS = 1;
