<?php

use Phinx\Seed\AbstractSeed;

class ProjectsSeeder extends AbstractSeed
{
    public function run()
    {
        // Example project data
        $data = [
            [
                'description' => 'A cutting-edge AI platform to optimize logistics.',
                'posterId' => 1,
                'staff' => json_encode(['Alice']), // Example team members
                'stack' => json_encode(['Python', 'TensorFlow', 'AWS']),
                'breakdown' => json_encode(['Frontend' => 'React', 'Backend' => 'Django']),
                'companyId' => 101,
                'currency' => 'USD',
                'methodology' => 'Agile',
                'stage' => 'Prototype',
                'name' => 'LogiAI',
                'contractType' => 'Full-time',
                'logo' => 'https://example.com/logos/logiai.png',
                'projectType' => 'AI/ML',
                'workload' => '40 hours/week',
                'requiredSkills' => json_encode(['Machine Learning', 'Cloud Deployment']),
                'perks' => json_encode(['Remote Work', 'Flexible Hours']),
            ],
            [
                'description' => 'A mobile-first platform for personal finance management.',
                'posterId' => 2,
                'staff' => json_encode(['Dave', 'Emma']),
                'stack' => json_encode(['Kotlin', 'Swift', 'Firebase']),
                'breakdown' => json_encode(['Mobile App' => 'Flutter', 'Backend' => 'Node.js']),
                'companyId' => 202,
                'currency' => 'EUR',
                'methodology' => 'Scrum',
                'stage' => 'Production',
                'name' => 'FinTrack',
                'contractType' => 'Freelance',
                'logo' => 'https://example.com/logos/fintrack.png',
                'projectType' => 'FinTech',
                'workload' => '20 hours/week',
                'requiredSkills' => json_encode(['Mobile Development', 'API Integration']),
                'perks' => json_encode(['Competitive Rates', 'Remote Work']),
            ],
            [
                'description' => 'A blockchain-based platform for secure document sharing.',
                'posterId' => 3,
                'staff' => json_encode(['Frank', 'Grace', 'Helen']),
                'stack' => json_encode(['Solidity', 'React', 'IPFS']),
                'breakdown' => json_encode(['Smart Contracts' => 'Solidity', 'Frontend' => 'React']),
                'companyId' => 303,
                'currency' => 'BTC',
                'methodology' => 'Kanban',
                'stage' => 'MVP',
                'name' => 'DocChain',
                'contractType' => 'Part-time',
                'logo' => 'https://example.com/logos/docchain.png',
                'projectType' => 'Blockchain',
                'workload' => '25 hours/week',
                'requiredSkills' => json_encode(['Blockchain Development', 'React']),
                'perks' => json_encode(['Equity Options', 'Flexible Hours']),
            ],
        ];

        // Insert data into the projects table
        $this->table('projects')->insert($data)->saveData();
    }
}
