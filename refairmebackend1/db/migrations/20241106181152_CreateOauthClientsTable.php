<?php

use Phinx\Migration\AbstractMigration;

class CreateOauthClientsTable extends AbstractMigration
{
    /**
     * Do the migration
     */
    public function change(): void
    {
        $this->table('oauth_clients')
            ->addColumn('client_id', 'string', ['limit' => 80, 'null' => false, 'collation' => 'utf8mb4_unicode_ci'])
            ->addPrimaryKey('client_id')
            ->addColumn('client_secret', 'string', ['limit' => 5000, 'null' => true, 'collation' => 'utf8mb4_unicode_ci'])
            ->addColumn('redirect_uri', 'string', ['limit' => 2000, 'null' => false, 'collation' => 'utf8mb4_unicode_ci'])
            ->addColumn('grant_types', 'string', ['limit' => 80, 'null' => true, 'collation' => 'utf8mb4_unicode_ci'])
            ->addColumn('scope', 'string', ['limit' => 100, 'null' => true, 'collation' => 'utf8mb4_unicode_ci'])
            ->addColumn('user_id', 'string', ['limit' => 80, 'null' => true, 'collation' => 'utf8mb4_unicode_ci'])
            ->addColumn('updated_at', 'timestamp', ['null' => true])
            ->addColumn('created_at', 'timestamp', ['null' => true])
            ->create();
    }

}
