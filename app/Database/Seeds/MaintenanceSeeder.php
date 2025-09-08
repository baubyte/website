<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class MaintenanceSeeder extends Seeder
{
    public function run()
    {
        $data = [
            'status' => 0, // 1 for maintenance mode enabled, 0 for disabled
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ];

        // Insert the data into the maintenances table
        $this->db->table('maintenance')->insert($data);
    }
}
