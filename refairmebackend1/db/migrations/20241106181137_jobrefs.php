<?php

use Phinx\Migration\AbstractMigration;

class CreateJobrefsTable extends AbstractMigration
{
    public function change(): void
    {
        $table = $this->table('jobrefs');

        $table->addColumn('referred_id', 'string', ['limit' => 255, 'null' => true])
            ->addColumn('location_id', 'string', ['limit' => 255, 'null' => true])
            ->addColumn('name', 'string', ['limit' => 255, 'null' => true])
            ->addColumn('keywords', 'string', ['limit' => 255, 'null' => true])
            ->addColumn('regdate', 'timestamp', ['default' => 'CURRENT_TIMESTAMP', 'update' => 'CURRENT_TIMESTAMP'])
            ->addColumn('hash', 'binary', ['null' => true])
            ->addColumn('state', 'string', ['limit' => 255, 'null' => true])
            ->addColumn('jobid', 'integer', ['null' => true])
            ->addColumn('referrer_id', 'string', ['limit' => 255, 'null' => true])
            ->addColumn('created_at', 'text', ['null' => true])
            ->addColumn('updated_at', 'text', ['null' => true])
            ->addColumn('interview_begin_hour', 'text', ['null' => true])
            ->addColumn('interview_end_hour', 'text', ['null' => true])
            ->addColumn('interview_date', 'text', ['null' => true])
            ->create();
    }

    public function down(): void
    {
        $this->table('jobrefs')->drop()->save();
    }
}

