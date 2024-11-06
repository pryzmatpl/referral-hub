<?php

use Phpmig\Migration\Migration;
use Illuminate\Database\Capsule\Manager as Capsule;

class CreateKeywordsTable extends Migration
{
    /**
     * Do the migration
     */
    public function up()
    {
        Capsule::schema()->create('keywords', function ($table) {
            $table->increments('id');
            $table->integer('uid')->nullable();
            $table->integer('termid')->nullable();
            $table->string('keyone', 255)->nullable();
            $table->string('keytwo', 255)->nullable();
            $table->string('keythree', 255)->nullable();
            $table->string('searchterm', 255)->nullable();
            $table->timestamp('regdate')->default(Capsule::raw('CURRENT_TIMESTAMP'))->useCurrentOnUpdate();
            $table->integer('cnt')->nullable();
        });
    }

    /**
     * Undo the migration
     */
    public function down()
    {
        Capsule::schema()->dropIfExists('keywords');
    }
}
