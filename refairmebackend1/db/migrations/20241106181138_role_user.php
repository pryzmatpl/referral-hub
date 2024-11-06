<?php

use Phinx\Migration\AbstractMigration;
use Illuminate\Database\Capsule\Manager as Capsule;

class CreateRoleUserTable extends AbstractMigration
{
    /**
     * Do the migration
     */
    public function change(): void
    {
        Capsule::schema()->create('role_user', function ($table) {
            $table->increments('id')->unsigned();
            $table->unsignedInteger('role_id');
            $table->unsignedInteger('user_id');
            $table->timestamp('created_at')->nullable();
            $table->timestamp('updated_at')->nullable();

            // Foreign key constraints
            $table->foreign('role_id', 'role_user_role_id_foreign')->references('id')->on('roles')->onDelete('cascade');
            $table->foreign('user_id', 'role_user_user_id_foreign')->references('id')->on('users')->onDelete('cascade');

            // Indexes
            $table->index('role_id', 'role_user_role_id_index');
            $table->index('user_id', 'role_user_user_id_index');
        });
    }

    /**
     * Undo the migration
     */
    public function down()
    {
        Capsule::schema()->dropIfExists('role_user');
    }
}
