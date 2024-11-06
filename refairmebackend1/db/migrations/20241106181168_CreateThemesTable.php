<?php

use Phinx\Migration\AbstractMigration;

class CreateThemesTable extends AbstractMigration
{
    /**
     * Do the migration
     */
    public function change(): void
    {
        $this->table('themes')
            ->addColumn('name', 'string', ['limit' => 191, 'unique' => true, 'collation' => 'utf8mb4_unicode_ci'])
            ->addColumn('link', 'string', ['limit' => 191, 'unique' => true, 'collation' => 'utf8mb4_unicode_ci'])
            ->addColumn('notes', 'string', ['limit' => 191, 'collation' => 'utf8mb4_unicode_ci', 'null' => true])
            ->addColumn('status', 'boolean', ['default' => 1])
            ->addColumn('taggable_id', 'integer', ['signed' => false])
            ->addColumn('taggable_type', 'string', ['limit' => 191, 'collation' => 'utf8mb4_unicode_ci'])
            ->addTimestamps()
            ->addSoftDelete()
            ->addIndex(['taggable_id', 'taggable_type'], ['name' => 'themes_taggable_id_taggable_type_index'])
            ->create();
    }

}
