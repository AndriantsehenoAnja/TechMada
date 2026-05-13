<?php

namespace App\Models;

use CodeIgniter\Model;

class TypeCongeModel extends Model
{
    protected $table = 'types_conge';

    protected $primaryKey = 'id';

    protected $returnType = 'array';

    protected $allowedFields = [
        'libelle',
        'jours_annuels',
        'deductible'
    ];

    protected $validationRules = [

        'libelle' => [
            'rules' => 'required|min_length[2]',
            'errors' => [
                'required' => 'Le libellé est obligatoire'
            ]
        ],

        'jours_annuels' => [
            'rules' => 'required|integer',
            'errors' => [
                'required' => 'Le nombre de jours annuels est obligatoire'
            ]
        ],

        'deductible' => [
            'rules' => 'required|in_list[0,1]',
            'errors' => [
                'required' => 'Le champ deductible est obligatoire',
                'in_list' => 'Valeur invalide'
            ]
        ]
    ];
}