<?php

use Phinx\Migration\AbstractMigration;


class CreateJobweightsTable extends AbstractMigration
{
    /**
     * Do the migration
     * Weights NEED TO BE THE RETURN JSON FROM THE CLASSIFIER
     */
    public function change(): void
    {
        $this->table('jobweights')
            ->addColumn('weights', 'text')
            ->addColumn('created_at', 'timestamp', ['null' => true])
            ->addColumn('updated_at', 'timestamp', ['null' => true])
            ->addColumn('jobid', 'integer', ['null' => true])
            ->addColumn('keywords', 'text', ['collation' => 'utf8mb4_unicode_ci', 'null' => true])
            ->create();
    }

}
