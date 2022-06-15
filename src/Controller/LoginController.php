<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class LoginController extends AbstractController
{
    
    #[Route('/exlogin', name: 'exlogin')]
    public function index(): Response
    {
        return $this->render('login/index.html.twig', [
            'controller_name' => 'LoginController',
        ]);
    }

    #[Route('/exlogon', name: 'exlogon')]
    public function logon(): Response
    {
        return $this->render('produit/liste.html.twig', [
            'controller_name' => 'LoginController',
        ]);
    }
}
