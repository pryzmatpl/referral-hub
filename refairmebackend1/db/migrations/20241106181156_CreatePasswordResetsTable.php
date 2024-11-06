<?php

use Phinx\Migration\AbstractMigration;

class CreatePasswordResetsTable extends AbstractMigration
{
    /**
     * Do the migration
     */
    public function change(): void
    {
        $this->table('password_resets')
            ->addColumn('email', 'string', ['limit' => 191])
            ->addColumn('token', 'string', ['limit' => 191])
            ->addColumn('created_at', 'timestamp', ['null' => true])
            ->addPrimaryKey('id')
            ->addIndex('email')
            ->addIndex('token')
            ->create();
    }

}
