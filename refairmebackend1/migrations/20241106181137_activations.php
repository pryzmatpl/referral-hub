<?php

use Phinx\Migration\AbstractMigration;

class CreateActivationsTable extends AbstractMigration
{
    public function change(): void
    {
        $sql = "CREATE TABLE `activations` (
            `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
            `user_id` int(10) unsigned NOT NULL,
            `token` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
            `ip_address` varchar(45) COLLATE utf8mb4_unicode_ci NOT NULL,
            `created_at` timestamp NULL DEFAULT NULL,
            `updated_at` timestamp NULL DEFAULT NULL,
            PRIMARY KEY (`id`),
            KEY `activations_user_id_index` (`user_id`),
            CONSTRAINT `activations_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;";

        $container = $this->getContainer();
        $container['db']->exec($sql);
    }

    public function down()
    {
        $sql = "DROP TABLE IF EXISTS `activations`;";

        $container = $this->getContainer();
        $container['db']->exec($sql);
    }
}