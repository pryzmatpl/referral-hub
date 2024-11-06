<?php

use Phinx\Migration\AbstractMigration;

class CreateOauthScopesTable extends AbstractMigration
{
    /**
     * Do the migration
     */
    public function change(): void
    {
        $this->table('oauth_scopes')
            ->addColumn('scope', 'text', ['null' => false, 'collation' => 'utf8mb4_unicode_ci'])
            ->addColumn('is_default', 'boolean', ['null' => true])
            // Optionally, uncomment to add primary key
            // ->addPrimaryKey('scope')
            ->create();
    }

    /**
     * Undo the migration
     */
    public function down(): void
    {
        $this->table('oauth_scopes')->drop()->save();
    }
}

