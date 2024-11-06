<?php

use Phinx\Migration\AbstractMigration;

class CreateActivationsTable extends AbstractMigration
{
    public function change(): void
    {
        $table = $this->table('activations', ['id' => false, 'primary_key' => 'id']);

        $table->addColumn('id', 'integer', ['signed' => false, 'identity' => true])
            ->addColumn('user_id', 'integer', ['signed' => false])
            ->addColumn('token', 'string', ['limit' => 191, 'collation' => 'utf8mb4_unicode_ci'])
            ->addColumn('ip_address', 'string', ['limit' => 45, 'collation' => 'utf8mb4_unicode_ci'])
            ->addColumn('created_at', 'timestamp', ['null' => true, 'default' => 'CURRENT_TIMESTAMP'])
            ->addColumn('updated_at', 'timestamp', ['null' => true, 'default' => 'CURRENT_TIMESTAMP'])
            ->addIndex(['user_id'], ['name' => 'activations_user_id_index'])
            ->addForeignKey('user_id', 'users', 'id', ['delete' => 'CASCADE', 'update' => 'NO_ACTION'])
            ->create();
    }

}
