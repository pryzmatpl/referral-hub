<?php

use Phinx\Migration\AbstractMigration;

class CreatePerksTable extends AbstractMigration
{
    /**
     * Do the migration
     */
    public function change(): void
    {
        $this->table('perks')
            ->addColumn('jobid', 'biginteger', ['signed' => false])
            ->addColumn('name', 'string', ['limit' => 255, 'null' => true])
            ->addColumn('uid', 'integer')
            ->addColumn('agreed_employer', 'integer', ['null' => true])
            ->addColumn('agreed_employee', 'integer', ['null' => true])
            ->addColumn('hash', 'binary')
            ->addColumn('regdate', 'timestamp', ['default' => 'CURRENT_TIMESTAMP', 'update' => 'CURRENT_TIMESTAMP'])
            ->addColumn('target', 'string', ['limit' => 255, 'null' => true])
            ->addColumn('agreed_referee', 'integer', ['null' => true])
            ->create();
    }

}
