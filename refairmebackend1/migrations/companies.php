<?php

use Phpmig\Migration\Migration;
use Illuminate\Database\Capsule\Manager as Capsule;

class CreateCompaniesTable extends Migration
{
    /**
     * Do the migration
     */
    public function up()
    {
        Capsule::schema()->create('companies', function ($table) {
            $table->increments('id');
            $table->text('name')->nullable();
            $table->text('description')->nullable();
            $table->text('created_at')->nullable();
            $table->text('updated_at')->nullable();
            $table->text('currency')->nullable();
            $table->text('posterId')->nullable();
        });
    }

    /**
     * Undo the migration
     */
    public function down()
    {
        Capsule::schema()->dropIfExists('companies');
    }
}
