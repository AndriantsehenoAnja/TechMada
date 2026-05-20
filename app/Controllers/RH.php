<?php

namespace App\Controllers;

use App\Models\Conge;
use App\Models\Solde;
use App\Models\Employee;
use CodeIgniter\Controller;

class RH extends Controller
{
    protected $congeModel;
    protected $soldeModel;
    protected $employeeModel;
    protected $session;

    public function __construct()
    {
        $this->congeModel = new Conge();
        $this->soldeModel = new Solde();
        $this->employeeModel = new Employee();
        $this->session = session();
    }

    /**
     * Dashboard RH - Voir toutes les demandes (tous statuts)
     */
    public function dashboard()
    {
        if (!$this->session->has('user_id') || $this->session->get('user_role') !== 'rh') {
            return redirect()->to('/login')->with('error', 'Accès refusé.');
        }

        $data = [
            'demandes' => $this->congeModel->getAllDemandesWithDetails(),
            'departements' => $this->employeeModel->distinct()->select('departement_id')->findAll(),
        ];

        return view('rh/dashboard', $data);
    }

    /**
     * Filtrer les demandes par département
     */
    public function filterByDepartement($departement_id)
    {
        if (!$this->session->has('user_id') || $this->session->get('user_role') !== 'rh') {
            return redirect()->to('/login')->with('error', 'Accès refusé.');
        }

        $data = [
            'demandes' => $this->congeModel->getPendingByDepartement($departement_id),
            'departements' => $this->employeeModel->distinct()->select('departement_id')->findAll(),
            'selected_departement' => $departement_id,
        ];

        return view('rh/dashboard', $data);
    }

    /**
     * Filtrer les demandes par statut
     */
    public function filterByStatut($statut)
    {
        if (!$this->session->has('user_id') || $this->session->get('user_role') !== 'rh') {
            return redirect()->to('/login')->with('error', 'Accès refusé.');
        }

        $data = [
            'demandes' => $this->congeModel->getByStatut($statut),
            'departements' => $this->employeeModel->distinct()->select('departement_id')->findAll(),
            'selected_statut' => $statut,
        ];

        return view('rh/dashboard', $data);
    }

    /**
     * Approuver une demande de congé
     */
    public function approveDemande($id)
    {
        if (!$this->request->is('post')) {
            return redirect()->back();
        }

        if (!$this->session->has('user_id') || $this->session->get('user_role') !== 'rh') {
            return redirect()->to('/login')->with('error', 'Accès refusé.');
        }

        $demande = $this->congeModel->find($id);

        if (!$demande) {
            return redirect()->back()->with('error', 'Demande introuvable.');
        }

        // Approuver la demande
        $this->congeModel->approveDemande($id, $this->session->get('user_id'));

        // Mettre à jour automatiquement le solde
        $this->soldeModel->updateJoursPris(
            $demande['employe_id'],
            $demande['type_conge_id'],
            2025,
            $demande['nb_jours']
        );

        return redirect()->back()->with('success', 'Demande approuvée et solde mis à jour.');
    }

    /**
     * Refuser une demande de congé
     */
    public function refuseDemande($id)
    {
        if (!$this->request->is('post')) {
            return redirect()->back();
        }

        if (!$this->session->has('user_id') || $this->session->get('user_role') !== 'rh') {
            return redirect()->to('/login')->with('error', 'Accès refusé.');
        }

        $demande = $this->congeModel->find($id);

        if (!$demande) {
            return redirect()->back()->with('error', 'Demande introuvable.');
        }

        $commentaire = $this->request->getPost('commentaire') ?? '';

        // Refuser la demande
        $this->congeModel->refuseDemande($id, $this->session->get('user_id'), $commentaire);

        return redirect()->back()->with('success', 'Demande refusée.');
    }

    /**
     * Voir les soldes de tous les employés
     */
    public function soldes()
    {
        if (!$this->session->has('user_id') || $this->session->get('user_role') !== 'rh') {
            return redirect()->to('/login')->with('error', 'Accès refusé.');
        }

        $data = [
            'soldes' => $this->soldeModel->getAllSoldes(2025),
        ];

        return view('rh/soldes', $data);
    }

    /**
     * Voir les détails des demandes d'un employé
     */
    public function demandesEmploye($employe_id)
    {
        if (!$this->session->has('user_id') || $this->session->get('user_role') !== 'rh') {
            return redirect()->to('/login')->with('error', 'Accès refusé.');
        }

        $employee = $this->employeeModel->find($employe_id);
        if (!$employee) {
            return redirect()->back()->with('error', 'Employé introuvable.');
        }

        $data = [
            'employee' => $employee,
            'soldes' => $this->soldeModel->getSoldeEmploye($employe_id, 2025),
            'demandes' => $this->congeModel->where('employe_id', $employe_id)->orderBy('created_at', 'DESC')->findAll(),
        ];

        return view('rh/demandes-employe', $data);
    }
}
