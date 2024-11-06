<?php

use Phinx\Migration\AbstractMigration;
use Illuminate\Database\Capsule\Manager as Capsule;

class CreateJobrefsTable extends AbstractMigration
{
    /**
     * Do the migration
     */
    public function change(): void
    {
        Capsule::schema()->create('jobrefs', function ($table) {
            $table->increments('id');
            $table->string('referred_id', 255)->nullable();
            $table->string('location_id', 255)->nullable();
            $table->string('name', 255)->nullable();
            $table->string('keywords', 255)->nullable();
            $table->timestamp('regdate')->default(Capsule::raw('CURRENT_TIMESTAMP'))->useCurrentOnUpdate();
            $table->binary('hash')->nullable();
            $table->string('state', 255)->nullable();
            $table->integer('jobid')->nullable();
            $table->string('referrer_id', 255)->nullable();
            $table->text('created_at')->nullable();
            $table->text('updated_at')->nullable();
            $table->text('interview_begin_hour')->nullable();
            $table->text('interview_end_hour')->nullable();
            $table->text('interview_date')->nullable();
        });
    }

    /**
     * Undo the migration
     */
    public function down()
    {
        Capsule::schema()->dropIfExists('jobrefs');
    }
}
