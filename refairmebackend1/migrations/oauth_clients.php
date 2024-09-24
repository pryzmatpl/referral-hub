<?php

use Phpmig\Migration\Migration;
use Illuminate\Database\Capsule\Manager as Capsule;

class CreateOauthClientsTable extends Migration
{
    /**
     * Do the migration
     */
    public function up()
    {
        Capsule::schema()->create('oauth_clients', function ($table) {
            $table->string('client_id', 80)->collation('utf8mb4_unicode_ci')->notNullable()->primary();
            $table->string('client_secret', 5000)->collation('utf8mb4_unicode_ci')->nullable();
            $table->string('redirect_uri', 2000)->collation('utf8mb4_unicode_ci')->notNullable();
            $table->string('grant_types', 80)->collation('utf8mb4_unicode_ci')->nullable();
            $table->string('scope', 100)->collation('utf8mb4_unicode_ci')->nullable();
            $table->string('user_id', 80)->collation('utf8mb4_unicode_ci')->nullable();
            $table->timestamp('updated_at')->nullable();
            $table->timestamp('created_at')->nullable();
        });
    }

    /**
     * Undo the migration
     */
    public function down()
    {
        Capsule::schema()->dropIfExists('oauth_clients');
    }
}
