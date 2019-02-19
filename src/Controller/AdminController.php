<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

/**
* @Route("/admin")
*/
class AdminController extends AbstractController
{
    /**
     * @Route("/", name="admin")
     */
    public function index()
    {
        //Redirection si l'utilisateur n'est pas admin.
        if ($this->get('security.authorization_checker')->isGranted('ROLE_ADMIN'))
        {
            return $this->render('admin/index.html.twig', [
                'controller_name' => 'AdminController',
            ]);
        }
        return $this->redirectToRoute('home', ['error' => 'No admin!']);
    }

    /**
     * @Route("/dashboard", name="admin_dashboard")
     */
    public function dashboard()
    {

        //Redirection si l'utilisateur n'est pas admin.
        if ($this->get('security.authorization_checker')->isGranted('ROLE_ADMIN'))
        {
            return $this->render('admin/dashboard.html.twig', [

            ]);
        }
        return $this->redirectToRoute('home', ['error' => 'No admin!']);
    }
}
