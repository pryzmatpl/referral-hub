<?php

use Phinx\Migration\AbstractMigration;

class CreateUserdescsTable extends AbstractMigration
{
    public function change()
    {
        $this->table('userdescs')
              ->addColumn('user_id', 'biginteger', ['signed' => false])
              ->addColumn('keywords', 'json', ['null' => true])
              ->addColumn('skills', 'json', ['null' => true])
              ->addColumn('notice_period', 'string', ['limit' => 255, 'null' => true])
              ->addColumn('availability', 'string', ['limit' => 255, 'null' => true])
              ->addColumn('expected_salary', 'string', ['limit' => 255, 'null' => true])
              ->addColumn('job_status', 'string', ['limit' => 255, 'null' => true])
              ->addColumn('created_at', 'timestamp', ['default' => 'CURRENT_TIMESTAMP'])
              ->addColumn('updated_at', 'timestamp', ['null' => true])
              ->addForeignKey('user_id', 'users', 'id', ['delete'=> 'CASCADE', 'update'=> 'CASCADE'])
              ->create();
    }
}

