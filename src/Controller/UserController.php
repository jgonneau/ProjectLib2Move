<?php

namespace App\Controller;

use App\Entity\Role;
use App\Entity\User;
use App\Form\UserType;
use App\Entity\UserRole;
use App\Repository\UserRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\BirthdayType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

/**
 * @Route("/admin/dashboard/users")
 * @IsGranted("ROLE_ADMIN")
 */
class UserController extends AbstractController
{
    /**
     * @Route("/", name="user_index", methods={"GET"})
     */
    public function index(UserRepository $userRepository): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');
        //L'on récupère tous les utilisateurs
        $all_users = $userRepository->findAll();

        //Redirection si l'utilisateur n'est pas admin.
        if ($this->get('security.authorization_checker')->isGranted('ROLE_ADMIN'))
        {
            return $this->render('admin/dashboard.html.twig', [
                'allusers' => $all_users
            ]);
        }
        return $this->redirectToRoute('home', ['error' => 'No admin!']);
    }

    /**
     * @Route("/new", name="user_new", methods={"GET","POST"})
     * @param Request $request
     * @param UserPasswordEncoderInterface $encoder
     * @param \Swift_Mailer $mailer
     * @return Response
     */
    public function new(Request $request, UserPasswordEncoderInterface $encoder, \Swift_Mailer $mailer): Response
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
            //L'on permet à utilisateur d'acceder au statut de ROLE_USER
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


        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        //Creation formulaire pour ajouter nouvel utilisateur
        //$user = new User();
        $form = $this->createFormBuilder($user)
            ->add('email', EmailType::class)
            ->add('roles', IntegerType::class)
            ->add('password', PasswordType::class)
            ->add('firstname', TextType::class)
            ->add('lastname', TextType::class)
            ->add('birthday', BirthdayType::class)
            ->add('phoneNumber', TextType::class)
            ->add('licenceNumber', TextType::class)
            ->add('document', TextType::class)
            ->getForm();

        if ($form->isSubmitted() && $form->isValid()) {
            $hash_password = $encoder->encodePassword($user, $user->getPassword());
            $user->setPassword($hash_password);
            $user->setRoles(['ROLE_USER']);
            $user->addUserRole($userRole);
            $entityManager->persist($user);
            $entityManager->flush();

            $message = (new \Swift_Message('Hello New Customer'))
                ->setFrom('contactlibtomove@gmail.com')
                ->setTo($user->getEmail())
                ->setBody(
                    $this->renderView(
                    // templates/emails/registration.html.twig
                        'user/registration.html.twig',
                        ['name' => $user->getFirstname()]
                    ),
                    'text/html'
                )
                /*
                 * If you also want to include a plaintext version of the message
                ->addPart(
                    $this->renderView(
                        'emails/registration.txt.twig',
                        ['name' => $name]
                    ),
                    'text/plain'
                )
                */
            ;

            $mailer->send($message);

            return $this->redirectToRoute('user_index');
        }





        return $this->render('user/new.html.twig', [
            'user' => $user,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="user_show", methods={"GET"})
     */
    public function show(User $user): Response
    {
        return $this->render('user/show.html.twig', [
            'user' => $user,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="user_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, User $user): Response
    {
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('user_index', [
                'id' => $user->getId(),
            ]);
        }

        return $this->render('user/edit.html.twig', [
            'user' => $user,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="user_delete", methods={"DELETE"})
     */
    public function delete(Request $request, User $user): Response
    {
        if ($this->isCsrfTokenValid('delete'.$user->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($user);
            $entityManager->flush();
        }

        return $this->redirectToRoute('user_index');
    }
}
