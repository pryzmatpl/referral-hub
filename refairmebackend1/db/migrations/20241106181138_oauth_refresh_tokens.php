<?php

use Phinx\Migration\AbstractMigration;

class CreateOauthRefreshTokensTable extends AbstractMigration
{
    /**
     * Do the migration
     */
    public function change(): void
    {
        $this->table('oauth_refresh_tokens')
            ->addColumn('refresh_token', 'string', ['limit' => 40, 'null' => false, 'collation' => 'utf8mb4_unicode_ci'])
            ->addPrimaryKey('refresh_token')
            ->addColumn('client_id', 'string', ['limit' => 80, 'null' => false, 'collation' => 'utf8mb4_unicode_ci'])
            ->addColumn('user_id', 'string', ['limit' => 255, 'null' => true, 'collation' => 'utf8mb4_unicode_ci'])
            ->addColumn('expires', 'timestamp', ['default' => 'CURRENT_TIMESTAMP', 'update' => 'CURRENT_TIMESTAMP'])
            ->addColumn('scope', 'string', ['limit' => 2000, 'null' => true, 'collation' => 'utf8mb4_unicode_ci'])
            ->create();
    }

    /**
     * Undo the migration
     */
    public function down(): void
    {
        $this->table('oauth_refresh_tokens')->drop()->save();
    }
}
