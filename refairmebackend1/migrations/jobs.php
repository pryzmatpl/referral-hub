<?php

use Phpmig\Migration\Migration;

class CreateJobsTable extends Migration
{
    public function up()
    {
        $sql = "CREATE TABLE `jobs` (
            `id` int(11) NOT NULL AUTO_INCREMENT,
            `title` text,
            `exp` text,
            `fund` text,
            `relocation` int(11) DEFAULT NULL,
            `remote` int(11) DEFAULT NULL,
            `regdate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            `keywords` varchar(255) DEFAULT NULL,
            `location` varchar(255) DEFAULT NULL,
            `description` text,
            `posterId` varchar(255) DEFAULT NULL,
            `bounty` float DEFAULT NULL,
            `hash` blob,
            `travelPercentage` int(11) DEFAULT NULL,
            `remotePercentage` int(11) DEFAULT NULL,
            `relocationPackage` int(11) DEFAULT NULL,
            `projectId` varchar(255) DEFAULT NULL,
            `other` text,
            `created_at` timestamp NULL DEFAULT NULL,
            `updated_at` timestamp NULL DEFAULT NULL,
            `currency` varchar(50) DEFAULT NULL,
            `companyId` int(11) DEFAULT NULL,
            `contractType` varchar(255) DEFAULT NULL,
            PRIMARY KEY (`id`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8;";

        $container = $this->getContainer();
        $container['db']->exec($sql);
    }

    public function down()
    {
        $sql = "DROP TABLE IF EXISTS `jobs`;";

        $container = $this->getContainer();
        $container['db']->exec($sql);
    }
}