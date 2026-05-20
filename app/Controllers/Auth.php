<?php

namespace App\Controllers;

use App\Models\Employee;
use CodeIgniter\Controller;

class Auth extends Controller
{
    protected $helpers = ['url'];
    protected $employeeModel;
    protected $session;

    public function __construct()
    {
        $this->employeeModel = new Employee();
        $this->session = session();
    }

    /**
     * Afficher la page de login
     */
    public function login()
    {
        return view('login/login');
    }

    /**
     * Traiter la soumission du formulaire de login
     */
    public function authenticate()
    {
        if (!$this->request->is('post')) {
            return redirect()->to('/login');
        }

        $email = strtolower(trim($this->request->getPost('email')));
        $password = $this->request->getPost('password');

        // Validation basique
        if (empty($email) || empty($password)) {
            return redirect()->back()
                ->with('error', 'Email et mot de passe sont requis.');
        }

        // Chercher l'employé actif
        $employee = $this->employeeModel->getActiveByEmail($email);

        if (!$employee) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Email ou mot de passe incorrect.');
        }

        // Vérifier le mot de passe
        // Note: Dans la démo, les passwords sont en clair. En production utiliser password_verify()
        if (!$this->verifyPassword($password, $employee['password'])) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Email ou mot de passe incorrect.');
        }

        // Créer la session
        $this->session->set([
            'user_id'    => $employee['id'],
            'user_email' => $employee['email'],
            'user_nom'   => $employee['nom'],
            'user_prenom'=> $employee['prenom'],
            'user_role'  => $employee['role'],
            'departement_id' => $employee['departement_id'],
        ]);

        // Redirection selon le rôle
        $role = strtolower($employee['role']);
        $redirectUrl = match($role) {
            'admin' => '/admin',
            'rh' => '/rh',
            'employe' => '/employe',
            default => '/login',
        };

        return redirect()->to($redirectUrl)->with('success', 'Bienvenue ' . $employee['prenom'] . '!');
    }

    /**
     * Déconnexion
     */
    public function logout()
    {
        $this->session->destroy();
        return redirect()->to('/login')->with('success', 'Vous avez été déconnecté.');
    }

    /**
     * Vérifier le mot de passe
     * Note: En production, utiliser password_verify() avec password_hash()
     */
    private function verifyPassword($inputPassword, $storedPassword)
    {
        // Accept both hashed and plain-stored passwords for transition:
        // - If stored password looks like a hash (starts with $2y$ or $argon2), use password_verify
        // - Otherwise, fall back to direct comparison
        if (is_string($storedPassword) && (str_starts_with($storedPassword, '$2y$') || str_starts_with($storedPassword, '$argon2') || str_starts_with($storedPassword, '$2a$') || str_starts_with($storedPassword, '$2b$'))) {
            return password_verify($inputPassword, $storedPassword);
        }

        return $inputPassword === $storedPassword;
    }
}
