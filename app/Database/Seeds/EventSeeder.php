<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class EventSeeder extends Seeder
{
    public function run()
    {
        // First seed clients and locations that events depend on
        $this->call('ClientSeeder');
        $this->call('LocationSeeder');

        $data = [
            [
                'name'        => 'Grand Wedding Ceremony',
                'client_id'   => 1,
                'location_id' => 1,
                'event_type'  => 'ceremony',
                'event_date'  => '2025-06-15',
                'event_time'  => '10:00:00',
                'guests'      => 200,
                'budget'      => 50000000,
                'status'      => 'pending',
                'created_at'  => date('Y-m-d H:i:s'),
            ],
            [
                'name'        => 'Reception Party',
                'client_id'   => 2,
                'location_id' => 2,
                'event_type'  => 'reception',
                'event_date'  => '2025-07-20',
                'event_time'  => '17:00:00',
                'guests'      => 150,
                'budget'      => 75000000,
                'status'      => 'pending',
                'created_at'  => date('Y-m-d H:i:s'),
            ],
        ];

        $this->db->table('events')->insertBatch($data);
    }
}
