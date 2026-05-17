<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class LocationSeeder extends Seeder
{
    public function run()
    {
        $data = [
            [
                'name'            => 'Grand Ballroom',
                'address'         => 'Jl. Sudirman No. 1, Jakarta',
                'type'            => 'ballroom',
                'capacity'        => 500,
                'contact_phone'   => '021-1234567',
                'created_at'      => date('Y-m-d H:i:s'),
            ],
            [
                'name'            => 'Garden Venue',
                'address'         => 'Jl. Gatot Subroto, Jakarta',
                'type'            => 'garden',
                'capacity'        => 300,
                'contact_phone'   => '021-9876543',
                'created_at'      => date('Y-m-d H:i:s'),
            ],
        ];

        $this->db->table('locations')->insertBatch($data);
    }
}
