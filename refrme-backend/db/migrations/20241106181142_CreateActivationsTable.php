<?php

use Phinx\Migration\AbstractMigration;

class CreateActivationsTable extends AbstractMigration
{
    public function change(): void
    {
        $table = $this->table('activations');

        $table->addColumn('user_id', 'integer')
            ->addColumn('token', 'string', ['limit' => 191, 'collation' => 'utf8mb4_unicode_ci'])
            ->addColumn('ip_address', 'string', ['limit' => 45, 'collation' => 'utf8mb4_unicode_ci'])
            ->addColumn('created_at', 'timestamp', ['null' => true, 'default' => 'CURRENT_TIMESTAMP'])
            ->addColumn('updated_at', 'timestamp', ['null' => true, 'default' => 'CURRENT_TIMESTAMP'])
            ->create();
    }

}
