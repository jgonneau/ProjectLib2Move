<?php

namespace App\Controller;

use App\Entity\Role;
use App\Entity\User;
use App\Entity\UserRole;
use App\Form\LoginUserType;
use App\Form\RegisterUserType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\BirthdayType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class SecurityController extends AbstractController
{
    /**
     * @Route("/register", name="register")
     */
    public function index(Request $request, UserPasswordEncoderInterface $encoder)
    {

        $entityManager = $this->getDoctrine()->getManager();
        $user = new User();
        $userRole = new UserRole();

        //L'on définit le rôle USER par défaut pour les utilisateurs
        $roleName = 'ROLE_USER';

        //L'on recupere et verifie si le rôle existe déjà dans la base de données
        $role = $entityManager->getRepository(Role::class)->findOneBy(['nomRole' => $roleName]);
        if ($role)
        {
            //L'on permet à l'utilisateur d'acceder au statut de ROLE_USER
            $userRole->setRole($role);
            $userRole->setUser($user);
        }
        else
        {
            //L'on crée le role USER si non existant dans la base de données
            $entityManager->persist($userRole);
            $entityManager->flush();

            //L'on recupere et verifie en même temps que le role existe maintenant
            $role = $entityManager->getRepository(Role::class)->findOneBy(['nomRole' => $roleName]);
        }

        //Definition du formulaire
        /*$form = $this->createFormBuilder($user)
            ->add('email', EmailType::class)
            ->add('password', PasswordType::class)
            ->add('firstname', TextType::class)
            ->add('lastname', TextType::class)
            ->add('birthday', BirthdayType::class)
            ->add('phoneNumber', TextType::class)
            ->getForm();*/

        $form = $this->createForm(RegisterUserType::class, $user);  
        $form->handleRequest($request);      

        if ($form->isSubmitted() && $form->isValid()) {
                $hash_password = $encoder->encodePassword($user, $user->getPassword());
                $user->setPassword($hash_password);
                $user->setRoles(['ROLE_USER']);
                $user->addUserRole($userRole);
                $entityManager->persist($user);
                $entityManager->flush();
    
                //redirection login... a revoir flash messages:
                return $this->redirectToRoute('login');
        }


        return $this->render('security/register.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/login", name="login")
     * @param AuthenticationUtils $authenticationUtils
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function login(AuthenticationUtils $authenticationUtils)
    {
        //Redirection si l'utilisateur est déjà connecté.
        if ($this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY'))
        {
            return $this->redirectToRoute('account');
        }

        $user = new User();

        $form = $this->createForm(LoginUserType::class, $user);

        return $this->render('security/login.html.twig', [
            'error' => $authenticationUtils ->getLastAuthenticationError(),
            'form' => $form->createView()
        ]);

    }
}
