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
                'email' => 'piotroxp%40gmail.com',
                'user_id' => 321,
                'technologies' => '"c++,nginx"',
                'tasks' => '{"newFeatures":27,"maintenance":34,"support":55,"documentation":20,"meetings":27}',
                'status' => '11',
                'created_at' => '2018-05-17 17:58:43',
                'updated_at' => '2018-05-17 17:58:43',
                'completed_at' => null,
                'tools' => '["unit testing","code reviews","knowledge repository"]',
                'project_type' => 'greenfield',
                'description' => 'Testing Project',
                'other_field' => null
            ],
            [
                'id' => 29,
                'name' => "Because It's awesome",
                'email' => null,
                'user_id' => 321,
                'technologies' => '"c++,nginx"',
                'tasks' => '{"newFeatures":39,"maintenance":20,"support":42,"documentation":20,"meetings":20}',
                'status' => '11',
                'created_at' => '2018-05-17 18:13:33',
                'updated_at' => '2018-05-17 18:13:33',
                'completed_at' => null,
                'tools' => '["pair programming","code reviews","issue tracking tool"]',
                'project_type' => 'greenfield',
                'description' => 'Testing Project 2',
                'other_field' => null
            ]
        ];

        // Insert data into the projects table
        $table = $this->table('projects');
        $table->insert($data)->saveData();
    }
}
