<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    /**
     * @Route("/", name="home")
     */
    public function index()
    {
        //Redirection si authentifié admin
        if ($this->get('security.authorization_checker')->isGranted('ROLE_ADMIN'))
        {
            return $this->redirectToRoute('admin_dashboard');
        }
        return $this->render('home/index.html.twig', [
            'controller_name' => 'HomeController',
            'error' => ''
        ]);
    }
}
