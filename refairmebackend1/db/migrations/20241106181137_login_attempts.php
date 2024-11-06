<?php

use Phinx\Migration\AbstractMigration;
use Illuminate\Database\Capsule\Manager as Capsule;

class CreateLoginAttemptsTable extends AbstractMigration
{
    /**
     * Do the migration
     */
    public function change(): void
    {
        Capsule::schema()->create('login_attempts', function ($table) {
            $table->increments('id');
            $table->string('ip_address', 45)->notNullable();
            $table->string('login', 100)->notNullable();
            $table->unsignedInteger('time')->nullable();
        });
    }

    /**
     * Undo the migration
     */
    public function down()
    {
        Capsule::schema()->dropIfExists('login_attempts');
    }
}
