<?php

use Phpmig\Migration\Migration;
use Illuminate\Database\Capsule\Manager as Capsule;

class CreateSignoffsTable extends Migration
{
    /**
     * Do the migration
     */
    public function up()
    {
        Capsule::schema()->create('signoffs', function ($table) {
            $table->increments('id')->unsigned();
            $table->string('name', 191)->collation('utf8mb4_unicode_ci')->notNullable();
            $table->string('referrer_id', 191)->collation('utf8mb4_unicode_ci')->nullable();
            $table->string('referred_id', 191)->collation('utf8mb4_unicode_ci')->nullable();
            $table->binary('reviewers_hash')->nullable();
            $table->text('statehash')->collation('utf8mb4_unicode_ci')->nullable();
            $table->string('token', 191)->collation('utf8mb4_unicode_ci')->notNullable();
            $table->string('signup_ip_address', 45)->collation('utf8mb4_unicode_ci')->nullable();
            $table->string('signup_confirmation_ip_address', 45)->collation('utf8mb4_unicode_ci')->nullable();
            $table->string('signup_sm_ip_address', 45)->collation('utf8mb4_unicode_ci')->nullable();
            $table->string('admin_ip_address', 45)->collation('utf8mb4_unicode_ci')->nullable();
            $table->string('updated_ip_address', 45)->collation('utf8mb4_unicode_ci')->nullable();
            $table->string('deleted_ip_address', 45)->collation('utf8mb4_unicode_ci')->nullable();
            $table->timestamp('created_at')->nullable();
            $table->timestamp('updated_at')->nullable();
            $table->timestamp('deleted_at')->nullable();
            $table->integer('jobid')->nullable();
            $table->text('cvfile')->collation('utf8mb4_unicode_ci')->nullable();
        });
    }

    /**
     * Undo the migration
     */
    public function down()
    {
        Capsule::schema()->dropIfExists('signoffs');
    }
}
