<?php

use Phinx\Migration\AbstractMigration;

class CreateSocialLoginsTable extends AbstractMigration
{
    public function change(): void
    {
        $this->table('social_logins')
            ->addColumn('user_id', 'biginteger', ['signed' => false])
            ->addColumn('provider', 'string', ['limit' => 50, 'null' => false])
            ->addColumn('social_id', 'text', ['null' => false])
            ->addColumn('created_at', 'timestamp', ['null' => true])
            ->addColumn('updated_at', 'timestamp', ['null' => true])
            ->addIndex(['user_id'], ['name' => 'social_logins_user_id_index'])
            ->addForeignKey('user_id', 'users', 'id', ['delete' => 'CASCADE'])
            ->create();
    }

}

