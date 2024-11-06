<?php

use Phinx\Migration\AbstractMigration;

class CreateAppointmentsTable extends AbstractMigration
{
    public function change(): void
    {
        $table = $this->table('appointments');

        $table->addColumn('candidate_id', 'integer', ['null' => false])
            ->addColumn('recruiter_id', 'integer', ['null' => false])
            ->addColumn('appointment_start', 'datetime', ['null' => false])
            ->addColumn('appointment_end', 'datetime', ['null' => false])
            ->addColumn('status', 'text', ['null' => false, 'collation' => 'utf8mb4_unicode_ci'])
            ->addColumn('created_at', 'timestamp', ['default' => 'CURRENT_TIMESTAMP'])
            ->addColumn('updated_at', 'timestamp', ['default' => 'CURRENT_TIMESTAMP', 'update' => 'CURRENT_TIMESTAMP'])
            ->addColumn('deleted_at', 'timestamp', ['null' => true, 'default' => null])
            ->create();
    }

    public function down(): void
    {
        $this->table('appointments')->drop()->save();
    }
}
