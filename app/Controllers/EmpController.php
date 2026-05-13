<?php

namespace App\Controllers;

use App\Models\EmployeModel;
use App\Models\CongeModel;
use App\Models\SoldeModel;
use App\Models\TypeCongeModel;
use App\Models\DepartementModel;

class EmpController extends BaseController
{
    private $employeModel;
    private $congeModel;
    private $soldeModel;
    private $typeCongeModel;
    private $departementModel;
    
    public function __construct()
    {
        // Vérifier que l'utilisateur est connecté
        if (!session()->get('isLoggedIn')) {
            redirect()->to('/auth/login')->with('error', 'Veuillez vous connecter')->send();
            exit();
        }
        
        // Vérifier que l'utilisateur a le rôle 'employe'
        if (session()->get('role') !== 'employe') {
            redirect()->to('/dashboard')->with('error', 'Accès non autorisé')->send();
            exit();
        }
        
        $this->employeModel = new EmployeModel();
        $this->congeModel = new CongeModel();
        $this->soldeModel = new SoldeModel();
        $this->typeCongeModel = new TypeCongeModel();
        $this->departementModel = new DepartementModel();
    }
    
    /**
     * Dashboard employé - Page d'accueil
     */
    public function index(): string
    {
        $employe = session()->get('employe');
        $userId = $employe['id'];
        $currentYear = date('Y');
        
        // Récupérer l'employé avec son département
        $employe = $this->employeModel
            ->select('employes.*, departements.nom as departement_nom')
            ->join('departements', 'departements.id = employes.departement_id', 'left')
            ->find($userId);
        
        // Récupérer toutes les demandes de congé avec les types
        $allConges = $this->congeModel
            ->select('conges.*, types_conge.libelle as type_libelle, types_conge.deductible')
            ->join('types_conge', 'types_conge.id = conges.type_conge_id')
            ->where('employe_id', $userId)
            ->orderBy('created_at', 'DESC')
            ->findAll();
        
        // Récupérer les 5 dernières demandes
        $recentConges = array_slice($allConges, 0, 5);
        
        // Calculer les métriques
        $enAttente = $this->congeModel
            ->where('employe_id', $userId)
            ->where('status', 'en_attente')
            ->countAllResults();
        
        $approuves = $this->congeModel
            ->where('employe_id', $userId)
            ->where('status', 'approuve')
            ->countAllResults();
        
        $refuses = $this->congeModel
            ->where('employe_id', $userId)
            ->where('status', 'refuse')
            ->countAllResults();
        
        // Récupérer les soldes pour l'année courante
        $soldes = $this->soldeModel
            ->select('soldes.*, types_conge.libelle, types_conge.jours_annuels, types_conge.deductible')
            ->join('types_conge', 'types_conge.id = soldes.type_conge_id')
            ->where('employe_id', $userId)
            ->where('annee', $currentYear)
            ->findAll();
        
        // Si aucun solde n'existe pour l'année, créer les soldes par défaut
        if (empty($soldes)) {
            $this->initSoldesForEmploye($userId, $currentYear);
            // Recharger les soldes
            $soldes = $this->soldeModel
                ->select('soldes.*, types_conge.libelle, types_conge.jours_annuels, types_conge.deductible')
                ->join('types_conge', 'types_conge.id = soldes.type_conge_id')
                ->where('employe_id', $userId)
                ->where('annee', $currentYear)
                ->findAll();
        }
        
        // Calculer le total des jours restants
        $totalJoursRestants = 0;
        foreach ($soldes as $solde) {
            $totalJoursRestants += ($solde['jours_attribues'] - $solde['jours_pris']);
        }
        
        $data = [
            'title' => 'TechMada RH - Espace employé',
            'employe' => $employe,
            'recentConges' => $recentConges,
            'allConges' => $allConges,
            'enAttente' => $enAttente,
            'approuves' => $approuves,
            'refuses' => $refuses,
            'soldes' => $soldes,
            'totalJoursRestants' => $totalJoursRestants,
            'currentYear' => $currentYear,
            'userInitials' => $this->getInitials($employe['prenom'] . ' ' . $employe['nom']),
            'userName' => $employe['prenom'] . ' ' . $employe['nom']
        ];
        
        return view('employe/index', $data);
    }
    
    /**
     * Formulaire de demande de congé
     */
    public function formconge(): string
    {
        $userId = session()->get('user_id');
        
        // Récupérer les types de congés disponibles
        $typesConges = $this->typeCongeModel->findAll();
        
        // Récupérer les soldes restants pour l'année en cours
        $currentYear = date('Y');
        $soldes = $this->soldeModel
            ->select('soldes.*, types_conge.libelle')
            ->join('types_conge', 'types_conge.id = soldes.type_conge_id')
            ->where('employe_id', $userId)
            ->where('annee', $currentYear)
            ->findAll();
        
        // Créer un tableau des jours restants par type de congé
        $joursRestants = [];
        foreach ($soldes as $solde) {
            $joursRestants[$solde['type_conge_id']] = $solde['jours_attribues'] - $solde['jours_pris'];
        }
        
        $data = [
            'title' => 'Nouvelle demande de congé',
            'typesConges' => $typesConges,
            'joursRestants' => $joursRestants
        ];
        
        return view('employe/create', $data);
    }
    
    /**
     * Soumettre une demande de congé
     */
    public function submitConge()
    {
        $userId = session()->get('user_id');
        $currentYear = date('Y');
        
        // Validation des données
        $rules = [
            'type_conge_id' => 'required|integer',
            'date_debut' => 'required|valid_date',
            'date_fin' => 'required|valid_date',
            'motif' => 'permit_empty|max_length[500]'
        ];
        
        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }
        
        $typeCongeId = $this->request->getPost('type_conge_id');
        $dateDebut = $this->request->getPost('date_debut');
        $dateFin = $this->request->getPost('date_fin');
        $motif = $this->request->getPost('motif');
        
        // Calculer le nombre de jours ouvrés
        $nbJours = $this->calculateWorkingDays($dateDebut, $dateFin);
        
        if ($nbJours <= 0) {
            return redirect()->back()->withInput()->with('error', 'La date de fin doit être postérieure à la date de début');
        }
        
        // Vérifier le solde disponible
        $solde = $this->soldeModel
            ->where('employe_id', $userId)
            ->where('type_conge_id', $typeCongeId)
            ->where('annee', $currentYear)
            ->first();
        
        // Récupérer le type de congé pour savoir s'il est déductible
        $typeConge = $this->typeCongeModel->find($typeCongeId);
        
        if ($typeConge && $typeConge['deductible'] == 1) {
            $joursRestants = ($solde['jours_attribues'] ?? 0) - ($solde['jours_pris'] ?? 0);
            
            if ($nbJours > $joursRestants) {
                return redirect()->back()->withInput()->with('error', 'Solde insuffisant. Jours restants : ' . $joursRestants);
            }
        }
        
        // Vérifier les chevauchements de congés
        $overlap = $this->congeModel
            ->where('employe_id', $userId)
            ->where('status !=', 'refuse')
            ->where('status !=', 'annule')
            ->groupStart()
                ->where('date_debut <=', $dateFin)
                ->where('date_fin >=', $dateDebut)
            ->groupEnd()
            ->first();
        
        if ($overlap) {
            return redirect()->back()->withInput()->with('error', 'Vous avez déjà une demande de congé sur cette période');
        }
        
        // Créer la demande de congé
        $data = [
            'employe_id' => $userId,
            'type_conge_id' => $typeCongeId,
            'date_debut' => $dateDebut,
            'date_fin' => $dateFin,
            'nb_jours' => $nbJours,
            'motif' => $motif,
            'status' => 'en_attente'
        ];
        
        if ($this->congeModel->insert($data)) {
            return redirect()->to('/emp/index')->with('success', 'Votre demande de congé a bien été soumise');
        } else {
            return redirect()->back()->withInput()->with('errors', $this->congeModel->errors());
        }
    }
    
    /**
     * Liste des demandes de congé de l'employé
     */
    public function demandeconge(): string
    {
        $userId = session()->get('user_id');
        
        // Récupérer toutes les demandes avec filtres optionnels
        $status = $this->request->getGet('status');
        
        $builder = $this->congeModel
            ->select('conges.*, types_conge.libelle as type_libelle, types_conge.deductible')
            ->join('types_conge', 'types_conge.id = conges.type_conge_id')
            ->where('employe_id', $userId);
        
        if ($status && in_array($status, ['en_attente', 'approuve', 'refuse', 'annule'])) {
            $builder->where('status', $status);
        }
        
        $demandes = $builder->orderBy('created_at', 'DESC')->findAll();
        
        // Calculer les statistiques
        $stats = [
            'total' => count($demandes),
            'en_attente' => $this->congeModel->where('employe_id', $userId)->where('status', 'en_attente')->countAllResults(),
            'approuves' => $this->congeModel->where('employe_id', $userId)->where('status', 'approuve')->countAllResults(),
            'refuses' => $this->congeModel->where('employe_id', $userId)->where('status', 'refuse')->countAllResults()
        ];
        
        $data = [
            'title' => 'Mes demandes de congé',
            'demandes' => $demandes,
            'stats' => $stats,
            'currentFilter' => $status
        ];
        
        return view('employe/demandeconge', $data);
    }
    
    /**
     * Annuler une demande de congé (seulement si en attente)
     */
    public function annuler($id)
    {
        $userId = session()->get('user_id');
        
        // Vérifier que la demande existe et appartient à l'employé
        $conge = $this->congeModel->where('id', $id)->where('employe_id', $userId)->first();
        
        if (!$conge) {
            return redirect()->back()->with('error', 'Demande non trouvée');
        }
        
        // Vérifier que la demande est en attente
        if ($conge['status'] !== 'en_attente') {
            return redirect()->back()->with('error', 'Seules les demandes en attente peuvent être annulées');
        }
        
        // Annuler la demande
        if ($this->congeModel->update($id, ['status' => 'annule'])) {
            return redirect()->back()->with('success', 'Demande annulée avec succès');
        } else {
            return redirect()->back()->with('error', 'Erreur lors de l\'annulation');
        }
    }
    
    /**
     * Voir le détail d'une demande
     */
    public function detail($id)
    {
        $userId = session()->get('user_id');
        
        $conge = $this->congeModel
            ->select('conges.*, types_conge.libelle as type_libelle, types_conge.deductible, types_conge.jours_annuels')
            ->join('types_conge', 'types_conge.id = conges.type_conge_id')
            ->where('conges.id', $id)
            ->where('employe_id', $userId)
            ->first();
        
        if (!$conge) {
            return redirect()->to('/emp/demandeconge')->with('error', 'Demande non trouvée');
        }
        
        $data = [
            'title' => 'Détail de la demande',
            'conge' => $conge
        ];
        
        return view('employe/detail', $data);
    }
    
    /**
     * Voir le solde de congés
     */
    public function solde()
    {
        $userId = session()->get('user_id');
        $currentYear = date('Y');
        $years = [$currentYear - 1, $currentYear, $currentYear + 1];
        
        $selectedYear = $this->request->getGet('year') ?? $currentYear;
        
        // Récupérer les soldes pour l'année sélectionnée
        $soldes = $this->soldeModel
            ->select('soldes.*, types_conge.libelle, types_conge.jours_annuels, types_conge.deductible')
            ->join('types_conge', 'types_conge.id = soldes.type_conge_id')
            ->where('employe_id', $userId)
            ->where('annee', $selectedYear)
            ->findAll();
        
        // Si aucun solde n'existe, initialiser
        if (empty($soldes) && $selectedYear == $currentYear) {
            $this->initSoldesForEmploye($userId, $selectedYear);
            $soldes = $this->soldeModel
                ->select('soldes.*, types_conge.libelle, types_conge.jours_annuels, types_conge.deductible')
                ->join('types_conge', 'types_conge.id = soldes.type_conge_id')
                ->where('employe_id', $userId)
                ->where('annee', $selectedYear)
                ->findAll();
        }
        
        $data = [
            'title' => 'Mon solde de congés',
            'soldes' => $soldes,
            'currentYear' => $selectedYear,
            'years' => $years
        ];
        
        return view('employe/solde', $data);
    }
    
    /**
     * Modifier le profil
     */
    public function profil()
    {
        $userId = session()->get('user_id');
        
        $employe = $this->employeModel->find($userId);
        $departements = $this->departementModel->findAll();
        
        $data = [
            'title' => 'Mon profil',
            'employe' => $employe,
            'departements' => $departements
        ];
        
        return view('employe/profil', $data);
    }
    
    /**
     * Mettre à jour le profil
     */
    public function updateProfil()
    {
        $userId = session()->get('user_id');
        
        $rules = [
            'nom' => 'required|min_length[2]',
            'prenom' => 'required|min_length[2]',
            'email' => 'required|valid_email|is_unique[employes.email,id,' . $userId . ']'
        ];
        
        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }
        
        $data = [
            'nom' => $this->request->getPost('nom'),
            'prenom' => $this->request->getPost('prenom'),
            'email' => $this->request->getPost('email')
        ];
        
        if ($this->employeModel->update($userId, $data)) {
            // Mettre à jour la session
            session()->set([
                'nom' => $data['nom'],
                'prenom' => $data['prenom'],
                'email' => $data['email']
            ]);
            
            return redirect()->back()->with('success', 'Profil mis à jour avec succès');
        } else {
            return redirect()->back()->with('errors', $this->employeModel->errors());
        }
    }
       
    /**
     * Initialiser les soldes pour un employé
     */
    private function initSoldesForEmploye($employeId, $annee)
    {
        // Récupérer tous les types de congés
        $typesConges = $this->typeCongeModel->findAll();
        
        foreach ($typesConges as $type) {
            // Vérifier si le solde existe déjà
            $exists = $this->soldeModel
                ->where('employe_id', $employeId)
                ->where('type_conge_id', $type['id'])
                ->where('annee', $annee)
                ->first();
            
            if (!$exists) {
                $this->soldeModel->insert([
                    'employe_id' => $employeId,
                    'type_conge_id' => $type['id'],
                    'annee' => $annee,
                    'jours_attribues' => $type['jours_annuels'],
                    'jours_pris' => 0
                ]);
            }
        }
    }
    
    /**
     * Calculer le nombre de jours ouvrés entre deux dates
     */
    private function calculateWorkingDays($startDate, $endDate)
    {
        $start = new \DateTime($startDate);
        $end = new \DateTime($endDate);
        $end->modify('+1 day'); // Inclure le jour de fin
        
        $interval = new \DateInterval('P1D');
        $period = new \DatePeriod($start, $interval, $end);
        
        $workingDays = 0;
        foreach ($period as $date) {
            $dayOfWeek = $date->format('N');
            // Exclure les samedis (6) et dimanches (7)
            if ($dayOfWeek < 6) {
                $workingDays++;
            }
        }
        
        return $workingDays;
    }
    
    /**
     * Récupérer les initiales du nom
     */
    private function getInitials($name)
    {
        $words = explode(' ', $name);
        $initials = '';
        foreach ($words as $word) {
            if (!empty($word)) {
                $initials .= strtoupper($word[0]);
            }
        }
        return substr($initials, 0, 2);
    }
}