<?php

namespace App\Models;

use CodeIgniter\Model;

class CongeModel extends Model
{
    protected $table = 'conges';

    protected $primaryKey = 'id';

    protected $returnType = 'array';

    protected $allowedFields = [
        'employe_id',
        'type_conge_id',
        'date_debut',
        'date_fin',
        'nb_jours',
        'motif',
        'status',
        'commentaire_rh',
        'created_at',
        'traite_par'
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

        'date_debut' => [
            'rules' => 'required|valid_date',
            'errors' => [
                'required' => 'La date de début est obligatoire'
            ]
        ],

        'date_fin' => [
            'rules' => 'required|valid_date',
            'errors' => [
                'required' => 'La date de fin est obligatoire'
            ]
        ],

        'nb_jours' => [
            'rules' => 'required|decimal',
            'errors' => [
                'required' => 'Le nombre de jours est obligatoire'
            ]
        ],

        'status' => [
            'rules' => 'required|in_list[en_attente,approuve,refuse,annule]',
            'errors' => [
                'required' => 'Le statut est obligatoire',
                'in_list' => 'Statut invalide'
            ]
        ],

        'traite_par' => [
            'rules' => 'permit_empty|integer',
            'errors' => [
                'integer' => 'Utilisateur invalide'
            ]
        ]
    ];
}