<?php

use Phpmig\Migration\Migration;
use Illuminate\Database\Capsule\Manager as Capsule;

class CreateUsersTable extends Migration
{
    /**
     * Do the migration
     */
    public function up()
    {
        Capsule::schema()->create('users', function ($table) {
            $table->increments('id')->unsigned();
            $table->string('first_name', 191)->collation('utf8mb4_unicode_ci')->nullable();
            $table->string('last_name', 191)->collation('utf8mb4_unicode_ci')->nullable();
            $table->string('password', 191)->collation('utf8mb4_unicode_ci')->notNullable();
            $table->string('remember_token', 100)->collation('utf8mb4_unicode_ci')->nullable();
            $table->tinyInteger('activated')->default(0)->notNullable();
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
            $table->text('last_login')->collation('utf8mb4_unicode_ci')->nullable();
            $table->text('activ_code')->collation('utf8mb4_unicode_ci')->nullable();
            $table->integer('group_id')->nullable();
            $table->integer('activ')->nullable();
            $table->tinyInteger('cvadded')->nullable();
            $table->string('name', 191)->collation('utf8mb4_unicode_ci')->notNullable();
            $table->string('email', 191)->collation('utf8mb4_unicode_ci')->nullable();
            $table->unique('email', 'users_email_unique');
        });
    }

    /**
     * Undo the migration
     */
    public function down()
    {
        Capsule::schema()->dropIfExists('users');
    }
}
