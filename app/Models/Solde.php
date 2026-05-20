<?php

namespace App\Models;

use CodeIgniter\Model;

class Solde extends Model
{
    protected $table      = 'soldes';
    protected $primaryKey = 'id';

    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;

    protected $allowedFields = ['employe_id', 'type_conge_id', 'annee', 'jours_attribues', 'jours_pris'];

    protected bool $allowEmptyInserts = false;

    /**
     * Récupérer le solde d'un employé pour une année
     */
    public function getSoldeEmploye($employe_id, $annee = null)
    {
        if (!$annee) {
            $annee = date('Y');
        }

        return $this->select('soldes.*, types_conge.libelle as type_conge_libelle')
                    ->join('types_conge', 'types_conge.id = soldes.type_conge_id')
                    ->where('soldes.employe_id', $employe_id)
                    ->where('soldes.annee', $annee)
                    ->findAll();
    }

    /**
     * Mettre à jour les jours pris
     */
    public function updateJoursPris($employe_id, $type_conge_id, $annee, $nb_jours)
    {
        $solde = $this->where('employe_id', $employe_id)
                      ->where('type_conge_id', $type_conge_id)
                      ->where('annee', $annee)
                      ->first();

        if ($solde) {
            return $this->update($solde['id'], [
                'jours_pris' => $solde['jours_pris'] + $nb_jours
            ]);
        }

        return false;
    }

    /**
     * Récupérer les soldes de tous les employés pour une année
     */
    public function getAllSoldes($annee = null)
    {
        if (!$annee) {
            $annee = date('Y');
        }

        return $this->select('soldes.*, employes.nom, employes.prenom, employes.departement_id, types_conge.libelle as type_conge_libelle')
                    ->join('employes', 'employes.id = soldes.employe_id')
                    ->join('types_conge', 'types_conge.id = soldes.type_conge_id')
                    ->where('soldes.annee', $annee)
                    ->orderBy('employes.nom', 'ASC')
                    ->findAll();
    }
}
