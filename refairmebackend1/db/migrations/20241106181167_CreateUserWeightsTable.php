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
            ->addColumn('weight_one', 'float') // More descriptive column names
            ->addColumn('weight_two', 'float')
            ->addColumn('weight_three', 'float')
            ->addColumn('weight_four', 'float')
            ->addColumn('weight_five', 'float')
            ->addColumn('weight_six', 'float')
            ->addColumn('weight_seven', 'float')
            ->addColumn('weight_eight', 'float')
            ->addColumn('weight_nine', 'float')
            ->addColumn('weight_ten', 'float')
            ->addColumn('weight_eleven', 'float')
            ->addColumn('created_at', 'timestamp', ['null' => true])
            ->addColumn('updated_at', 'timestamp', ['null' => true])
            ->addColumn('user_id', 'integer', ['null' => true]) // Rename to user_id for clarity
            ->addColumn('keywords', 'text', ['collation' => 'utf8mb4_unicode_ci', 'null' => true])
            ->addIndex(['user_id'], ['name' => 'user_weights_user_id_index'])
            ->addForeignKey('user_id', 'users', 'id', ['delete' => 'CASCADE', 'update' => 'NO_ACTION'])
            ->create();
    }

}
