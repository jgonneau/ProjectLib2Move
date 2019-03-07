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
