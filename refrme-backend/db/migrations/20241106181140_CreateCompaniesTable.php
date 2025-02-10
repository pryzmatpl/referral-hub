<?php

use Phinx\Migration\AbstractMigration;

class CreateCompaniesTable extends AbstractMigration
{
    public function change(): void
    {
        $table = $this->table('companies');

        $table->addColumn('name', 'text', ['null' => true])
            ->addColumn('description', 'text', ['null' => true])
            ->addColumn('created_at', 'timestamp', ['null' => true, 'default' => 'CURRENT_TIMESTAMP'])
            ->addColumn('updated_at', 'timestamp', ['null' => true, 'default' => 'CURRENT_TIMESTAMP', 'update' => 'CURRENT_TIMESTAMP'])
            ->addColumn('currency', 'text', ['null' => true])
            ->addColumn('posterId', 'text', ['null' => true])
            ->create();
    }

}
