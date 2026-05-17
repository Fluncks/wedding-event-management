<?php

namespace Tests\Support\Database\Seeds;

use CodeIgniter\Database\Seeder;

class ClientSeeder extends Seeder
{
    public function run()
    {
        $data = [
            [
                'bride_name' => 'Maria',
                'groom_name' => 'John',
                'phone'      => '081234567890',
                'created_at' => date('Y-m-d H:i:s'),
            ],
            [
                'bride_name' => 'Siti',
                'groom_name' => 'Ahmad',
                'phone'      => '082345678901',
                'created_at' => date('Y-m-d H:i:s'),
            ],
        ];

        $this->db->table('clients')->insertBatch($data);
    }
}
