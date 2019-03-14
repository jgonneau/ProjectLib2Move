<?php

namespace App\Controller;

use App\Repository\UserRepository;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Service\AccessAuth;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;

//header("Access-Control-Allow-Origin: *");

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
        return $this->redirectToRoute('admin_dashboard');
    }

    /**
     * @Route("/dashboard", name="admin_dashboard")
     */
    public function dashboard(UserRepository $userRepository, AccessAuth $accessAuth, EntityManagerInterface $entityManager, Request $request)
    {
        //Redirection si non autorisation d'accès
        $redirection = $accessAuth->verif($request, $entityManager);
        if($redirection)
        {
            return $this->redirect($redirection);
        }

        //L'on récupère tous les utilisateurs
        $all_users = $userRepository->findAll();

        //Redirection si l'utilisateur n'est pas admin.
        if ($this->get('security.authorization_checker')->isGranted('ROLE_ADMIN'))
        {
            return $this->redirectToRoute('user_index');
        }
        return $this->redirectToRoute('home', ['error' => 'No admin!']);
    }
}
