<?php

use Phinx\Migration\AbstractMigration;

class CreatePermissionsTable extends AbstractMigration
{
    /**
     * Do the migration
     */
    public function change(): void
    {
        $table = $this->table('permissions', ['id' => false, 'primary_key' => ['id']]);

        $table->addColumn('id', 'biginteger', ['signed' => false, 'identity' => true])
            ->addColumn('name', 'string', ['limit' => 191])
            ->addColumn('slug', 'string', ['limit' => 191])
            ->addColumn('description', 'string', ['limit' => 191, 'null' => true])
            ->addColumn('model', 'string', ['limit' => 191, 'null' => true])
            ->addColumn('created_at', 'timestamp', ['null' => true])
            ->addColumn('updated_at', 'timestamp', ['null' => true])
            ->addIndex('slug', ['unique' => true]) // Unique index for slug
            ->create();
    }

}
