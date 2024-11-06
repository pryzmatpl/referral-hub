<?php

use Phinx\Migration\AbstractMigration;
use Illuminate\Database\Capsule\Manager as Capsule;

class CreateUserWeightsTable extends AbstractMigration
{
    /**
     * Do the migration
     */
    public function change(): void
    {
        Capsule::schema()->create('userweights', function ($table) {
            $table->increments('id')->unsigned();
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
            $table->integer('userid')->nullable();
            $table->text('keywords')->collation('utf8mb4_unicode_ci')->nullable();
        });
    }

    /**
     * Undo the migration
     */
    public function down()
    {
        Capsule::schema()->dropIfExists('userweights');
    }
}
