<?php

use Phinx\Migration\AbstractMigration;

class CreateProfilesTable extends AbstractMigration
{
    /**
     * Do the migration
     */
    public function change(): void
    {
        $this->table('profiles')
            ->addColumn('user_id', 'biginteger', ['signed' => false])
            ->addColumn('theme_id', 'integer', ['default' => 1])
            ->addColumn('location', 'string', ['limit' => 191, 'null' => true])
            ->addColumn('bio', 'text', ['null' => true])
            ->addColumn('twitter_username', 'string', ['limit' => 191, 'null' => true])
            ->addColumn('github_username', 'string', ['limit' => 191, 'null' => true])
            ->addColumn('avatar', 'string', ['limit' => 191, 'null' => true])
            ->addColumn('avatar_status', 'integer', ['default' => 0])
            ->addColumn('created_at', 'timestamp', ['null' => true])
            ->addColumn('updated_at', 'timestamp', ['null' => true])

            // Foreign key constraints
            ->addForeignKey('user_id', 'users', 'id', ['delete' => 'CASCADE', 'update' => 'NO_ACTION'])

            // Indexes
            ->addIndex(['theme_id'], ['name' => 'profiles_theme_id_foreign'])
            ->addIndex(['user_id'], ['name' => 'profiles_user_id_index'])

            ->create();
    }

}
