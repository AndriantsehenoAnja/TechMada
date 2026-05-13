<?php

namespace App\Controllers;


class AuthController extends BaseController
{
    public function login()
    {
        return view('auth/login');
    }

    public function register()
    {
        return view('auth/register');
    }

    public function logout()
    {
        // Logique de déconnexion (ex: session_destroy())
        session_destroy();
        return redirect()->to('auth/login');
    }

    public function authenticate()
    {
        // Logique d'authentification (ex: vérifier les credentials et créer une session)
        $session = session();
        $employeModel = new \App\Models\EmployeModel();
        $email = $this->request->getPost('email');
        $password = $this->request->getPost('password');
        
        $employe = $employeModel->where('email', $email)->first();

        if ($employe && password_verify($password, $employe['password'])) {
            $session->set('employe', $employe);

            if ($employe['role'] === 'admin') {
                return redirect()->to('/admin/dashboard');
            } elseif ($employe['role'] === 'rh') {
                return redirect()->to('/rh/dashboard');
            } else {
                return redirect()->to('/employe/dashboard');
            }
        }
        return redirect()->to('/auth/login')->with('error', 'Identifiants incorrects');
    }
}