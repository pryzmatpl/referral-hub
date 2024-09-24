<?php

use Phpmig\Migration\Migration;
use Illuminate\Database\Capsule\Manager as Capsule;

class CreatePasswordResetsTable extends Migration
{
    /**
     * Do the migration
     */
    public function up()
    {
        Capsule::schema()->create('password_resets', function ($table) {
            $table->increments('id');
            $table->string('email', 191);
            $table->string('token', 191);
            $table->timestamp('created_at')->nullable();

            $table->primary('id');
            $table->index('email');
            $table->index('token');
        });
    }

    /**
     * Undo the migration
     */
    public function down()
    {
        Capsule::schema()->dropIfExists('password_resets');
    }
}
