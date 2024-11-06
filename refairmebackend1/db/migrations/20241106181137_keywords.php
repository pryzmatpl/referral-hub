<?php

use Phinx\Migration\AbstractMigration;


class CreateKeywordsTable extends AbstractMigration
{
    /**
     * Do the migration
     */
    public function change(): void
    {
        $this->table('keywords')
            ->addColumn('uid', 'integer', ['null' => true])
            ->addColumn('termid', 'integer', ['null' => true])
            ->addColumn('keyone', 'string', ['limit' => 255, 'null' => true])
            ->addColumn('keytwo', 'string', ['limit' => 255, 'null' => true])
            ->addColumn('keythree', 'string', ['limit' => 255, 'null' => true])
            ->addColumn('searchterm', 'string', ['limit' => 255, 'null' => true])
            ->addColumn('regdate', 'timestamp', ['default' => Capsule::raw('CURRENT_TIMESTAMP'), 'update' => Capsule::raw('CURRENT_TIMESTAMP')])
            ->addColumn('cnt', 'integer', ['null' => true])
            ->create();
    }

    /**
     * Undo the migration
     */
    public function down(): void
    {
        $this->table('keywords')->drop()->save();
    }
}

