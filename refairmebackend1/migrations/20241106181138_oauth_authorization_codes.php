<?php

use Phpmig\Migration\Migration;
use Illuminate\Database\Capsule\Manager as Capsule;

class CreateOauthAuthorizationCodesTable extends Migration
{
    /**
     * Do the migration
     */
    public function up()
    {
        Capsule::schema()->create('oauth_authorization_codes', function ($table) {
            $table->string('authorization_code', 40)->collation('utf8mb4_unicode_ci')->notNullable()->primary();
            $table->string('client_id', 80)->collation('utf8mb4_unicode_ci')->notNullable();
            $table->string('user_id', 255)->collation('utf8mb4_unicode_ci')->nullable();
            $table->string('redirect_uri', 2000)->collation('utf8mb4_unicode_ci')->nullable();
            $table->timestamp('expires')->default(Capsule::raw('CURRENT_TIMESTAMP'))->useCurrentOnUpdate();
            $table->string('scope', 2000)->collation('utf8mb4_unicode_ci')->nullable();
        });
    }

    /**
     * Undo the migration
     */
    public function down()
    {
        Capsule::schema()->dropIfExists('oauth_authorization_codes');
    }
}
