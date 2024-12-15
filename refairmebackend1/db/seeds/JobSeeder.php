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
                'level' => '7',
                'salary_range' => '[11500,15000]',
                'remote' => 0,
                'active' => 1,
                'date' => '2018-05-17 18:14:58',
                'tags' => 'html,nginx,c++',
                'short_description' => 'who knows',
                'description' => '# Description \n### Job id \n* c++\n* nginx \n* html\n',
                'contact_email' => 'piotroxp@gmail.com',
                'apply_url' => null,
                'token' => 'VjFSQ2IxTXlUa2hXYmxKT1ZUTlNWVmxZY0hOU1JsVjRWbXhLVDJGNlJuaFZiR2h6U3pGT2NrOVZUbGRTVjNoV1ZrVldWazVzUmxoVmJYUmhWakZHZVZVeU1EVmhXRFZNVWxac1dGUXhTa1ZWZW5CdlpFY3hjMHBVU2tSaWJXUndZbTVuYkUxclRtcEtWRXBEU2xSS1EzNVFUMU5VUlZKSlJEcHdhVzkwY205NGNDVTBNR2R0WVdsc0xtTnZiUT09flJFR0RBVEU6MjAxOC0wNS0xNysxOCUzQTE0JTNBNTg=',
                'views' => 20,
                'applications' => 20,
                'featured' => 0,
                'company_id' => '29',
                'location' => '',
                'created_at' => '2018-05-17 18:14:58',
                'updated_at' => '2018-05-17 18:14:58',
                'currency' => '$',
                'status' => 11,
                'expired_at' => null
            ]
        ];

        // Insert data into the projects table
        $table = $this->table('jobs');
        $table->insert($data)->saveData();
    }
}
