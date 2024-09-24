<?php
use Phpmig\Migration\Migration;

class CreateApikeysTable extends Migration
{
    public function up()
    {
        $sql = "CREATE TABLE `apikeys` (
                `id` int(11) NOT NULL AUTO_INCREMENT,
                `uid` int(11) DEFAULT NULL,
                `securekey` varchar(255) DEFAULT NULL,
                `regdate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                `vendorid` int(11) DEFAULT NULL,
                PRIMARY KEY (`id`)
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8;";

        $container = $this->getContainer();
        $container['db']->exec($sql);
    }

    public function down()
    {
        $sql = "DROP TABLE IF EXISTS `apikeys`;";

        $container = $this->getContainer();
        $container['db']->exec($sql);
    }
}
