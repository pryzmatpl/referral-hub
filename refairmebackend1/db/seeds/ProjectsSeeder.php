<?php

use Phinx\Seed\AbstractSeed;

class ProjectsSeeder extends AbstractSeed
{
    public function run()
    {
        // Define the data as an array of arrays (one per row)
        $data = [
            [
                'id' => 28,
                'name' => "Because It's awesome",
                'description' => 'Testing Project',
            ],
            [
                'id' => 29,
                'name' => "Because It's awesome",
                'description' => 'Testing Project 2',
            ]
        ];

        // Insert data into the projects table
        $table = $this->table('projects');
        $table->insert($data)->saveData();
    }
}
