<?php

use Phinx\Migration\AbstractMigration;

class CreateUserWeightsTable extends AbstractMigration
{
    /**
     * Do the migration
     */
    public function change(): void
    {
        $this->table('user_weights')
            ->addColumn('weights', 'text') // Store the classifier's JSON output here.
            ->addColumn('user_id', 'biginteger', ['signed' => false]) // Renamed for clarity.
            ->addColumn('keywords', 'text', [
                'collation' => 'utf8mb4_unicode_ci',
                'null' => true
            ])
            ->addColumn('created_at', 'timestamp', ['null' => true])
            ->addColumn('updated_at', 'timestamp', ['null' => true])
            ->addIndex(['user_id'], ['name' => 'userweights_userid_index'])
            ->addForeignKey('user_id', 'users', 'id', [
                'delete' => 'CASCADE',
                'update' => 'NO_ACTION'
            ])
            ->create();
    }
}
