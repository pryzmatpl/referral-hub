<?php
use Phinx\Seed\AbstractSeed;
class UserSeeder extends AbstractSeed
{
    public function run(): void
    {
        $data = [
            [
                'username'  => 'admin',
                'email'     => 'admin@slim.dev',
                'password'  => '$2y$10$ElXh/aFKLN1Vf4t2G0DTnupWcEpS2/2OP8fIsQXjHp7KXE3bjcUke',
                'group_id'  => 1,
                'status'    => 1,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ],
            [
                'username'  => 'moderator',
                'email'     => 'moderator@slim.dev',
                'password'  => '$2y$10$ElXh/aFKLN1Vf4t2G0DTnupWcEpS2/2OP8fIsQXjHp7KXE3bjcUke',
                'group_id'  => 1,
                'status'    => 1,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ],
            [
                'username'  => 'user',
                'email'     => 'user@slim.dev',
                'password'  => '$2y$10$ElXh/aFKLN1Vf4t2G0DTnupWcEpS2/2OP8fIsQXjHp7KXE3bjcUke',
                'group_id'  => 1,
                'status'    => 1,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ]
        ];

        $this->table('users')->insert($data)->saveData();
    }
}
