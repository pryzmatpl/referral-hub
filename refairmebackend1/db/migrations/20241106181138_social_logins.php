<?php

use Phinx\Migration\AbstractMigration;
use Illuminate\Database\Capsule\Manager as Capsule;

class CreateSocialLoginsTable extends AbstractMigration
{
    /**
     * Do the migration
     */
    public function change(): void
    {
        Capsule::schema()->create('social_logins', function ($table) {
            $table->increments('id')->unsigned();
            $table->integer('user_id')->unsigned();
            $table->string('provider', 50)->collation('utf8mb4_unicode_ci')->notNullable();
            $table->text('social_id')->collation('utf8mb4_unicode_ci')->notNullable();
            $table->timestamp('created_at')->nullable();
            $table->timestamp('updated_at')->nullable();

            // Indexes
            $table->index('user_id', 'social_logins_user_id_index');

            // Foreign key constraint
            $table->foreign('user_id', 'social_logins_user_id_foreign')
                ->references('id')->on('users')
                ->onDelete('cascade');
        });
    }

    /**
     * Undo the migration
     */
    public function down()
    {
        Capsule::schema()->dropIfExists('social_logins');
    }
}
