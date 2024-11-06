<?php

use Phinx\Migration\AbstractMigration;

class CreateOauthJwtTable extends AbstractMigration
{
    /**
     * Do the migration
     */
    public function change(): void
    {
        $this->table('oauth_jwt')
            ->addColumn('client_id', 'string', ['limit' => 80, 'null' => false, 'collation' => 'utf8mb4_unicode_ci'])
            ->addPrimaryKey('client_id')
            ->addColumn('subject', 'string', ['limit' => 80, 'null' => true, 'collation' => 'utf8mb4_unicode_ci'])
            ->addColumn('public_key', 'string', ['limit' => 2000, 'null' => true, 'collation' => 'utf8mb4_unicode_ci'])
            ->create();
    }

}
