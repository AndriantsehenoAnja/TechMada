<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateTypesCongeTable extends Migration
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
            'libelle' => [
                'type' => 'TEXT',
                'null' => false,
            ],
            'jours_annuels' => [
                'type' => 'INTEGER',
                'constraint' => 11,
                'default' => 0,
                'null' => false,
            ],
            'deductible' => [
                'type' => 'INTEGER',
                'constraint' => 1,
                'default' => 1,
                'null' => false,
                'comment' => '0 = non déductible, 1 = déductible',
            ],
        ]);
        
        $this->forge->addKey('id', true);
        $this->forge->createTable('types_conge');
    }

    public function down()
    {
        $this->forge->dropTable('types_conge');
    }
}