<?php

use Phpmig\Migration\Migration;
use Illuminate\Database\Capsule\Manager as Capsule;

class CreateRolesTable extends Migration
{
    /**
     * Do the migration
     */
    public function up()
    {
        Capsule::schema()->create('roles', function ($table) {
            $table->increments('id')->unsigned();
            $table->string('name', 191)->collation('utf8mb4_unicode_ci')->notNullable();
            $table->string('slug', 191)->collation('utf8mb4_unicode_ci')->notNullable();
            $table->string('description', 191)->collation('utf8mb4_unicode_ci')->nullable();
            $table->integer('level')->default(1);
            $table->timestamp('created_at')->nullable();
            $table->timestamp('updated_at')->nullable();

            // Unique index on slug
            $table->unique('slug', 'roles_slug_unique');
        });
    }

    /**
     * Undo the migration
     */
    public function down()
    {
        Capsule::schema()->dropIfExists('roles');
    }
}
