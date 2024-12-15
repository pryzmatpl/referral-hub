<?php

use Phinx\Migration\AbstractMigration;

class CreateSignoffsTable extends AbstractMigration
{
    public function change(): void
    {
        $this->table('signoffs')
            ->addColumn('name', 'string', ['limit' => 191, 'null' => false])
            ->addColumn('referrer_id', 'string', ['limit' => 191, 'null' => true])
            ->addColumn('referred_id', 'string', ['limit' => 191, 'null' => true])
            ->addColumn('reviewers_hash', 'binary', ['null' => true])
            ->addColumn('statehash', 'text', ['null' => true])
            ->addColumn('token', 'string', ['limit' => 191, 'null' => false])
            ->addColumn('signup_ip_address', 'string', ['limit' => 45, 'null' => true])
            ->addColumn('signup_confirmation_ip_address', 'string', ['limit' => 45, 'null' => true])
            ->addColumn('signup_sm_ip_address', 'string', ['limit' => 45, 'null' => true])
            ->addColumn('admin_ip_address', 'string', ['limit' => 45, 'null' => true])
            ->addColumn('updated_ip_address', 'string', ['limit' => 45, 'null' => true])
            ->addColumn('deleted_ip_address', 'string', ['limit' => 45, 'null' => true])
            ->addColumn('created_at', 'timestamp', ['null' => true])
            ->addColumn('updated_at', 'timestamp', ['null' => true])
            ->addColumn('deleted_at', 'timestamp', ['null' => true])
            ->addColumn('jobid', 'integer', ['null' => true])
            ->addColumn('cvfile', 'text', ['null' => true])
            ->create();
    }

}
