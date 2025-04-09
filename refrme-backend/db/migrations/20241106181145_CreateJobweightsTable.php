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
        $this->table('job_weights')
            ->addColumn('weights', 'text') // Store the classifier's JSON output here.
            ->addColumn('jobid', 'biginteger', ['signed' => false]) // Use unsigned integer for consistency.
            ->addColumn('keywords', 'text', [
                'collation' => 'utf8mb4_unicode_ci',
                'null' => true
            ])
            ->addColumn('created_at', 'timestamp', ['null' => true])
            ->addColumn('updated_at', 'timestamp', ['null' => true])
            ->addIndex(['jobid'], ['name' => 'job_weights_jobid_index'])
            ->addForeignKey('jobid', 'jobs', 'id', [
                'delete' => 'CASCADE',
                'update' => 'NO_ACTION'
            ])
            ->create();
    }
}
