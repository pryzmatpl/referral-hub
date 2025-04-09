<?php

use Phinx\Migration\AbstractMigration;

class CreateLocationsTable extends AbstractMigration
{
    /**
     * Do the migration
     */
    public function change(): void
    {
        $this->table('locations')
            ->addColumn('jobref_hashes', 'blob', ['null' => true])
            ->addColumn('name', 'string', ['limit' => 255])
            ->addColumn('city', 'string', ['limit' => 255])
            ->addColumn('country', 'string', ['limit' => 255])
            ->addColumn('address', 'string', ['limit' => 255])
            ->addColumn('zip', 'string', ['limit' => 255])
            ->addColumn('lat', 'text', ['null' => true])
            ->addColumn('lng', 'text', ['null' => true])
            ->addColumn('hash', 'binary', ['null' => true])
            ->addColumn('regdate', 'timestamp', ['default' => 'CURRENT_TIMESTAMP', 'update' => 'CURRENT_TIMESTAMP'])
            ->addColumn('userid', 'string', ['limit' => 255, 'null' => true])
            ->addColumn('description', 'binary', ['null' => true])
            ->addColumn('updated_at', 'timestamp', ['null' => true])
            ->addColumn('created_at', 'timestamp', ['null' => true])
            ->addColumn('currency', 'text', ['null' => true])
            ->create();
    }

}
