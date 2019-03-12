<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

use Doctrine\ORM\EntityManager;
use Doctrine\Common\Persistence\ObjectManager;


class TestController
{
    /*private $em;

    public function __construct(EntityManager $em)
    {
        $this->em = $em;
    }*/

    /**
     * @Route("/ttt", name="testo")
     */
    public function test()
    {
        //$routeCollection = $this->get('routing.loader')->loa

        $v = shell_exec('php bin/console debug:router');

        dd($v);

        /*dd($routeCollection);

        foreach ($routeCollection->all() as $routeName => $route) {
        //do stuff with Route (Symfony\Component\Routing\Route)
        }*/

        return $this->render('home/index.html.twig', [

        ]);
    }

    public function index()
    {
        /*//$entityManager = $this->getDoctrine()->getManager();
        $ret = $this->em->getRepository(UserRole::class)->findBy(['user' => 141]);
        //$ret = $entityManager->getRepository(UserRole::class)->findBy(['user' => 141]);
        
        $test = '';

        foreach ($ret as $value) {
            $test .= $value->getRole()->getNomRole();
        }*/

        return $test;
    }

    
}
