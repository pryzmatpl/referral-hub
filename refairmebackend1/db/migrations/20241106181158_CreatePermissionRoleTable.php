<?php

use Phinx\Migration\AbstractMigration;

class CreatePermissionRoleTable extends AbstractMigration
{
    /**
     * Do the migration
     */
    public function change(): void
    {
        $this->table('permission_role')
            ->addColumn('permission_id', 'integer', ['limit' => 10, 'signed' => false])
            ->addColumn('role_id', 'integer', ['limit' => 10, 'signed' => false])
            ->addColumn('created_at', 'timestamp', ['null' => true])
            ->addColumn('updated_at', 'timestamp', ['null' => true])
            ->addIndex(['permission_id'], ['name' => 'permission_role_permission_id_index'])
            ->addIndex(['role_id'], ['name' => 'permission_role_role_id_index'])
            ->addForeignKey('permission_id', 'permissions', 'id', ['delete' => 'CASCADE'])
            ->addForeignKey('role_id', 'roles', 'id', ['delete' => 'CASCADE'])
            ->create();
    }

}
