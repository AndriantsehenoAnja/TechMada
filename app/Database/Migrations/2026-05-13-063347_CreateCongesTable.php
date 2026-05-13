<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateCongesTable extends Migration
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
            'date_debut' => [
                'type' => 'DATE',
                'null' => false,
            ],
            'date_fin' => [
                'type' => 'DATE',
                'null' => false,
            ],
            'nb_jours' => [
                'type' => 'REAL',
                'null' => false,
            ],
            'motif' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'status' => [
                'type' => 'TEXT',
                'default' => 'en_attente',
                'null' => false,
            ],
            'commentaire_rh' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => true,
                'default' => null,
            ],
            'traite_par' => [
                'type' => 'INTEGER',
                'constraint' => 11,
                'unsigned' => true,
                'null' => true,
            ],
        ]);
        
        $this->forge->addKey('id', true);
        $this->forge->addKey('employe_id', false, false, 'idx_conges_employe');
        $this->forge->addKey('status', false, false, 'idx_conges_status');
        
        $this->forge->addForeignKey('employe_id', 'employes', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('type_conge_id', 'types_conge', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('traite_par', 'employes', 'id', 'SET NULL', 'CASCADE');
        
        $this->forge->createTable('conges');
    }

    public function down()
    {
        $this->forge->dropTable('conges');
    }
}