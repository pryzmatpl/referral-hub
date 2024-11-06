<?php

use Phpmig\Migration\Migration;
use Illuminate\Database\Capsule\Manager as Capsule;

class CreateOauthScopesTable extends Migration
{
    /**
     * Do the migration
     */
    public function up()
    {
        Capsule::schema()->create('oauth_scopes', function ($table) {
            $table->text('scope')->collate('utf8mb4_unicode_ci');
            $table->boolean('is_default')->nullable();

            // Optionally, you can define a primary key if needed
            // $table->primary('scope'); // Uncomment if `scope` should be a primary key
        });
    }

    /**
     * Undo the migration
     */
    public function down()
    {
        Capsule::schema()->dropIfExists('oauth_scopes');
    }
}
