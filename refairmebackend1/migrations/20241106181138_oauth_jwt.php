<?php

use Phinx\Migration\AbstractMigration;
use Illuminate\Database\Capsule\Manager as Capsule;

class CreateOauthJwtTable extends AbstractMigration
{
    /**
     * Do the migration
     */
    public function change(): void
    {
        Capsule::schema()->create('oauth_jwt', function ($table) {
            $table->string('client_id', 80)->collation('utf8mb4_unicode_ci')->notNullable()->primary();
            $table->string('subject', 80)->collation('utf8mb4_unicode_ci')->nullable();
            $table->string('public_key', 2000)->collation('utf8mb4_unicode_ci')->nullable();
        });
    }

    /**
     * Undo the migration
     */
    public function down()
    {
        Capsule::schema()->dropIfExists('oauth_jwt');
    }
}
