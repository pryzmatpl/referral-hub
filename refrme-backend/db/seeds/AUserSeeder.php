<?php

use Phinx\Seed\AbstractSeed;

class AUserSeeder extends AbstractSeed
{
    public function run(): void
    {
        // Example user data
        $data = [
            [
                'first_name' => 'Alice',
                'last_name' => 'Smith',
                'password' => password_hash('password123', PASSWORD_DEFAULT),
                'remember_token' => bin2hex(random_bytes(16)),
                'activated' => 1, // 1 = Activated, 0 = Not activated
                'signup_ip_address' => '192.168.1.100',
                'signup_confirmation_ip_address' => '192.168.1.100',
                'signup_sm_ip_address' => null,
                'admin_ip_address' => '192.168.1.101',
                'updated_ip_address' => '192.168.1.102',
                'deleted_ip_address' => null,
                'last_login' => date('Y-m-d H:i:s'),
                'activ_code' => null,
                'group_id' => 2,
                'activ' => 1,
                'cvadded' => 1,
                'name' => 'Alice Smith',
                'email' => 'alice.smith@example.com',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
                "current_role" => 'recruiter',
                "unique_id" => '123'
            ],
            [
                'first_name' => 'Bob',
                'last_name' => 'Johnson',
                'password' => password_hash('securepassword', PASSWORD_DEFAULT),
                'remember_token' => bin2hex(random_bytes(16)),
                'activated' => 0,
                'signup_ip_address' => '192.168.2.200',
                'signup_confirmation_ip_address' => null,
                'signup_sm_ip_address' => null,
                'admin_ip_address' => null,
                'updated_ip_address' => null,
                'deleted_ip_address' => null,
                'last_login' => null,
                'activ_code' => bin2hex(random_bytes(8)),
                'group_id' => 3,
                'activ' => 0,
                'cvadded' => 0,
                'name' => 'Bob Johnson',
                'email' => 'bob.johnson@example.com',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
                "current_role" => 'recruiter',
                "unique_id" => '456'

            ],
            [
                'first_name' => 'Charlie',
                'last_name' => 'Brown',
                'password' => password_hash('securepassword', PASSWORD_DEFAULT),
                'remember_token' => bin2hex(random_bytes(16)),
                'activated' => 1,
                'signup_ip_address' => '192.168.2.300',
                'signup_confirmation_ip_address' => null,
                'signup_sm_ip_address' => null,
                'admin_ip_address' => null,
                'updated_ip_address' => null,
                'deleted_ip_address' => null,
                'last_login' => null,
                'activ_code' => bin2hex(random_bytes(8)),
                'group_id' => 2,
                'activ' => 1,
                'cvadded' => 1,
                'name' => 'Charlie Brown',
                'email' => 'charlie.brown@example.com',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
                "current_role" => 'recruiter',
                "unique_id" => '789'

            ]
        ];

        // Insert data into the users table
        $this->table('users')->insert($data)->saveData();
    }
}
