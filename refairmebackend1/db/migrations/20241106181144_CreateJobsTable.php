<?php

use Phinx\Migration\AbstractMigration;

class CreateJobsTable extends AbstractMigration
{
    public function change(): void
    {
        $table = $this->table('jobs');

        $table->addColumn('title', 'text', ['null' => true])
            ->addColumn('exp', 'text', ['null' => true])
            ->addColumn('fund', 'text', ['null' => true])
            ->addColumn('relocation', 'integer', ['null' => true])
            ->addColumn('remote', 'integer', ['null' => true])
            ->addColumn('regdate', 'timestamp', ['default' => 'CURRENT_TIMESTAMP', 'update' => 'CURRENT_TIMESTAMP'])
            ->addColumn('keywords', 'string', ['limit' => 255, 'null' => true])
            ->addColumn('location', 'string', ['limit' => 255, 'null' => true])
            ->addColumn('description', 'text', ['null' => true])
            ->addColumn('posterId', 'string', ['limit' => 255, 'null' => true])
            ->addColumn('bounty', 'float', ['null' => true])
            ->addColumn('hash', 'binary', ['null' => true])
            ->addColumn('travelPercentage', 'integer', ['null' => true])
            ->addColumn('remotePercentage', 'integer', ['null' => true])
            ->addColumn('relocationPackage', 'integer', ['null' => true])
            ->addColumn('projectId', 'string', ['limit' => 255, 'null' => true])
            ->addColumn('other', 'text', ['null' => true])
            ->addColumn('created_at', 'timestamp', ['null' => true])
            ->addColumn('updated_at', 'timestamp', ['null' => true])
            ->addColumn('currency', 'string', ['limit' => 50, 'null' => true])
            ->addColumn('companyId', 'integer', ['null' => true])
            ->addColumn('contractType', 'string', ['limit' => 255, 'null' => true])
            ->create();
    }

}
