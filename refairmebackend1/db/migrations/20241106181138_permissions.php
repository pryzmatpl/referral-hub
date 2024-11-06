<?php

use Phinx\Migration\AbstractMigration;
use Illuminate\Database\Capsule\Manager as Capsule;

class CreatePermissionsTable extends AbstractMigration
{
    /**
     * Do the migration
     */
    public function change(): void
    {
        Capsule::schema()->create('permissions', function ($table) {
            $table->increments('id')->unsigned();
            $table->string('name', 191);
            $table->string('slug', 191)->unique();
            $table->string('description', 191)->nullable();
            $table->string('model', 191)->nullable();
            $table->timestamp('created_at')->nullable();
            $table->timestamp('updated_at')->nullable();
        });
    }

    /**
     * Undo the migration
     */
    public function down()
    {
        Capsule::schema()->dropIfExists('permissions');
    }
}
