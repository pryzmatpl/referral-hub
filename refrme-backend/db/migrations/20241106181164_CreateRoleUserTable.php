<?php

use Phinx\Migration\AbstractMigration;

class CreateRoleUserTable extends AbstractMigration
{
    /**
     * Do the migration
     */
    public function change(): void
    {
        // Create the role_user table
        $table = $this->table('role_user', ['id' => false, 'primary_key' => ['id']]);

        $table->addColumn('id', 'biginteger', ['signed' => false, 'identity' => true])
            ->addColumn('role_id', 'biginteger', ['signed' => false])
            ->addColumn('user_id', 'biginteger', ['signed' => false])
            ->addColumn('created_at', 'timestamp', ['null' => true])
            ->addColumn('updated_at', 'timestamp', ['null' => true])

            // Define indexes
            ->addIndex(['role_id'], ['name' => 'role_user_role_id_index'])
            ->addIndex(['user_id'], ['name' => 'role_user_user_id_index'])

            // Define foreign keys
            ->addForeignKey('role_id', 'roles', 'id', [
                'delete' => 'CASCADE',
                'update' => 'NO_ACTION',
                'constraint' => 'role_user_role_id_foreign'
            ])
            ->addForeignKey('user_id', 'users', 'id', [
                'delete' => 'CASCADE',
                'update' => 'NO_ACTION',
                'constraint' => 'role_user_user_id_foreign'
            ])

            ->create();
    }
}

