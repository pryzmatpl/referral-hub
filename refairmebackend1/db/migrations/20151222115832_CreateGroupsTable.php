<?php

use Phinx\Migration\AbstractMigration;

class CreateGroupsTable extends AbstractMigration
{
    /**
     * Change Method.
     */
    public function change(): void
    {
        $table = $this->table('groups');
        $table->addColumn('group_name', 'string')
            ->addColumn('description', 'string')
            ->addTimestamps()
            ->create();
    }

    /**
     * Down Method.
     */
    public function down(): void
    {
        $this->table('groups')->drop()->save();
    }
}
