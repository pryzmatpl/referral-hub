<?php

use Phinx\Migration\AbstractMigration;

class CreatePermissionUserTable extends AbstractMigration
{
    /**
     * Do the migration
     */
    public function change(): void
    {
        $this->table('permission_user')
            ->addColumn('permission_id', 'integer')
            ->addColumn('user_id', 'integer')
            ->addColumn('created_at', 'timestamp', ['null' => true])
            ->addColumn('updated_at', 'timestamp', ['null' => true])
            ->addForeignKey('permission_id', 'permissions', 'id', ['delete'=> 'CASCADE'])
            ->addForeignKey('user_id', 'users', 'id', ['delete'=> 'CASCADE'])
            ->create();
    }

    /**
     * Undo the migration
     */
    public function down(): void
    {
        $this->table('permission_user')->drop()->save();
    }
}
