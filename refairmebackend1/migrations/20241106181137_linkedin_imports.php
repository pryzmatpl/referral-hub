<?php

use Phpmig\Migration\Migration;
use Illuminate\Database\Capsule\Manager as Capsule;

class CreateLinkedinImportsTable extends Migration
{
    /**
     * Do the migration
     */
    public function up()
    {
        Capsule::schema()->create('linkedin_imports', function ($table) {
            $table->increments('id');
            $table->string('firstName', 255)->notNullable();
            $table->string('lastName', 255)->notNullable();
            $table->string('company', 255)->notNullable();
            $table->string('title', 255)->nullable();
            $table->string('email', 255)->nullable();
            $table->string('phone', 255)->nullable();
            $table->string('notes', 255)->nullable();
            $table->string('tags', 255)->nullable();
            $table->string('uidInviter', 255)->nullable();
            $table->timestamp('regdate')->default(Capsule::raw('CURRENT_TIMESTAMP'))->useCurrentOnUpdate();
            $table->timestamp('created_at')->default('0000-00-00 00:00:00');
            $table->timestamp('updated_at')->default('0000-00-00 00:00:00');
            $table->text('skills')->nullable();
        });
    }

    /**
     * Undo the migration
     */
    public function down()
    {
        Capsule::schema()->dropIfExists('linkedin_imports');
    }
}
