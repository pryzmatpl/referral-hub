<?php

use Phinx\Migration\AbstractMigration;

class CreateRolesTable extends AbstractMigration
{
    /**
     * Do the migration
     */
    public function change(): void
    {
        // Create the roles table
        $table = $this->table('roles', ['id' => false, 'primary_key' => ['id']]);

        $table->addColumn('id', 'biginteger', ['signed' => false, 'identity' => true])
            ->addColumn('name', 'string', [
            'limit' => 191,
            'collation' => 'utf8mb4_unicode_ci',
            'null' => false
        ])
            ->addColumn('slug', 'string', [
                'limit' => 191,
                'collation' => 'utf8mb4_unicode_ci',
                'null' => false
            ])
            ->addColumn('description', 'string', [
                'limit' => 191,
                'collation' => 'utf8mb4_unicode_ci',
                'null' => true
            ])
            ->addColumn('level', 'integer', [
                'default' => 1
            ])
            ->addColumn('created_at', 'timestamp', [
                'default' => 'CURRENT_TIMESTAMP',
                'null' => true
            ])
            ->addColumn('updated_at', 'timestamp', [
                'null' => true,
                'update' => 'CURRENT_TIMESTAMP'
            ])
            ->addIndex(['slug'], [
                'unique' => true,
                'name' => 'roles_slug_unique'
            ])
            ->create();
    }
}

