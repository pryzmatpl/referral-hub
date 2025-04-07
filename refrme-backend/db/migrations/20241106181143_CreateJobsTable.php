<?php

use Phinx\Migration\AbstractMigration;

class CreateJobsTable extends AbstractMigration
{
    public function change(): void
    {
        $table = $this->table('jobs', ['id' => false, 'primary_key' => ['id']]);

        $table->addColumn('id', 'biginteger', ['signed' => false, 'identity' => true])
            ->addColumn('title', 'text', ['null' => true])
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
            ->addColumn('musthave', 'text', ['null' => true])
            ->addColumn('nicetohave', 'text', ['null' => true])
            ->addColumn('essentials', 'text', ['null' => true])
            ->addColumn('specs', 'text', ['null' => true])
            ->addColumn('travelPercentage', 'integer', ['null' => true])
            ->addColumn('remotePercentage', 'integer', ['null' => true])
            ->addColumn('relocationPackage', 'string', ['null' => true])
            ->addColumn('projectId', 'string', ['limit' => 255, 'null' => true])
            ->addColumn('other', 'text', ['null' => true])
            ->addColumn('created_at', 'timestamp', ['null' => true])
            ->addColumn('updated_at', 'timestamp', ['null' => true])
            ->addColumn('currency', 'string', ['limit' => 50, 'null' => true])
            ->addColumn('contractType', 'string', ['limit' => 255, 'null' => true])
            ->addColumn('companyId', 'biginteger', ['null' => true, 'signed'=> false])
            ->addForeignKey('companyId', 'companies', 'id', ['delete'=> 'SET_NULL', 'update'=> 'NO_ACTION'])
            ->create();
    }

}
