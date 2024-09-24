<?php

use Phpmig\Migration\Migration;
use Illuminate\Database\Capsule\Manager as Capsule;

class CreateLocationsTable extends Migration
{
    /**
     * Do the migration
     */
    public function up()
    {
        Capsule::schema()->create('locations', function ($table) {
            $table->increments('id');
            $table->mediumBlob('jobref_hashes')->nullable();
            $table->string('name', 255)->notNullable();
            $table->string('city', 255)->notNullable();
            $table->string('country', 255)->notNullable();
            $table->string('address', 255)->notNullable();
            $table->string('zip', 255)->notNullable();
            $table->text('lat')->nullable();
            $table->text('lng')->nullable();
            $table->binary('hash')->nullable();
            $table->timestamp('regdate')->default(Capsule::raw('CURRENT_TIMESTAMP'))->useCurrentOnUpdate();
            $table->string('userid', 255)->nullable();
            $table->binary('description')->nullable();
            $table->text('updated_at')->nullable();
            $table->text('created_at')->nullable();
            $table->text('currency')->nullable();
        });
    }

    /**
     * Undo the migration
     */
    public function down()
    {
        Capsule::schema()->dropIfExists('locations');
    }
}
