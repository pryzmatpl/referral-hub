<?php

use Phinx\Seed\AbstractSeed;

class JobSeeder extends AbstractSeed
{
    public function run():void
    {
        $data = [
            [
                'title' => 'Software Engineer',
                'exp' => '3+ years',
                'fund' => json_encode([90000, 100000]),
                'relocation' => 1,
                'remote' => 1,
                'regdate' => date('Y-m-d H:i:s'),
                'keywords' => 'PHP, Laravel, MySQL, API, Git',
                'location' => 'Berlin, Germany',
                'description' => 'Join our growing team as a Software Engineer. Work on cutting-edge web applications.',
                'posterId' => 1, // Assuming this matches an existing user ID
                'bounty' => 5000,
                'hash' => bin2hex(random_bytes(16)),
                'travelPercentage' => 10,
                'remotePercentage' => 80,
                'relocationPackage' => 'Yes',
                'projectId' => 2, // Assuming this matches an existing project ID
                'musthave' => 'Proficiency in PHP and Symfony, 3 years of experience',
                'nicetohave' => 'Experience with Docker and AWS',
                'essentials' => 'Strong problem-solving skills, team player',
                'specs' => 'Backend development, database optimization',
                'other' => 'Opportunity for growth and training.',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
                'currency' => 'EUR',
                'companyId' => 1, // Assuming this matches an existing company ID
                'contractType' => '["Full-Time"]',
            ],
            [
                'title' => 'Data Scientist',
                'exp' => '5+ years',
                'fund' => json_encode([100000, 110000]),
                'relocation' => 1,
                'remote' => 0,
                'regdate' => date('Y-m-d H:i:s'),
                'keywords' => 'Python, Machine Learning, Data Analysis, SQL, Hadoop',
                'location' => 'San Francisco, USA',
                'description' => 'We are looking for a Data Scientist to analyze large datasets and provide actionable insights.',
                'posterId' => 2, // Assuming this matches an existing user ID
                'bounty' => 7000,
                'hash' => bin2hex(random_bytes(16)),
                'travelPercentage' => 20,
                'remotePercentage' => 0,
                'relocationPackage' => 'Yes',
                'projectId' => 3, // Assuming this matches an existing project ID
                'musthave' => 'Proficiency in PHP and Symfony, 3 years of experience',
                'nicetohave' => 'Experience with Docker and AWS',
                'essentials' => 'Strong problem-solving skills, team player',
                'specs' => 'Backend development, database optimization',
                'other' => 'Work with an innovative team focusing on AI-driven solutions.',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
                'currency' => 'USD',
                'companyId' => 2, // Assuming this matches an existing company ID
                'contractType' => '["Contract"]',
            ],
            [
                'title' => 'Full Stack Developer',
                'exp' => '4+ years',
                'fund' => json_encode([85000, 95000]),
                'relocation' => 0,
                'remote' => 1,
                'regdate' => date('Y-m-d H:i:s'),
                'keywords' => 'JavaScript, React, Node.js, AWS, Docker',
                'location' => 'London, UK',
                'description' => 'Join our remote team and work on building scalable applications using modern technologies.',
                'posterId' => 3, // Assuming this matches an existing user ID
                'bounty' => 4000,
                'hash' => bin2hex(random_bytes(16)),
                'travelPercentage' => 5,
                'remotePercentage' => 100,
                'relocationPackage' => 'No',
                'projectId' => 4, // Assuming this matches an existing project ID
                'other' => 'Flexible hours and a collaborative environment.',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
                'currency' => 'GBP',
                'companyId' => 3, // Assuming this matches an existing company ID
                'contractType' => '["Full-Time"]',
            ],
        ];

        $this->table('jobs')->insert($data)->saveData();
    }
}
