<?php

namespace App\Models;

use CodeIgniter\Model;

class EmployeModel extends Model
{
    protected $table = 'employes';

    protected $primaryKey = 'id';

    protected $returnType = 'array';

    protected $allowedFields = [
        'nom',
        'prenom',
        'email',
        'password',
        'role',
        'departement_id',
        'date_embauche',
        'actif'
    ];

    protected $validationRules = [

        'nom' => [
            'rules' => 'required|min_length[2]',
            'errors' => [
                'required' => 'Le nom est obligatoire'
            ]
        ],

        'prenom' => [
            'rules' => 'required|min_length[2]',
            'errors' => [
                'required' => 'Le prénom est obligatoire'
            ]
        ],

        'email' => [
            'rules' => 'required|valid_email|is_unique[employes.email,id,{id}]',
            'errors' => [
                'required' => 'L’email est obligatoire',
                'valid_email' => 'Email invalide',
                'is_unique' => 'Cet email existe déjà'
            ]
        ],

        'password' => [
            'rules' => 'required|min_length[6]',
            'errors' => [
                'required' => 'Le mot de passe est obligatoire'
            ]
        ],

        'role' => [
            'rules' => 'required',
            'errors' => [
                'required' => 'Le rôle est obligatoire'
            ]
        ],

        'departement_id' => [
            'rules' => 'permit_empty|integer',
            'errors' => [
                'integer' => 'Département invalide'
            ]
        ],

        'actif' => [
            'rules' => 'required|in_list[0,1]',
            'errors' => [
                'required' => 'Le statut actif est obligatoire'
            ]
        ]
    ];
}