<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

class AuthFilter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        // On évite la boucle de redirection si on est déjà sur la page de login
        $uri = $request->getUri()->getPath();
        if (strpos($uri, 'auth/login') !== false) {
            return;
        }

        if (!session()->get('isLoggedIn')) {
            return redirect()->to('/auth/login')->with('error', 'Veuillez vous connecter pour accéder à cette page');
        }
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // Rien après
    }
}

?>