<?php

use Phinx\Migration\AbstractMigration;
use Illuminate\Database\Capsule\Manager as Capsule;

class CreateMigrationsTable extends AbstractMigration
{
    /**
     * Do the migration
     */
    public function change(): void
    {
        Capsule::schema()->create('migrations', function ($table) {
            $table->increments('id')->unsigned();
            $table->string('migration', 255)->collation('utf8mb4_unicode_ci')->notNullable();
            $table->integer('batch')->notNullable();
        });
    }

    /**
     * Undo the migration
     */
    public function down()
    {
        Capsule::schema()->dropIfExists('migrations');
    }
}
