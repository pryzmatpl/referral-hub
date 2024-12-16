<?php


use Phinx\Seed\AbstractSeed;

class JobSeeder extends AbstractSeed
{
    /**
     * Run Method.
     *
     * Write your database seeder using this method.
     *
     * More information on writing seeders is available here:
     * https://book.cakephp.org/phinx/0/en/seeding.html
     */
    public function run()
    {
        $data = [
            [
                'id' => 212,
                'title' => 'Added Job',
                #'level' => '7',
                'fund' => '[11500,15000]',
                'remote' => 0,
                'description' => '# Description \n### Job id \n* c++\n* nginx \n* html\n',
                'companyId' => '29',
                'location' => '',
                'created_at' => '2018-05-17 18:14:58',
                'updated_at' => '2018-05-17 18:14:58',
                'currency' => 'USD',
            ]
        ];

        // Insert data into the projects table
        $table = $this->table('jobs');
        $table->insert($data)->saveData();
    }
}
