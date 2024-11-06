<?php

use Phinx\Migration\AbstractMigration;

class CreateApikeysTable extends AbstractMigration
{
    public function change(): void
    {
        $table = $this->table('apikeys');

        $table->addColumn('uid', 'integer', ['null' => true])
            ->addColumn('securekey', 'string', ['limit' => 255, 'null' => true])
            ->addColumn('regdate', 'timestamp', ['default' => 'CURRENT_TIMESTAMP', 'update' => 'CURRENT_TIMESTAMP'])
            ->addColumn('vendorid', 'integer', ['null' => true])
            ->create();
    }

    public function down(): void
    {
        $this->table('apikeys')->drop()->save();
    }
}

