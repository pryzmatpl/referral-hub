<?php

use Phinx\Seed\AbstractSeed;

class AppointmentSeeder extends AbstractSeed
{
    public function run(): void
    {
        // Example appointment data
        $data = [
            [
                'candidate_id' => 1,
                'recruiter_id' => 2,
                'appointment_start' => '2024-12-18 10:00:00',
                'appointment_end' => '2024-12-18 11:00:00',
                'status' => 'scheduled', // Possible values: scheduled, completed, canceled, etc.
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
                'deleted_at' => null, // Null indicates it's not deleted
            ],
            [
                'candidate_id' => 3,
                'recruiter_id' => 4,
                'appointment_start' => '2024-12-19 14:00:00',
                'appointment_end' => '2024-12-19 15:00:00',
                'status' => 'completed',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
                'deleted_at' => null,
            ],
            [
                'candidate_id' => 5,
                'recruiter_id' => 6,
                'appointment_start' => '2024-12-20 09:00:00',
                'appointment_end' => '2024-12-20 09:30:00',
                'status' => 'canceled',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
                'deleted_at' => '2024-12-20 09:15:00', // Marked as deleted after being canceled
            ],
        ];

        // Insert data into the appointments table
        $this->table('appointments')->insert($data)->saveData();
    }
}
