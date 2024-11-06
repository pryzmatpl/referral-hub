<?php

use Phinx\Migration\AbstractMigration;

class CreateLinkedinImportsTable extends AbstractMigration
{
    /**
     * Do the migration
     */
    public function change(): void
    {
        $this->table('linkedin_imports')
            ->addColumn('firstName', 'string', ['limit' => 255])
            ->addColumn('lastName', 'string', ['limit' => 255])
            ->addColumn('company', 'string', ['limit' => 255])
            ->addColumn('title', 'string', ['limit' => 255, 'null' => true])
            ->addColumn('email', 'string', ['limit' => 255, 'null' => true])
            ->addColumn('phone', 'string', ['limit' => 255, 'null' => true])
            ->addColumn('notes', 'string', ['limit' => 255, 'null' => true])
            ->addColumn('tags', 'string', ['limit' => 255, 'null' => true])
            ->addColumn('uidInviter', 'string', ['limit' => 255, 'null' => true])
            ->addColumn('regdate', 'timestamp', ['default' => Capsule::raw('CURRENT_TIMESTAMP'), 'update' => Capsule::raw('CURRENT_TIMESTAMP')])
            ->addColumn('created_at', 'timestamp', ['default' => '0000-00-00 00:00:00'])
            ->addColumn('updated_at', 'timestamp', ['default' => '0000-00-00 00:00:00'])
            ->addColumn('skills', 'text', ['null' => true])
            ->create();
    }

    /**
     * Undo the migration
     */
    public function down(): void
    {
        $this->table('linkedin_imports')->drop()->save();
    }
}
