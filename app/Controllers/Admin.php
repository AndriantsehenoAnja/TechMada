<?php

namespace App\Controllers;

use App\Models\Conge;
use App\Models\Employee;
use App\Models\Solde;
use CodeIgniter\Controller;
use Config\Database;

class Admin extends Controller
{
    protected Employee $employeeModel;
    protected Conge $congeModel;
    protected Solde $soldeModel;
    protected $db;
    protected $session;

    public function __construct()
    {
        $this->employeeModel = new Employee();
        $this->congeModel = new Conge();
        $this->soldeModel = new Solde();
        $this->db = Database::connect();
        $this->session = session();
    }

    private function guard()
    {
        if (!$this->session->has('user_id') || (string) $this->session->get('user_role') !== 'admin') {
            return redirect()->to('/login')->with('error', 'Accès refusé. Admin uniquement.');
        }

        return null;
    }

    private function monthRange(): array
    {
        return [
            'start' => date('Y-m-01'),
            'end' => date('Y-m-t'),
        ];
    }

    private function departementsList(): array
    {
        return $this->db->table('departements')->orderBy('nom', 'ASC')->get()->getResultArray();
    }

    private function typesCongeList(): array
    {
        return $this->db->table('types_conge')->orderBy('libelle', 'ASC')->get()->getResultArray();
    }

    private function employeesWithDepartements(): array
    {
        return $this->db->table('employes e')
            ->select('e.*, d.nom as departement_nom')
            ->join('departements d', 'd.id = e.departement_id', 'left')
            ->orderBy('e.nom', 'ASC')
            ->get()
            ->getResultArray();
    }

    private function currentMonthAbsences(): array
    {
        $range = $this->monthRange();

        return $this->db->table('conges c')
            ->select('c.*, e.nom as nom_employe, e.prenom as prenom_employe, t.libelle as type_conge')
            ->join('employes e', 'e.id = c.employe_id')
            ->join('types_conge t', 't.id = c.type_conge_id')
            ->where('c.date_debut <=', $range['end'])
            ->where('c.date_fin >=', $range['start'])
            ->orderBy('c.date_debut', 'ASC')
            ->get()
            ->getResultArray();
    }

    private function upsertSolde(int $employeId, int $typeCongeId, int $annee, float $joursAttribues, float $joursPris = 0.0): void
    {
        $existing = $this->db->table('soldes')
            ->where('employe_id', $employeId)
            ->where('type_conge_id', $typeCongeId)
            ->where('annee', $annee)
            ->get()
            ->getRowArray();

        $data = [
            'employe_id' => $employeId,
            'type_conge_id' => $typeCongeId,
            'annee' => $annee,
            'jours_attribues' => $joursAttribues,
            'jours_pris' => $joursPris,
        ];

        if ($existing) {
            $this->db->table('soldes')->where('id', $existing['id'])->update($data);
            return;
        }

        $this->db->table('soldes')->insert($data);
    }

    public function dashboard()
    {
        if ($redirect = $this->guard()) {
            return $redirect;
        }

        $range = $this->monthRange();
        $currentMonthCongeCount = $this->db->table('conges')
            ->where('date_debut <=', $range['end'])
            ->where('date_fin >=', $range['start'])
            ->countAllResults();

        $currentMonthApprovedCount = $this->db->table('conges')
            ->where('statut', 'approuvee')
            ->where('date_debut <=', $range['end'])
            ->where('date_fin >=', $range['start'])
            ->countAllResults();

        $approvedDaysRow = $this->db->table('conges')
            ->selectSum('nb_jours')
            ->where('statut', 'approuvee')
            ->where('date_debut <=', $range['end'])
            ->where('date_fin >=', $range['start'])
            ->get()
            ->getRow();

        $currentMonthApprovedDays = $approvedDaysRow ? (float) ($approvedDaysRow->nb_jours ?? 0) : 0.0;

        $data = [
            'employeesCount' => $this->db->table('employes')->countAllResults(),
            'departementsCount' => $this->db->table('departements')->countAllResults(),
            'typesCount' => $this->db->table('types_conge')->countAllResults(),
            'pendingCount' => $this->db->table('conges')->where('statut', 'en_attente')->countAllResults(),
            'currentMonthCongeCount' => $currentMonthCongeCount,
            'currentMonthApprovedCount' => $currentMonthApprovedCount,
            'currentMonthApprovedDays' => $currentMonthApprovedDays,
            'recentDemandes' => $this->congeModel->getAllDemandesWithDetails(),
            'currentMonthAbsences' => $this->currentMonthAbsences(),
            'userPrenom' => $this->session->get('user_prenom'),
            'userNom' => $this->session->get('user_nom'),
        ];

        return view('admin/dashboard', $data);
    }

    public function employes()
    {
        if ($redirect = $this->guard()) {
            return $redirect;
        }

        $editId = (int) ($this->request->getGet('edit') ?? 0);

        $data = [
            'employes' => $this->employeesWithDepartements(),
            'departements' => $this->departementsList(),
            'editEmploye' => $editId ? $this->employeeModel->find($editId) : null,
        ];

        return view('admin/employes', $data);
    }

    public function storeEmploye()
    {
        if ($redirect = $this->guard()) {
            return $redirect;
        }

        $password = trim((string) $this->request->getPost('password'));
        if ($password === '') {
            return redirect()->back()->withInput()->with('error', 'Le mot de passe est requis.');
        }

        $this->employeeModel->insert([
            'nom' => trim((string) $this->request->getPost('nom')),
            'prenom' => trim((string) $this->request->getPost('prenom')),
            'email' => strtolower(trim((string) $this->request->getPost('email'))),
            'password' => $password,
            'role' => trim((string) ($this->request->getPost('role') ?: 'employe')),
            'departement_id' => (int) $this->request->getPost('departement_id'),
            'date_embauche' => $this->request->getPost('date_embauche') ?: null,
            'actif' => (int) ($this->request->getPost('actif') ?? 1),
        ]);

        return redirect()->to('/admin/employes')->with('success', 'Employé créé.');
    }

    public function updateEmploye($id)
    {
        if ($redirect = $this->guard()) {
            return $redirect;
        }

        $data = [
            'nom' => trim((string) $this->request->getPost('nom')),
            'prenom' => trim((string) $this->request->getPost('prenom')),
            'email' => strtolower(trim((string) $this->request->getPost('email'))),
            'role' => trim((string) ($this->request->getPost('role') ?: 'employe')),
            'departement_id' => (int) $this->request->getPost('departement_id'),
            'date_embauche' => $this->request->getPost('date_embauche') ?: null,
            'actif' => (int) ($this->request->getPost('actif') ?? 1),
        ];

        $password = trim((string) $this->request->getPost('password'));
        if ($password !== '') {
            $data['password'] = $password;
        }

        $this->employeeModel->update($id, $data);

        return redirect()->to('/admin/employes')->with('success', 'Employé mis à jour.');
    }

    public function toggleEmploye($id)
    {
        if ($redirect = $this->guard()) {
            return $redirect;
        }

        $employee = $this->employeeModel->find($id);
        if (!$employee) {
            return redirect()->back()->with('error', 'Employé introuvable.');
        }

        $this->employeeModel->update($id, ['actif' => (int) !$employee['actif']]);

        return redirect()->back()->with('success', 'Statut de l employé mis à jour.');
    }

    public function departements()
    {
        if ($redirect = $this->guard()) {
            return $redirect;
        }

        $editId = (int) ($this->request->getGet('edit') ?? 0);

        $data = [
            'departements' => $this->departementsList(),
            'departementEdit' => $editId ? $this->db->table('departements')->where('id', $editId)->get()->getRowArray() : null,
        ];

        return view('admin/departements', $data);
    }

    public function storeDepartement()
    {
        if ($redirect = $this->guard()) {
            return $redirect;
        }

        $nom = trim((string) $this->request->getPost('nom'));
        if ($nom === '') {
            return redirect()->back()->withInput()->with('error', 'Le nom du département est requis.');
        }

        $this->db->table('departements')->insert([
            'nom' => $nom,
            'description' => trim((string) $this->request->getPost('description')),
        ]);

        return redirect()->to('/admin/departements')->with('success', 'Département créé.');
    }

    public function updateDepartement($id)
    {
        if ($redirect = $this->guard()) {
            return $redirect;
        }

        $this->db->table('departements')->where('id', $id)->update([
            'nom' => trim((string) $this->request->getPost('nom')),
            'description' => trim((string) $this->request->getPost('description')),
        ]);

        return redirect()->to('/admin/departements')->with('success', 'Département mis à jour.');
    }

    public function deleteDepartement($id)
    {
        if ($redirect = $this->guard()) {
            return $redirect;
        }

        $this->db->table('departements')->where('id', $id)->delete();

        return redirect()->to('/admin/departements')->with('success', 'Département supprimé.');
    }

    public function typesConges()
    {
        if ($redirect = $this->guard()) {
            return $redirect;
        }

        $editId = (int) ($this->request->getGet('edit') ?? 0);

        $data = [
            'typesConges' => $this->typesCongeList(),
            'typeCongeEdit' => $editId ? $this->db->table('types_conge')->where('id', $editId)->get()->getRowArray() : null,
        ];

        return view('admin/types-conges', $data);
    }

    public function storeTypeConge()
    {
        if ($redirect = $this->guard()) {
            return $redirect;
        }

        $libelle = trim((string) $this->request->getPost('libelle'));
        if ($libelle === '') {
            return redirect()->back()->withInput()->with('error', 'Le libellé est requis.');
        }

        $this->db->table('types_conge')->insert([
            'libelle' => $libelle,
            'jours_annuels' => (int) $this->request->getPost('jours_annuels'),
            'deductible' => (int) ($this->request->getPost('deductible') ?? 1),
        ]);

        return redirect()->to('/admin/types-conges')->with('success', 'Type de congé créé.');
    }

    public function updateTypeConge($id)
    {
        if ($redirect = $this->guard()) {
            return $redirect;
        }

        $this->db->table('types_conge')->where('id', $id)->update([
            'libelle' => trim((string) $this->request->getPost('libelle')),
            'jours_annuels' => (int) $this->request->getPost('jours_annuels'),
            'deductible' => (int) ($this->request->getPost('deductible') ?? 1),
        ]);

        return redirect()->to('/admin/types-conges')->with('success', 'Type de congé mis à jour.');
    }

    public function deleteTypeConge($id)
    {
        if ($redirect = $this->guard()) {
            return $redirect;
        }

        $this->db->table('types_conge')->where('id', $id)->delete();

        return redirect()->to('/admin/types-conges')->with('success', 'Type de congé supprimé.');
    }

    public function absences()
    {
        if ($redirect = $this->guard()) {
            return $redirect;
        }

        $range = $this->monthRange();

        $data = [
            'mois' => date('F Y'),
            'absences' => $this->currentMonthAbsences(),
            'approvedCount' => $this->db->table('conges')
                ->where('statut', 'approuvee')
                ->where('date_debut <=', $range['end'])
                ->where('date_fin >=', $range['start'])
                ->countAllResults(),
            'pendingCount' => $this->db->table('conges')
                ->where('statut', 'en_attente')
                ->where('date_debut <=', $range['end'])
                ->where('date_fin >=', $range['start'])
                ->countAllResults(),
        ];

        return view('admin/absences', $data);
    }

    public function soldes()
    {
        if ($redirect = $this->guard()) {
            return $redirect;
        }

        $year = (int) ($this->request->getGet('year') ?: date('Y'));

        $data = [
            'year' => $year,
            'soldes' => $this->soldeModel->getAllSoldes($year),
            'employes' => $this->employeesWithDepartements(),
            'typesConges' => $this->typesCongeList(),
        ];

        return view('admin/soldes', $data);
    }

    public function initializeSoldes()
    {
        if ($redirect = $this->guard()) {
            return $redirect;
        }

        $annee = (int) ($this->request->getPost('annee') ?: date('Y'));
        $employeId = (int) ($this->request->getPost('employe_id') ?: 0);

        $employees = $employeId > 0
            ? [$this->employeeModel->find($employeId)]
            : $this->employeeModel->where('actif', 1)->findAll();

        $types = $this->db->table('types_conge')->where('deductible', 1)->get()->getResultArray();

        foreach ($employees as $employee) {
            if (!$employee) {
                continue;
            }

            foreach ($types as $typeConge) {
                $this->upsertSolde(
                    (int) $employee['id'],
                    (int) $typeConge['id'],
                    $annee,
                    (float) $typeConge['jours_annuels'],
                    0.0
                );
            }
        }

        return redirect()->to('/admin/soldes?year=' . $annee)->with('success', 'Soldes initialisés.');
    }

    public function historique()
    {
        if ($redirect = $this->guard()) {
            return $redirect;
        }

        $data = [
            'demandes' => $this->congeModel->getAllDemandesWithDetails(),
        ];

        return view('admin/historique', $data);
    }
}