<?php

namespace App\Controllers;

use CodeIgniter\Controller;
use Config\Database;
use App\Models\Employee;
use DateTime;

class EmployeController extends Controller
{
    private Employee $employeeModel;
    protected $session;

    public function __construct()
    {
        $this->employeeModel = new Employee();
        $this->session = session();
    }

    private function requireEmploye()
    {
        if (! $this->session || ! $this->session->has('user_id')) {
            return redirect()->to('/login');
        }

        if ($this->session->get('user_role') !== 'employe') {
            return redirect()->to('/login');
        }

        return null;
    }

    public function dashboard()
    {
        if ($redirect = $this->requireEmploye()) {
            return $redirect;
        }

        return view('employe/dashboard');
    }

    public function demandes()
    {
        if ($redirect = $this->requireEmploye()) {
            return $redirect;
        }

        $employeeId = (int) $this->session->get('user_id');
        $db = Database::connect();

        $demandes = $db->table('conges')
            ->select('conges.*, types_conge.libelle as type_conge')
            ->join('types_conge', 'types_conge.id = conges.type_conge_id')
            ->where('conges.employe_id', $employeeId)
            ->orderBy('conges.created_at', 'DESC')
            ->get()
            ->getResultArray();

        return view('employe/demandes', [
            'demandes' => $demandes,
        ]);
    }

    public function nouvelleDemande()
    {
        if ($redirect = $this->requireEmploye()) {
            return $redirect;
        }

        $db = Database::connect();
        $typesConge = $db->table('types_conge')
            ->orderBy('libelle', 'ASC')
            ->get()
            ->getResultArray();

        $soldes = $db->table('v_soldes')
            ->where('employe_id', (int) $this->session->get('user_id'))
            ->orderBy('type_conge', 'ASC')
            ->get()
            ->getResultArray();

        return view('employe/nouvelle_demande', [
            'typesConge' => $typesConge,
            'soldes' => $soldes,
        ]);
    }

    public function storeDemande()
    {
        if ($redirect = $this->requireEmploye()) {
            return $redirect;
        }

        if (! $this->request->is('post')) {
            return redirect()->to('/employe/nouvelle-demande');
        }

        $rules = [
            'type_conge_id' => 'required|is_natural_no_zero',
            'date_debut' => 'required',
            'date_fin' => 'required',
            'motif' => 'required|min_length[3]',
        ];

        if (! $this->validate($rules)) {
            return redirect()->back()->withInput()->with('error', 'Veuillez remplir correctement le formulaire.');
        }

        $dateDebut = DateTime::createFromFormat('Y-m-d', (string) $this->request->getPost('date_debut'));
        $dateFin = DateTime::createFromFormat('Y-m-d', (string) $this->request->getPost('date_fin'));

        if (! $dateDebut || ! $dateFin || $dateDebut > $dateFin) {
            return redirect()->back()->withInput()->with('error', 'Les dates de congé sont invalides.');
        }

        $jours = (int) $dateDebut->diff($dateFin)->days + 1;

        $db = Database::connect();
        $db->table('conges')->insert([
            'employe_id' => (int) $this->session->get('user_id'),
            'type_conge_id' => (int) $this->request->getPost('type_conge_id'),
            'date_debut' => $dateDebut->format('Y-m-d'),
            'date_fin' => $dateFin->format('Y-m-d'),
            'nb_jours' => $jours,
            'motif' => trim((string) $this->request->getPost('motif')),
            'statut' => 'en_attente',
            'created_at' => date('Y-m-d H:i:s'),
            'traite_par' => null,
        ]);

        return redirect()->to('/employe/demandes')->with('success', 'Votre demande de congé a été envoyée.');
    }

    public function cancelDemande($id)
    {
        if ($redirect = $this->requireEmploye()) {
            return $redirect;
        }

        $db = Database::connect();
        $demande = $db->table('conges')
            ->where('id', (int) $id)
            ->where('employe_id', (int) $this->session->get('user_id'))
            ->get()
            ->getRowArray();

        if (! $demande) {
            return redirect()->to('/employe/demandes')->with('error', 'Demande introuvable.');
        }

        if (($demande['statut'] ?? '') !== 'en_attente') {
            return redirect()->to('/employe/demandes')->with('error', 'Seule une demande en attente peut être annulée.');
        }

        $db->table('conges')->where('id', (int) $id)->update([
            'statut' => 'annulee',
        ]);

        return redirect()->to('/employe/demandes')->with('success', 'La demande a été annulée.');
    }

    public function solde()
    {
        if ($redirect = $this->requireEmploye()) {
            return $redirect;
        }

        $db = Database::connect();
        $soldes = $db->table('v_soldes')
            ->where('employe_id', (int) $this->session->get('user_id'))
            ->orderBy('type_conge', 'ASC')
            ->get()
            ->getResultArray();

        return view('employe/solde', [
            'soldes' => $soldes,
        ]);
    }

    public function profil()
    {
        if ($redirect = $this->requireEmploye()) {
            return $redirect;
        }

        $employe = $this->employeeModel->getById((int) $this->session->get('user_id'));

        return view('employe/profil', [
            'employe' => $employe,
        ]);
    }

    public function updateProfil()
    {
        if ($redirect = $this->requireEmploye()) {
            return $redirect;
        }

        if (! $this->request->is('post')) {
            return redirect()->to('/employe/profil');
        }

        $rules = [
            'nom' => 'required|min_length[2]',
            'prenom' => 'required|min_length[2]',
            'password' => 'permit_empty|min_length[6]',
        ];

        if (! $this->validate($rules)) {
            return redirect()->back()->withInput()->with('error', 'Veuillez corriger les champs du profil.');
        }

        $data = [
            'nom' => trim((string) $this->request->getPost('nom')),
            'prenom' => trim((string) $this->request->getPost('prenom')),
        ];

        $password = trim((string) $this->request->getPost('password'));
        if ($password !== '') {
            $data['password'] = $password;
        }

        $this->employeeModel->update((int) $this->session->get('user_id'), $data);

        $this->session->set([
            'user_nom' => $data['nom'],
            'user_prenom' => $data['prenom'],
        ]);

        return redirect()->to('/employe/profil')->with('success', 'Profil mis à jour.');
    }
}