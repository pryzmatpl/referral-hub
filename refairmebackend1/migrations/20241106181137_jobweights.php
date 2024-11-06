<?php

use Phpmig\Migration\Migration;
use Illuminate\Database\Capsule\Manager as Capsule;

class CreateJobweightsTable extends Migration
{
    /**
     * Do the migration
     */
    public function up()
    {
        Capsule::schema()->create('jobweights', function ($table) {
            $table->increments('id');
            $table->double('aone')->notNullable();
            $table->double('atwo')->notNullable();
            $table->double('athree')->notNullable();
            $table->double('afour')->notNullable();
            $table->double('afive')->notNullable();
            $table->double('asix')->notNullable();
            $table->double('aseven')->notNullable();
            $table->double('aeight')->notNullable();
            $table->double('anine')->notNullable();
            $table->double('aten')->notNullable();
            $table->double('aeleven')->notNullable();
            $table->timestamp('created_at')->nullable();
            $table->timestamp('updated_at')->nullable();
            $table->integer('jobid')->nullable();
            $table->text('keywords')->collation('utf8mb4_unicode_ci')->nullable();
        });
    }

    /**
     * Undo the migration
     */
    public function down()
    {
        Capsule::schema()->dropIfExists('jobweights');
    }
}
