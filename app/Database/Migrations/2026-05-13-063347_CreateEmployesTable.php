<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateEmployesTable extends Migration
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
            'prenom' => [
                'type' => 'TEXT',
                'null' => false,
            ],
            'email' => [
                'type' => 'TEXT',
                'null' => false,
                'unique' => true,
            ],
            'password' => [
                'type' => 'TEXT',
                'null' => false,
            ],
            'role' => [
                'type' => 'TEXT',
                'default' => 'employe',
                'null' => false,
            ],
            'departement_id' => [
                'type' => 'INTEGER',
                'constraint' => 11,
                'unsigned' => true,
                'null' => true,
            ],
            'date_embauche' => [
                'type' => 'DATE',
                'null' => true,
            ],
            'actif' => [
                'type' => 'INTEGER',
                'constraint' => 1,
                'default' => 1,
                'null' => false,
            ],
        ]);
        
        $this->forge->addKey('id', true);
        $this->forge->addKey('departement_id', false, false, 'idx_employes_departement');
        $this->forge->addForeignKey('departement_id', 'departements', 'id', 'SET NULL', 'CASCADE');
        $this->forge->createTable('employes');
    }

    public function down()
    {
        $this->forge->dropTable('employes');
    }
}