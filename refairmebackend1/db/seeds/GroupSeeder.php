<?php

use Phinx\Seed\AbstractSeed;

class GroupsSeeder extends AbstractSeed
{
    public function run(): void
    {
        $data = [
            [
                'group_name'  => 'Admin',
                'description' => 'Administrator',
                'created_at'  => date('Y-m-d H:i:s'),
                'updated_at'  => date('Y-m-d H:i:s')
            ],
            [
                'group_name'  => 'Moderator',
                'description' => 'Moderator',
                'created_at'  => date('Y-m-d H:i:s'),
                'updated_at'  => date('Y-m-d H:i:s')
            ],
            [
                'group_name'  => 'User',
                'description' => 'User',
                'created_at'  => date('Y-m-d H:i:s'),
                'updated_at'  => date('Y-m-d H:i:s')
            ]
        ];

        $this->table('groups')->insert($data)->saveData();
    }
}
