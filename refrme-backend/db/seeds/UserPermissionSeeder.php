<?php

use Phinx\Seed\AbstractSeed;

class UserPermissionSeeder extends AbstractSeed
{
    public function run(): void
    {
        $data = [
            [
                'group_id' => 1,
                'page'     => 'user',
                'action'   => 'index',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ],
            [
                'group_id' => 1,
                'page'     => 'user',
                'action'   => 'edit',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ],
            [
                'group_id' => 1,
                'page'     => 'user',
                'action'   => 'delete',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ],
            [
                'group_id' => 1,
                'page'     => 'group',
                'action'   => 'index',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ],
            [
                'group_id' => 1,
                'page'     => 'group',
                'action'   => 'edit',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ],
            [
                'group_id' => 1,
                'page'     => 'group',
                'action'   => 'delete',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ],
            [
                'group_id' => 1,
                'page'     => 'permission',
                'action'   => 'index',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ],
            [
                'group_id' => 1,
                'page'     => 'permission',
                'action'   => 'edit',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ],
            [
                'group_id' => 1,
                'page'     => 'permission',
                'action'   => 'delete',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ]
        ];

        $this->table('users_permission')->insert($data)->saveData();
    }
}
