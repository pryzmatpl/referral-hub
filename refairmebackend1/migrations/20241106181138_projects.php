<?php

use Phpmig\Migration\Migration;

class CreateProjectsTable extends Migration
{
    public function up()
    {
        $sql = "CREATE TABLE `projects` (
            `id` int(11) NOT NULL AUTO_INCREMENT,
            `description` text,
            `posterId` varchar(255) DEFAULT NULL,
            `staff` int(11) DEFAULT NULL,
            `stack` text,
            `breakdown` text,
            `companyId` text,
            `created_at` timestamp NULL DEFAULT NULL,
            `updated_at` timestamp NULL DEFAULT NULL,
            `currency` varchar(50) DEFAULT NULL,
            `methodology` text,
            `stage` text,
            `name` text,
            `contractType` varchar(255) DEFAULT NULL,
            `logo` text,
            `projectType` varchar(255) DEFAULT NULL,
            `workload` text,
            `requiredSkills` text,
            `perks` text,
            PRIMARY KEY (`id`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8;";

        $container = $this->getContainer();
        $container['db']->exec($sql);
    }

    public function down()
    {
        $sql = "DROP TABLE IF EXISTS `projects`;";

        $container = $this->getContainer();
        $container['db']->exec($sql);
    }
}