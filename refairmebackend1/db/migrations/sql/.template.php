<?= "<?php";?>


use Phinx\Migration\AbstractMigration;


class <?= $className ?> extends AbstractMigration
{
    /**
     * Do the migration
     */
    public function change(): void
    {
        Capsule::schema()->create('<?= strtolower($className) ?>', function($table)
        {
            $table->timestamps();
        });

    }

    /**
     * Undo the migration
     */
    public function down()
    {
        Capsule::schema()->drop('<?= strtolower($className) ?>');
    }
}