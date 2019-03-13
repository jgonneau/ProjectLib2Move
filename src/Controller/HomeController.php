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
        /*$sql = 'SELECT default_path_redirection 
        FROM user_role ur
        LEFT JOIN user u ON ur.user_id = u.id
        LEFT JOIN role r ON r.id = ur.role_id
        ORDER BY r.level_role DESC
        LIMIT 1';

        $conn = $entityManager->getConnection(); //$this-> //->getEntityManager()->getConnection();

        $stmt = $conn->prepare($sql);
        $stmt->execute([ 'user_id' => $this->getUser()->getId() ]);
        // dd($stmt->fetchAll());

        $ret = $stmt->fetchAll();
        dd('|||', $ret[0]);*/

        $redirection = $accessAuth->verif($request, $entityManager);
        if ($redirection)
        {
            //dd($is_auth);
            return $this->redirect($redirection);
        }

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
    public function test(AccessAuth $AA, Request $request, EntityManagerInterface $entityManager)
    {
        //Verifie l'authorisation des droits d'accès de l'utilisateur courant
        $accss = "";//$AA->verif($this->getUser()->getId(), );
        $redirection = $AA->verif($request, $entityManager);
        //dd($redirection);
        if($redirection)
        {
            return $this->redirect($redirection);
        }
        //dd($redirection != '', $redirection);

        return $this->render('home/index.html.twig', [
            'controller_name' => $accss,
            'error' => ''
        ]);
    }
}
