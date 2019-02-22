<?php

namespace App\Controller;

use App\Repository\UserRepository;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

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
    public function dashboard(UserRepository $userRepository)
    {
        //L'on récupère tous les utilisateurs
        $all_users = $userRepository->findAll();

        //Creation formulaire pour ajouter nouvel utilisateur
        //$user = new User();
        /*$form = $this->createFormBuilder($user)
            ->add('nomRole', TextType::class)
            ->add('descriptionRole', TextType::class)
            ->getForm();*/

        //Redirection si l'utilisateur n'est pas admin.
        if ($this->get('security.authorization_checker')->isGranted('ROLE_ADMIN'))
        {
            /*return $this->render('admin/dashboard.html.twig', [
                'allusers' => $all_users
            ]);*/
            return $this->redirectToRoute('user_index');
        }
        return $this->redirectToRoute('home', ['error' => 'No admin!']);
    }
}
