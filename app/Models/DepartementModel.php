<?php

namespace App\Models;

use CodeIgniter\Model;

class DepartementModel extends Model
{
    protected $table = 'departements';

    protected $primaryKey = 'id';

    protected $returnType = 'array';

    protected $allowedFields = [
        'nom',
        'description'
    ];

    protected $validationRules = [

        'nom' => [
            'rules' => 'required|min_length[2]',
            'errors' => [
                'required' => 'Le nom du département est obligatoire'
            ]
        ]
    ];
}