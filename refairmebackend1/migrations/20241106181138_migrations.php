<?php

use Phpmig\Migration\Migration;
use Illuminate\Database\Capsule\Manager as Capsule;

class CreateMigrationsTable extends Migration
{
    /**
     * Do the migration
     */
    public function up()
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
