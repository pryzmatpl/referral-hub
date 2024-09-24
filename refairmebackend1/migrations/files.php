<?php

use Phpmig\Migration\Migration;
use Illuminate\Database\Capsule\Manager as Capsule;

class CreateFilesTable extends Migration
{
    /**
     * Do the migration
     */
    public function up()
    {
        Capsule::schema()->create('files', function ($table) {
            $table->increments('id');
            $table->string('filename', 255)->collation('utf8mb4_unicode_ci')->notNullable();
            $table->string('title', 100)->collation('utf8mb4_unicode_ci')->notNullable();
            $table->binary('hash')->nullable();
            $table->string('regdate', 255)->collation('utf8mb4_unicode_ci')->nullable();
        });
    }

    /**
     * Undo the migration
     */
    public function down()
    {
        Capsule::schema()->dropIfExists('files');
    }
}
