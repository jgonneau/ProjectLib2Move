<?php

namespace App\Controller;

use App\Service\AccessAuth;
use Doctrine\ORM\EntityManager;

use Symfony\Component\HttpFoundation\Request;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Doctrine\ORM\EntityManagerInterface;


class TestController extends AbstractController
{
    /*private $request;
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->em = $entityManager;
        $this->re = new Request();
    }*/

    /**
     * @Route("/ttt", name="testo")
     */
    public function test(AccessAuth $accessAuth, EntityManagerInterface $entityManager, Request $request)
    {
        //$routeCollection = $this->get('routing.loader')->loa
        /*$sql = 'SELECT * 
            FROM access_role ar
            LEFT JOIN access_role_role arr ON arr.access_role_id = ar.id
            LEFT JOIN role r ON arr.role_id = r.id
            LEFT JOIN user_role ur ON ur.role_id = r.id
            LEFT JOIN user u ON ur.user_id = u.id
            WHERE u.id = :user_id';*/

        //$req = new Request();
        
        $ret = $accessAuth->verif($request, $entityManager);

        dd($ret);//, $this->getUser()->getId());

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
