<?php

namespace App\Controller;

use App\Entity\UserRole;
use App\Service\AccessAuth;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Entity;
use Doctrine\ORM\EntityManager;
use App\Service\Lucy;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;

class HomeController extends AbstractController
{
    /**
     * @Route("/", name="home")
     */
    public function index(AccessAuth $accessAuth, Request $request, EntityManagerInterface $entityManager)
    {
        //Redirection si non autorisation d'accès
        $redirection = $accessAuth->verif($request, $entityManager);
        if ($redirection)
        {
            return $this->redirect($redirection);
        }

        return $this->render('home/index.html.twig', [
            'controller_name' => 'HomeController',
            'error' => ''
        ]);
    
    }


    /**
     * @Route("/vehicle_introducing", name="vehicle_introducing")
     */
    public function vehicle()
    {
        return $this->render('home/vehicle.html.twig', []);
    }

    /**
     * @Route("/strategy_introducing", name="strategy_introducing")
     */
    public function strategy()
    {
        return $this->render('home/strategy.html.twig', []);
    }

    /**
     * @Route("/principles", name="principles_introducing")
     */
    public function principles()
    {
        return $this->render('home/principles.html.twig', []);
    }

    /**
     * @Route("/enterprise", name="enterprise_introducing")
     */
    public function enterprise()
    {
        return $this->render('home/enterprise.html.twig', []);
    }

    /**
     * @Route("/locations", name="locations_introducing")
     */
    public function locations()
    {
        return $this->render('home/locations.html.twig', []);
    }

    /**
     * @Route("/test", name="testt")
     */
    public function test(AccessAuth $AA, Request $request, EntityManagerInterface $entityManager)
    {
        //Verifie l'authorisation des droits d'accès de l'utilisateur courant
        $redirection = $AA->verif($request, $entityManager);
        if($redirection)
        {
            return $this->redirect($redirection);
        }
        //dd($redirection != '', $redirection);

        return $this->render('home/index.html.twig', [
            'controller_name' => '',
            'error' => ''
        ]);
    }
}
