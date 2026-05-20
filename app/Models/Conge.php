<?php

namespace App\Models;

use CodeIgniter\Model;

class Conge extends Model
{
    protected $table      = 'conges';
    protected $primaryKey = 'id';

    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;

    protected $allowedFields = ['employe_id', 'type_conge_id', 'date_debut', 'date_fin', 'nb_jours', 'motif', 'statut', 'commentaire_rh', 'traite_par'];

    protected bool $allowEmptyInserts = false;

    /**
     * Récupérer toutes les demandes en attente
     */
    public function getPendingDemandes()
    {
        return $this->where('statut', 'en_attente')
                    ->orderBy('created_at', 'DESC')
                    ->findAll();
    }

    /**
     * Récupérer les demandes en attente avec infos employé et type de congé
     */
    public function getPendingDemandesWithDetails()
    {
        return $this->select('conges.*, employes.nom, employes.prenom, employes.departement_id, types_conge.libelle as type_conge_libelle')
                    ->join('employes', 'employes.id = conges.employe_id')
                    ->join('types_conge', 'types_conge.id = conges.type_conge_id')
                    ->where('conges.statut', 'en_attente')
                    ->orderBy('conges.created_at', 'DESC')
                    ->findAll();
    }

    /**
     * Récupérer les demandes filtrées par département
     */
    public function getPendingByDepartement($departement_id)
    {
        return $this->select('conges.*, employes.nom, employes.prenom, employes.departement_id, types_conge.libelle as type_conge_libelle')
                    ->join('employes', 'employes.id = conges.employe_id')
                    ->join('types_conge', 'types_conge.id = conges.type_conge_id')
                    ->where('conges.statut', 'en_attente')
                    ->where('employes.departement_id', $departement_id)
                    ->orderBy('conges.created_at', 'DESC')
                    ->findAll();
    }

    /**
     * Récupérer les demandes filtrées par statut
     */
    public function getByStatut($statut)
    {
        return $this->select('conges.*, employes.nom, employes.prenom, employes.departement_id, types_conge.libelle as type_conge_libelle')
                    ->join('employes', 'employes.id = conges.employe_id')
                    ->join('types_conge', 'types_conge.id = conges.type_conge_id')
                    ->where('conges.statut', $statut)
                    ->orderBy('conges.created_at', 'DESC')
                    ->findAll();
    }

    /**
     * Récupérer toutes les demandes (tous statuts) avec infos employé et type de congé
     */
    public function getAllDemandesWithDetails()
    {
        return $this->select('conges.*, employes.nom, employes.prenom, employes.departement_id, types_conge.libelle as type_conge_libelle')
                    ->join('employes', 'employes.id = conges.employe_id')
                    ->join('types_conge', 'types_conge.id = conges.type_conge_id')
                    ->orderBy('conges.created_at', 'DESC')
                    ->findAll();
    }

    /**
     * Approuver une demande
     */
    public function approveDemande($id, $traiteParId)
    {
        return $this->update($id, [
            'statut' => 'approuvee',
            'traite_par' => $traiteParId
        ]);
    }

    /**
     * Refuser une demande
     */
    public function refuseDemande($id, $traiteParId, $commentaire = '')
    {
        return $this->update($id, [
            'statut' => 'refusee',
            'traite_par' => $traiteParId,
            'commentaire_rh' => $commentaire
        ]);
    }
}
