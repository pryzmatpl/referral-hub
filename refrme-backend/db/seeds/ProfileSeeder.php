<?php

use Phinx\Seed\AbstractSeed;

class ProfileSeeder extends AbstractSeed
{
    public function run():void
    {
        // Example profile data
        $data = [
            [
                'user_id' => 1,
                'theme_id' => 2,
                'location' => 'Berlin, Germany',
                'bio' => 'Software engineer with a passion for open source and clean code.',
                'twitter_username' => 'alice_dev',
                'github_username' => 'alice-smith',
                'avatar' => 'https://example.com/avatars/alice.jpg',
                'avatar_status' => 1, // 1 = Active, 0 = Inactive
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'user_id' => 2,
                'theme_id' => 3,
                'location' => 'San Francisco, USA',
                'bio' => 'Data scientist focusing on machine learning and AI.',
                'twitter_username' => 'bob_ai',
                'github_username' => 'bob-johnson',
                'avatar' => 'https://example.com/avatars/bob.jpg',
                'avatar_status' => 1,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'user_id' => 3,
                'theme_id' => 1,
                'location' => 'Remote',
                'bio' => 'Freelance designer specializing in UX/UI and branding.',
                'twitter_username' => 'charlie_design',
                'github_username' => 'charlie-ux',
                'avatar' => null, // No avatar uploaded yet
                'avatar_status' => 0,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
        ];

        // Insert data into the profiles table
        $this->table('profiles')->insert($data)->saveData();
    }
}
