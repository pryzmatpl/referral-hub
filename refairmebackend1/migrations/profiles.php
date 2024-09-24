<?php

use Phpmig\Migration\Migration;
use Illuminate\Database\Capsule\Manager as Capsule;

class CreateProfilesTable extends Migration
{
    /**
     * Do the migration
     */
    public function up()
    {
        Capsule::schema()->create('profiles', function ($table) {
            $table->increments('id')->unsigned();
            $table->unsignedInteger('user_id');
            $table->unsignedInteger('theme_id')->default(1);
            $table->string('location', 191)->nullable();
            $table->text('bio')->nullable();
            $table->string('twitter_username', 191)->nullable();
            $table->string('github_username', 191)->nullable();
            $table->string('avatar', 191)->nullable();
            $table->tinyInteger('avatar_status')->default(0);
            $table->timestamp('created_at')->nullable();
            $table->timestamp('updated_at')->nullable();

            // Foreign key constraints
            $table->foreign('theme_id', 'profiles_theme_id_foreign')->references('id')->on('themes');
            $table->foreign('user_id', 'profiles_user_id_foreign')->references('id')->on('users')->onDelete('cascade');

            // Indexes
            $table->index('theme_id', 'profiles_theme_id_foreign');
            $table->index('user_id', 'profiles_user_id_index');
        });
    }

    /**
     * Undo the migration
     */
    public function down()
    {
        Capsule::schema()->dropIfExists('profiles');
    }
}
