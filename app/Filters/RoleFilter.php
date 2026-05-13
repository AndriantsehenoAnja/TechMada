<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

class RoleFilter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        if (!session()->get('isLoggedIn')) {
            return redirect()->to('/login')->with('error', 'Veuillez vous connecter');
        }

        $userRole = session()->get('role');
        
        if (empty($arguments)) {
            return;
        }
        
        $allowedRoles = $arguments;
        
        if (!in_array($userRole, $allowedRoles)) {
            // Rediriger selon le rôle
            if ($userRole === 'employe') {
                return redirect()->to('employe/index')->with('error', 'Accès non autorisé');
            } elseif ($userRole === 'rh') {
                return redirect()->to('rh/index')->with('error', 'Accès non autorisé');
            }
            
            return redirect()->to('/')->with('error', 'Accès non autorisé');
        }
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // Rien après
    }
}