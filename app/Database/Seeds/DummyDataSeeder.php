<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

/**
 * Resets app data and inserts sample users, locations, clients, and events.
 * Run: php spark db:seed DummyDataSeeder
 */
class DummyDataSeeder extends Seeder
{
    public function run()
    {
        $db = $this->db;
        $db->disableForeignKeyChecks();

        $db->table('events')->truncate();
        $db->table('clients')->truncate();
        $db->table('locations')->truncate();
        $db->table('users')->truncate();
        $db->table('settings')->truncate();

        $db->enableForeignKeyChecks();

        $demoPass = password_hash('admin123', PASSWORD_DEFAULT);

        $db->table('users')->insertBatch([
            [
                'name'     => 'Admin User',
                'email'    => 'admin@batakwedding.com',
                'password' => $demoPass,
                'role'     => 'admin',
                'phone'    => '+62 812 3456 7890',
            ],
            [
                'name'     => 'Staff Member',
                'email'    => 'staff@batakwedding.com',
                'password' => $demoPass,
                'role'     => 'staff',
                'phone'    => '+62 813 0000 1111',
            ],
        ]);

        $db->table('settings')->insertBatch([
            ['setting_key' => 'site_name', 'setting_value' => 'Horas Wedding'],
            ['setting_key' => 'welcome_note', 'setting_value' => 'Staff Portal'],
        ]);

        $db->table('locations')->insertBatch([
            [
                'name'          => 'Aula Parbaba',
                'image'         => null,
                'address'       => 'Jl. Sisingamangaraja No. 123, Medan',
                'capacity'      => 800,
                'type'          => 'Indoor Hall',
                'facilities'    => 'AC, Sound System, Parking',
                'contact_phone' => '+62 812 9876 5432',
                'description'   => 'Large traditional Batak hall.',
            ],
            [
                'name'          => 'Hotel Grand Mercure',
                'image'         => null,
                'address'       => 'Jl. Gatot Subroto No. 45, Medan',
                'capacity'      => 500,
                'type'          => 'Hotel Ballroom',
                'facilities'    => 'AC, Catering, Hotel Rooms',
                'contact_phone' => '+62 811 2345 6789',
                'description'   => 'Hotel ballroom.',
            ],
            [
                'name'          => 'Taman Huta Garden',
                'image'         => null,
                'address'       => 'Jl. Thamrin No. 67, Medan',
                'capacity'      => 250,
                'type'          => 'Outdoor Garden',
                'facilities'    => 'Garden, Parking',
                'contact_phone' => '+62 813 4567 8901',
                'description'   => 'Garden venue for intimate gatherings.',
            ],
        ]);

        $db->table('clients')->insertBatch([
            [
                'bride_name' => 'Sarah Sitompul',
                'groom_name' => 'John Sitompul',
                'phone'      => '+62 812 1111 2222',
                'notes'      => null,
            ],
            [
                'bride_name' => 'Maria Simanjuntak',
                'groom_name' => 'David Simanjuntak',
                'phone'      => '+62 812 2222 3333',
                'notes'      => null,
            ],
        ]);

        $db->table('events')->insertBatch([
            [
                'name'        => 'Pesta Adat Batak Toba',
                'client_id'   => 1,
                'location_id' => 1,
                'event_type'  => 'Traditional Wedding',
                'event_date'  => date('Y-m-d', strtotime('+30 days')),
                'event_time'  => '10:00:00',
                'guests'      => 500,
                'budget'      => 150_000_000,
                'description' => 'Traditional Batak Toba wedding ceremony.',
                'status'      => 'pending',
                'created_by'  => 1,
            ],
            [
                'name'        => 'Mangain dan Martupol',
                'client_id'   => 2,
                'location_id' => 3,
                'event_type'  => 'Engagement',
                'event_date'  => date('Y-m-d', strtotime('+45 days')),
                'event_time'  => '08:00:00',
                'guests'      => 200,
                'budget'      => 75_000_000,
                'description' => 'Batak engagement ceremony.',
                'status'      => 'active',
                'created_by'  => 1,
            ],
        ]);

        echo "Dummy data loaded. Both accounts use password: admin123\n";
        echo "  admin@batakwedding.com (admin)  |  staff@batakwedding.com (staff)\n";
    }
}
