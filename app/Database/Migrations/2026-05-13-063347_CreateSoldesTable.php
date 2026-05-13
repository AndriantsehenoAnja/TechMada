<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateSoldesTable extends Migration
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
            'employe_id' => [
                'type' => 'INTEGER',
                'constraint' => 11,
                'unsigned' => true,
                'null' => false,
            ],
            'type_conge_id' => [
                'type' => 'INTEGER',
                'constraint' => 11,
                'unsigned' => true,
                'null' => false,
            ],
            'annee' => [
                'type' => 'INTEGER',
                'constraint' => 11,
                'null' => false,
            ],
            'jours_attribues' => [
                'type' => 'REAL',
                'default' => 0,
                'null' => false,
            ],
            'jours_pris' => [
                'type' => 'REAL',
                'default' => 0,
                'null' => false,
            ],
        ]);
        
        $this->forge->addKey('id', true);
        $this->forge->addKey('employe_id', false, false, 'idx_soldes_employe');
        $this->forge->addUniqueKey(['employe_id', 'type_conge_id', 'annee']);
        
        $this->forge->addForeignKey('employe_id', 'employes', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('type_conge_id', 'types_conge', 'id', 'CASCADE', 'CASCADE');
        
        $this->forge->createTable('soldes');
    }

    public function down()
    {
        $this->forge->dropTable('soldes');
    }
}