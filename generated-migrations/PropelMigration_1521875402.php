<?php

use Propel\Generator\Manager\MigrationManager;

/**
 * Data object containing the SQL and PHP code to migrate the database
 * up to version 1521875402.
 * Generated on 2018-03-24 07:10:02 by root
 */
class PropelMigration_1521875402
{
    public $comment = '';

    public function preUp(MigrationManager $manager)
    {
        // add the pre-migration code here
    }

    public function postUp(MigrationManager $manager)
    {
        // add the post-migration code here
    }

    public function preDown(MigrationManager $manager)
    {
        // add the pre-migration code here
    }

    public function postDown(MigrationManager $manager)
    {
        // add the post-migration code here
    }

    /**
     * Get the SQL statements for the Up migration
     *
     * @return array list of the SQL strings to execute for the Up migration
     *               the keys being the datasources
     */
    public function getUpSQL()
    {
        return array (
  'default' => '
# This is a fix for InnoDB in MySQL >= 4.1.x
# It "suspends judgement" for fkey relationships until are tables are set.
SET FOREIGN_KEY_CHECKS = 0;

DROP TABLE IF EXISTS `dg`;

CREATE TABLE `sheet_config`
(
    `id` BIGINT NOT NULL AUTO_INCREMENT,
    `field` VARCHAR(255) NOT NULL,
    `type` VARCHAR(10) DEFAULT \'string\',
    `default_value` VARCHAR(255),
    `column` VARCHAR(3) DEFAULT \'A\',
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
',
);
    }

    /**
     * Get the SQL statements for the Down migration
     *
     * @return array list of the SQL strings to execute for the Down migration
     *               the keys being the datasources
     */
    public function getDownSQL()
    {
        return array (
  'default' => '
# This is a fix for InnoDB in MySQL >= 4.1.x
# It "suspends judgement" for fkey relationships until are tables are set.
SET FOREIGN_KEY_CHECKS = 0;

DROP TABLE IF EXISTS `sheet_config`;

CREATE TABLE `dg`
(
    `id` BIGINT NOT NULL AUTO_INCREMENT,
    `guild_id` BIGINT NOT NULL,
    PRIMARY KEY (`id`),
    INDEX `guild_id` (`guild_id`)
) ENGINE=InnoDB;

# This restores the fkey checks, after having unset them earlier
SET FOREIGN_KEY_CHECKS = 1;
',
);
    }

}