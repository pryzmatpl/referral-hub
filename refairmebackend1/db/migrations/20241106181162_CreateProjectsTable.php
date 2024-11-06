<?php

use Phinx\Migration\AbstractMigration;

class CreateProjectsTable extends AbstractMigration
{
    public function change(): void
    {
        $this->table('projects')
            ->addColumn('description', 'text', ['null' => true])
            ->addColumn('posterId', 'string', ['limit' => 255, 'null' => true])
            ->addColumn('staff', 'integer', ['null' => true])
            ->addColumn('stack', 'text', ['null' => true])
            ->addColumn('breakdown', 'text', ['null' => true])
            ->addColumn('companyId', 'text', ['null' => true])
            ->addColumn('currency', 'string', ['limit' => 50, 'null' => true])
            ->addColumn('methodology', 'text', ['null' => true])
            ->addColumn('stage', 'text', ['null' => true])
            ->addColumn('name', 'text', ['null' => true])
            ->addColumn('contractType', 'string', ['limit' => 255, 'null' => true])
            ->addColumn('logo', 'text', ['null' => true])
            ->addColumn('projectType', 'string', ['limit' => 255, 'null' => true])
            ->addColumn('workload', 'text', ['null' => true])
            ->addColumn('requiredSkills', 'text', ['null' => true])
            ->addColumn('perks', 'text', ['null' => true])
            ->addPrimaryKey('id')
            ->create();
    }

}
