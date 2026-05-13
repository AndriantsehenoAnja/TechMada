<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateDepartementsTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type' => 'INTEGER',
                'constraint' => 11,
                'unsigned' => true,
                'auto_increment' => true,
            ],
            'nom' => [
                'type' => 'TEXT',
                'null' => false,
            ],
            'description' => [
                'type' => 'TEXT',
                'null' => true,
            ],
        ]);
        
        $this->forge->addKey('id', true);
        $this->forge->createTable('departements');
    }

    public function down()
    {
        $this->forge->dropTable('departements');
    }
}