<?php

use Phpmig\Migration\Migration;
use Illuminate\Database\Capsule\Manager as Capsule;

class CreateThemesTable extends Migration
{
    /**
     * Do the migration
     */
    public function up()
    {
        Capsule::schema()->create('themes', function ($table) {
            $table->increments('id')->unsigned();
            $table->string('name', 191)->collate('utf8mb4_unicode_ci')->unique();
            $table->string('link', 191)->collate('utf8mb4_unicode_ci')->unique();
            $table->string('notes', 191)->collate('utf8mb4_unicode_ci')->nullable();
            $table->boolean('status')->default(1);
            $table->integer('taggable_id')->unsigned();
            $table->string('taggable_type', 191)->collate('utf8mb4_unicode_ci');
            $table->timestamps();
            $table->softDeletes();

            // Indexes
            $table->index(['taggable_id', 'taggable_type'], 'themes_taggable_id_taggable_type_index');
            $table->index('id', 'themes_id_index');
        });
    }

    /**
     * Undo the migration
     */
    public function down()
    {
        Capsule::schema()->dropIfExists('themes');
    }
}
