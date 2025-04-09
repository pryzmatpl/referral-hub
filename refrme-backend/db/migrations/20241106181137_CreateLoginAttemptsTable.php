<?php

use Phinx\Migration\AbstractMigration;

class CreateLoginAttemptsTable extends AbstractMigration
{
    /**
     * Do the migration
     */
    public function change(): void
    {
        $this->table('login_attempts')
            ->addColumn('ip_address', 'string', ['limit' => 45])
            ->addColumn('login', 'string', ['limit' => 100])
            ->addColumn('time', 'integer', ['null' => true])
            ->create();
    }
}
