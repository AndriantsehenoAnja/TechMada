<?php

namespace App\Models;

use CodeIgniter\Model;

class SoldeModel extends Model
{
    protected $table = 'soldes';

    protected $primaryKey = 'id';

    protected $returnType = 'array';

    protected $allowedFields = [
        'employe_id',
        'type_conge_id',
        'annee',
        'jours_attribues',
        'jours_pris'
    ];

    protected $validationRules = [

        'employe_id' => [
            'rules' => 'required|integer',
            'errors' => [
                'required' => 'Employé obligatoire'
            ]
        ],

        'type_conge_id' => [
            'rules' => 'required|integer',
            'errors' => [
                'required' => 'Type de congé obligatoire'
            ]
        ],

        'annee' => [
            'rules' => 'required|integer',
            'errors' => [
                'required' => 'Année obligatoire'
            ]
        ],

        'jours_attribues' => [
            'rules' => 'required|decimal',
            'errors' => [
                'required' => 'Les jours attribués sont obligatoires'
            ]
        ],

        'jours_pris' => [
            'rules' => 'required|decimal',
            'errors' => [
                'required' => 'Les jours pris sont obligatoires'
            ]
        ]
    ];
}