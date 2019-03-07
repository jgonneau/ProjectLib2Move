<?php

namespace App\Controller;

use App\Entity\UserRole;
use App\Service\AccessAuth;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Entity;
use Doctrine\ORM\EntityManager;
use App\Service\Lucy;

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

    /**
     * @Route("/test", name="testt")
     */
    public function test(AccessAuth $AA)
    {
        //Verifie l'authorisation des droits d'accès de l'utilisateur courant
        $accss = $AA->verif($this->getUser()->getId());
        dd($accss);

        return $this->render('home/index.html.twig', [
            'controller_name' => $accss,
            'error' => ''
        ]);
    }
}
