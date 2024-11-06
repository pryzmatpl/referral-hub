<?php

use Phinx\Migration\AbstractMigration;

class CreateMigrationsTable extends AbstractMigration
{
    /**
     * Do the migration
     */
    public function change(): void
    {
        $this->table('migrations')
            ->addColumn('migration', 'string', ['limit' => 255, 'null' => false, 'collation' => 'utf8mb4_unicode_ci'])
            ->addColumn('batch', 'integer', ['null' => false])
            ->create();
    }

}
