<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class EnableForeignKeys extends Seeder
{
    public function run()
    {
        $this->db->query('PRAGMA foreign_keys = ON');
    }
}