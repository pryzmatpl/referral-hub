<?php

use Phinx\Migration\AbstractMigration;

class CreateUserexpTable extends AbstractMigration
{

    public function change()
    {
        $this->table("userexp")
        ->addColumn('user_id', 'integer', ['null' => false])
        ->addColumn('name', 'string', ['limit' => 255])
        ->addColumn('role', 'string', ['limit' => 255])
        ->addColumn('responsibilities', 'text') // Assuming long text is needed
        ->addColumn('current_job', 'boolean')
        ->addColumn('start', 'date')
        ->addColumn('end', 'date', ['null' => true]) // Null allowed for ongoing jobs
        ->addColumn('years', 'integer', ['signed' => false])
        ->addColumn('salary', 'integer', ['signed' => false])
        ->addForeignKey('user_id', 'users', 'id', ['delete'=> 'CASCADE', 'update'=> 'CASCADE'])
        ->create();
    }
}
