<?php
use Phpmig\Migration\Migration;

class CreateAppointmentsTable extends Migration
{
    public function up()
    {
        $sql = "CREATE TABLE `appointments` (
            `id` int(11) NOT NULL AUTO_INCREMENT,
            `candidate_id` int(11) NOT NULL,
            `recruiter_id` int(11) NOT NULL,
            `appointment_start` datetime NOT NULL,
            `appointment_end` datetime NOT NULL,
            `status` text COLLATE utf8mb4_unicode_ci NOT NULL,
            `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
            `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            `deleted_at` timestamp NULL DEFAULT NULL,
            PRIMARY KEY (`id`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;";

        $container = $this->getContainer();
        $container['db']->exec($sql);
    }

    public function down()
    {
        $sql = "DROP TABLE IF EXISTS `appointments`;";

        $container = $this->getContainer();
        $container['db']->exec($sql);
    }
}
