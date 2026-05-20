<?php

namespace App\Models;

use CodeIgniter\Model;

class Employee extends Model
{
    protected $table      = 'employes';
    protected $primaryKey = 'id';

    protected $useAutoIncrement = true;

    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;

    protected $allowedFields = ['nom', 'prenom', 'email', 'password', 'role', 'departement_id', 'date_embauche', 'actif'];

    // Hash password automatically before insert/update when using Model::save()/insert()/update()
    protected $beforeInsert = ['hashPassword'];
    protected $beforeUpdate = ['hashPassword'];

    protected bool $allowEmptyInserts = false;

    /**
     * Callback: hash password if present in data
     */
    protected function hashPassword(array $data)
    {
        if (isset($data['data']['password']) && !empty($data['data']['password'])) {
            // If password already looks like a hash, skip
            $pw = $data['data']['password'];
            if (!(str_starts_with($pw, '$2y$') || str_starts_with($pw, '$2a$') || str_starts_with($pw, '$2b$') || str_starts_with($pw, '$argon2')) ) {
                $data['data']['password'] = password_hash($pw, PASSWORD_DEFAULT);
            }
        }

        return $data;
    }

    /**
     * Trouver un employé par email
     */
    public function getByEmail($email)
    {
        return $this->where('email', $email)->first();
    }

    /**
     * Trouver un employé actif par email
     */
    public function getActiveByEmail($email)
    {
        return $this->where('email', $email)
                    ->where('actif', 1)
                    ->first();
    }

    /**
     * Trouver un employé par ID
     */
    public function getById($id)
    {
        return $this->find($id);
    }
}
