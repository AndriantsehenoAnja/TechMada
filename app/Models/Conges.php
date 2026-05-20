<?php
namespace App\Models;
use CodeIgniter\Model;
use Override;

class Conges extends Model
{
    protected $table = 'conges';
    protected $primaryKey = 'id';
    protected $allowedFields = ['id', 'employe_id', 'type_conge_id', 'date_debut', 'date_fin', 'nb_jours', 'motif', 'statut', 'commentaire_rh', 'created_at', 'traite_par'];

    public function findAllwithdetails(int $limit = 0, int $offset = 0)
    {
       $db = \Config\Database::connect();
       $builder = $db->table($this->table);
       $builder->select('conges.*, employes.nom as nom_employe, employes.prenom as prenom_employe, types_conge.libelle as type_conge');
       $builder->join('employes', 'employes.id = conges.employe_id');
       $builder->join('types_conge', 'types_conge.id = conges.type_conge_id');
       
       if ($limit > 0) {
           $builder->limit($limit, $offset);
       }
       
       return $builder->get()->getResultArray();
    }
    // uytre
    #[Override]
    public function find($id = null)
    {
        $db = \Config\Database::connect();
        $builder = $db->table($this->table);
        $builder->select('conges.*, employes.nom as nom_employe, employes.prenom as prenom_employe, types_conge.libelle as type_conge');
        $builder->join('employes', 'employes.id = conges.employe_id');
        $builder->join('types_conge', 'types_conge.id = conges.type_conge_id');
        
        if ($id === null) {
            return $builder->get()->getResultArray();
        }
        return $builder->where('conges.id', $id)->get()->getRow();
    }
    
    public function delete($id = null, bool $purge = false)
    {
        $db = \Config\Database::connect();
        $builder = $db->table($this->table);
        return $builder->delete(['id' => $id]);
    }
}