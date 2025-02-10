<?php

use Phinx\Migration\AbstractMigration;

class CreateFilesTable extends AbstractMigration
{
    public function change(): void
    {
        $table = $this->table('files');

        $table->addColumn('filename', 'string', ['limit' => 255, 'collation' => 'utf8mb4_unicode_ci', 'null' => false])
            ->addColumn('title', 'string', ['limit' => 100, 'collation' => 'utf8mb4_unicode_ci', 'null' => false])
            ->addColumn('hash', 'binary', ['null' => true])
            ->addColumn('regdate', 'string', ['limit' => 255, 'collation' => 'utf8mb4_unicode_ci', 'null' => true])
            ->create();
    }

}
