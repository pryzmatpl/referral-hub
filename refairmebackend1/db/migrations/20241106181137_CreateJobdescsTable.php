<?php

use Phinx\Migration\AbstractMigration;

class CreateJobdescsTable extends AbstractMigration
{
    public function change(): void
    {
        $table = $this->table('jobdescs');

        $table->addColumn('jobtitle', 'string', ['limit' => 255, 'null' => true])
            ->addColumn('required_exp', 'text', ['null' => true])
            ->addColumn('required_fund', 'text', ['null' => true])
            ->addColumn('required_relocation', 'integer', ['null' => true])
            ->addColumn('required_remote', 'integer', ['null' => true])
            ->addColumn('regdate', 'timestamp', ['default' => 'CURRENT_TIMESTAMP', 'update' => 'CURRENT_TIMESTAMP'])
            ->addColumn('keywords', 'string', ['limit' => 255, 'null' => true])
            ->addColumn('location', 'string', ['limit' => 255, 'null' => true])
            ->addColumn('description', 'text', ['null' => true])
            ->addColumn('poster_id', 'string', ['limit' => 255, 'null' => true])
            ->addColumn('bounty', 'float', ['null' => true])
            ->addColumn('hash', 'binary', ['null' => true])
            ->addColumn('musthave', 'text', ['null' => true])
            ->addColumn('nicetohave', 'text', ['null' => true])
            ->addColumn('essentials', 'text', ['null' => true])
            ->addColumn('specs', 'text', ['null' => true])
            ->addColumn('other', 'text', ['null' => true])
            ->addColumn('created_at', 'timestamp', ['null' => true])
            ->addColumn('updated_at', 'timestamp', ['null' => true])
            ->addColumn('currency', 'string', ['limit' => 50, 'null' => true])
            ->create();
    }

    public function down(): void
    {
        $this->table('jobdescs')->drop()->save();
    }
}
