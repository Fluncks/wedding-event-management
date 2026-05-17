<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

/**
 * TestSeeder — used exclusively by the test suite.
 *
 * Seeds only the parent tables (clients, locations) that events depend on
 * via foreign keys. Tests insert their own event rows as needed.
 *
 * Execution order matters:
 *   1. clients    (no FK deps)
 *   2. locations  (no FK deps)
 *   — events are NOT seeded here; each test inserts its own rows —
 */
class TestSeeder extends Seeder
{
    public function run(): void
    {
        $now = date('Y-m-d H:i:s');

        // ── clients ───────────────────────────────────────────────────────
        $this->db->table('clients')->insertBatch([
            [
                'bride_name' => 'Test Bride One',
                'groom_name' => 'Test Groom One',
                'phone'      => '081300000001',
                'notes'      => 'Test client for automated tests',
                'created_at' => $now,
            ],
            [
                'bride_name' => 'Test Bride Two',
                'groom_name' => 'Test Groom Two',
                'phone'      => '081300000002',
                'notes'      => 'Second test client',
                'created_at' => $now,
            ],
        ]);

        // ── locations ─────────────────────────────────────────────────────
        $this->db->table('locations')->insertBatch([
            [
                'name'       => 'Test Venue One',
                'address'    => 'Jl. Test Satu No. 1, Jakarta',
                'capacity'   => 200,
                'type'       => 'Indoor',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'name'       => 'Test Venue Two',
                'address'    => 'Jl. Test Dua No. 2, Bandung',
                'capacity'   => 300,
                'type'       => 'Outdoor',
                'created_at' => $now,
                'updated_at' => $now,
            ],
        ]);
    }
}
