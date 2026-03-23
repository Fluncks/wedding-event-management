<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

/**
 * Clients: single name → bride_name + groom_name.
 * Locations: optional image filename (stored under public/uploads/locations/).
 */
class ClientBrideGroomLocationImage extends Migration
{
    public function up()
    {
        $db = $this->db;
        $db->disableForeignKeyChecks();

        $clientFields = $db->getFieldNames('clients');

        if (in_array('name', $clientFields, true) && ! in_array('bride_name', $clientFields, true)) {
            $this->forge->addColumn('clients', [
                'bride_name' => [
                    'type'       => 'VARCHAR',
                    'constraint' => 200,
                    'null'       => true,
                ],
                'groom_name' => [
                    'type'       => 'VARCHAR',
                    'constraint' => 200,
                    'null'       => true,
                ],
            ]);
            $db->query('UPDATE clients SET bride_name = name, groom_name = ""');
            $db->query('ALTER TABLE clients MODIFY bride_name VARCHAR(200) NOT NULL');
            $db->query('ALTER TABLE clients MODIFY groom_name VARCHAR(200) NOT NULL DEFAULT ""');
            $this->forge->dropColumn('clients', 'name');
        }

        $locFields = $db->getFieldNames('locations');
        if (! in_array('image', $locFields, true)) {
            $this->forge->addColumn('locations', [
                'image' => [
                    'type'       => 'VARCHAR',
                    'constraint' => 255,
                    'null'       => true,
                ],
            ]);
        }

        $db->enableForeignKeyChecks();
    }

    public function down()
    {
        $db = $this->db;
        $db->disableForeignKeyChecks();

        $clientFields = $db->getFieldNames('clients');
        if (in_array('bride_name', $clientFields, true) && ! in_array('name', $clientFields, true)) {
            $this->forge->addColumn('clients', [
                'name' => [
                    'type'       => 'VARCHAR',
                    'constraint' => 200,
                    'null'       => true,
                ],
            ]);
            $db->query('UPDATE clients SET name = CONCAT(bride_name, " & ", groom_name)');
            $db->query('ALTER TABLE clients MODIFY name VARCHAR(200) NOT NULL');
            $this->forge->dropColumn('clients', 'bride_name');
            $this->forge->dropColumn('clients', 'groom_name');
        }

        $locFields = $db->getFieldNames('locations');
        if (in_array('image', $locFields, true)) {
            $this->forge->dropColumn('locations', 'image');
        }

        $db->enableForeignKeyChecks();
    }
}
