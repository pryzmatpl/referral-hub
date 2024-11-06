<?php

use Phinx\Migration\AbstractMigration;

class CreateJobdescsTable extends AbstractMigration
{
    public function change(): void
    {
        $sql = "CREATE TABLE `jobdescs` (
            `id` int(11) NOT NULL AUTO_INCREMENT,
            `jobtitle` varchar(255) DEFAULT NULL,
            `required_exp` text,
            `required_fund` text,
            `required_relocation` int(11) DEFAULT NULL,
            `required_remote` int(11) DEFAULT NULL,
            `regdate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            `keywords` varchar(255) DEFAULT NULL,
            `location` varchar(255) DEFAULT NULL,
            `description` text,
            `poster_id` varchar(255) DEFAULT NULL,
            `bounty` float DEFAULT NULL,
            `hash` blob,
            `musthave` text,
            `nicetohave` text,
            `essentials` text,
            `specs` text,
            `other` text,
            `created_at` timestamp NULL DEFAULT NULL,
            `updated_at` timestamp NULL DEFAULT NULL,
            `currency` varchar(50) DEFAULT NULL,
            PRIMARY KEY (`id`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8;";

        $container = $this->getContainer();
        $container['db']->exec($sql);
    }

    public function down()
    {
        $sql = "DROP TABLE IF EXISTS `jobdescs`;";

        $container = $this->getContainer();
        $container['db']->exec($sql);
    }
}