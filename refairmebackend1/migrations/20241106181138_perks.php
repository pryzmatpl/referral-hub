<?php

use Phinx\Migration\AbstractMigration;
use Illuminate\Database\Capsule\Manager as Capsule;

class CreatePerksTable extends AbstractMigration
{
    /**
     * Do the migration
     */
    public function change(): void
    {
        Capsule::schema()->create('perks', function ($table) {
            $table->increments('id');
            $table->integer('jobid');
            $table->string('name', 255)->nullable();
            $table->integer('uid');
            $table->integer('agreed_employer')->nullable();
            $table->integer('agreed_employee')->nullable();
            $table->binary('hash');
            $table->timestamp('regdate')->default(DB::raw('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'));
            $table->string('target', 255)->nullable();
            $table->integer('agreed_referee')->nullable();

            $table->primary('id');
        });
    }

    /**
     * Undo the migration
     */
    public function down()
    {
        Capsule::schema()->dropIfExists('perks');
    }
}
