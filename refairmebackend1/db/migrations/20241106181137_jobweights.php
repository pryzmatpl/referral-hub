<?php

use Phinx\Migration\AbstractMigration;


class CreateJobweightsTable extends AbstractMigration
{
    /**
     * Do the migration
     */
    public function change(): void
    {
        $this->table('jobweights')
            ->addColumn('aone', 'double')
            ->addColumn('atwo', 'double')
            ->addColumn('athree', 'double')
            ->addColumn('afour', 'double')
            ->addColumn('afive', 'double')
            ->addColumn('asix', 'double')
            ->addColumn('aseven', 'double')
            ->addColumn('aeight', 'double')
            ->addColumn('anine', 'double')
            ->addColumn('aten', 'double')
            ->addColumn('aeleven', 'double')
            ->addColumn('created_at', 'timestamp', ['null' => true])
            ->addColumn('updated_at', 'timestamp', ['null' => true])
            ->addColumn('jobid', 'integer', ['null' => true])
            ->addColumn('keywords', 'text', ['collation' => 'utf8mb4_unicode_ci', 'null' => true])
            ->create();
    }

    /**
     * Undo the migration
     */
    public function down(): void
    {
        $this->table('jobweights')->drop()->save();
    }
}
