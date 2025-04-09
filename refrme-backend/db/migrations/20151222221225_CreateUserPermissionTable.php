<?php

use Phinx\Migration\AbstractMigration;

class CreateUserPermissionTable extends AbstractMigration
{
    /**
     * Change Method.
     */
    public function change(): void
    {
        $table = $this->table('users_permission');
        $table->addColumn('group_id', 'integer')
            ->addColumn('page', 'string')
            ->addColumn('action', 'string')
            ->addTimestamps()
            ->create();
    }
}
