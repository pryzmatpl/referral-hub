<?php

use Phinx\Migration\AbstractMigration;
use Illuminate\Database\Capsule\Manager as Capsule;

class CreatePermissionUserTable extends AbstractMigration
{
    /**
     * Do the migration
     */
    public function change(): void
    {
        Capsule::schema()->create('permission_user', function ($table) {
            $table->increments('id')->unsigned();
            $table->unsignedInteger('permission_id');
            $table->unsignedInteger('user_id');
            $table->timestamp('created_at')->nullable();
            $table->timestamp('updated_at')->nullable();

            $table->foreign('permission_id')
                ->references('id')->on('permissions')
                ->onDelete('cascade');

            $table->foreign('user_id')
                ->references('id')->on('users')
                ->onDelete('cascade');
        });
    }

    /**
     * Undo the migration
     */
    public function down()
    {
        Capsule::schema()->dropIfExists('permission_user');
    }
}
