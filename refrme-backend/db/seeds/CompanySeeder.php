<?php

use Phinx\Seed\AbstractSeed;

class CompanySeeder extends AbstractSeed
{
    public function run(): void
    {
        $data = [
            [
                'name' => 'TechNova Solutions',
                'description' => 'Innovative software solutions for businesses.',
                'currency' => 'USD',
                'posterId' => 1,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'name' => 'GreenFuture Energy',
                'description' => 'Renewable energy solutions to power a sustainable future.',
                'currency' => 'EUR',
                'posterId' => 2,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'name' => 'AeroTech Dynamics',
                'description' => 'Cutting-edge aerospace engineering and defense technology.',
                'currency' => 'GBP',
                'posterId' => 3,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
        ];

        $this->table('companies')->insert($data)->saveData();
    }
}
