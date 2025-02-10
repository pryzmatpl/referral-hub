<?php

use Phinx\Seed\AbstractSeed;

class JobdescsSeeder extends AbstractSeed
{
    public function run()
    {
        // Example job descriptions
        $data = [
            [
                'jobtitle' => 'Software Engineer',
                'required_exp' => 3,
                'required_fund' => 50000,
                'required_relocation' => 1, // 1 = Required, 0 = Not required
                'required_remote' => 1, // 1 = Available, 0 = Not available
                'regdate' => date('Y-m-d H:i:s'),
                'keywords' => 'PHP, Symfony, Docker, MySQL',
                'location' => 'Berlin, Germany',
                'description' => 'Develop and maintain web applications using Symfony and Docker.',
                'poster_id' => 1,
                'bounty' => 1000,
                'hash' => hash('sha256', uniqid()),
                'musthave' => 'Proficiency in PHP and Symfony, 3 years of experience',
                'nicetohave' => 'Experience with Docker and AWS',
                'essentials' => 'Strong problem-solving skills, team player',
                'specs' => 'Backend development, database optimization',
                'other' => 'Flexible working hours, learning opportunities',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
                'currency' => 'EUR',
            ],
            [
                'jobtitle' => 'Data Scientist',
                'required_exp' => 5,
                'required_fund' => 70000,
                'required_relocation' => 0,
                'required_remote' => 1,
                'regdate' => date('Y-m-d H:i:s'),
                'keywords' => 'Python, Machine Learning, TensorFlow, SQL',
                'location' => 'Remote',
                'description' => 'Analyze complex datasets to generate actionable insights and develop machine learning models.',
                'poster_id' => 2,
                'bounty' => 1500,
                'hash' => hash('sha256', uniqid()),
                'musthave' => 'Strong Python skills, experience with TensorFlow and machine learning',
                'nicetohave' => 'Knowledge of big data tools like Spark',
                'essentials' => 'Detail-oriented, excellent communication skills',
                'specs' => 'Data analysis, model development',
                'other' => 'Option for fully remote work, annual bonus',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
                'currency' => 'USD',
            ],
        ];

        // Insert data into the jobdescs table
        $this->table('jobdescs')->insert($data)->saveData();
    }
}
