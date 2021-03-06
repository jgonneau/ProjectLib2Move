<?php

namespace App\Controller;

use App\Entity\Role;
use App\Form\RoleType;
use App\Service\AccessAuth;
use App\Repository\RoleRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @Route("/admin/dashboard/role")
 */
class RoleController extends AbstractController
{

    /**
     * @Route("/", name="role_index", methods={"GET"})
     */
    public function index(RoleRepository $roleRepository, AccessAuth $accessAuth, EntityManagerInterface $entityManager, Request $request) : Response
    {
        $redirection = $accessAuth->verif($request, $entityManager);
        if($redirection)
        {
            return $this->redirect($redirection);
        }

        //L'on récupère tous les roles
        $all_roles = $roleRepository->findAll();

        //Redirection si l'utilisateur n'est pas admin.
        if ($this->get('security.authorization_checker')->isGranted('ROLE_ADMIN'))
        {
            return $this->render('role/index.html.twig', [
                'allroles' => $all_roles
            ]);
        }
        return $this->redirectToRoute('home', ['error' => 'No admin!']);
    }

    /**
     * @Route("/new", name="role_new", methods={"GET","POST"})
     */
    public function new(AccessAuth $accessAuth, EntityManagerInterface $entityManager, Request $request) :Response
    {
        $redirection = $accessAuth->verif($request, $entityManager);
        if($redirection)
        {
            return $this->redirect($redirection);
        }
        
        $role = new Role();
        //$form = $this->createForm(RoleType::class, $role);
        $form = $this->createFormBuilder($role)
            ->add('nomRole', TextType::class)
            ->add('descriptionRole', TextType::class)
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($role);
            $entityManager->flush();

            return $this->redirectToRoute('role_index');
        }

        return $this->render('role/new.html.twig', [
            'role' => $role,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="role_show", methods={"GET"})
     */
    public function show(Role $role, AccessAuth $accessAuth, EntityManagerInterface $entityManager, Request $request) : Response
    {
        $redirection = $accessAuth->verif($request, $entityManager);
        if($redirection)
        {
            return $this->redirect($redirection);
        }

        return $this->render('role/show.html.twig', [
            'role' => $role,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="role_edit", methods={"GET","POST"})
     */
    public function edit(Role $role, AccessAuth $accessAuth, EntityManagerInterface $entityManager, Request $request) : Response
    {
        $redirection = $accessAuth->verif($request, $entityManager);
        if($redirection)
        {
            return $this->redirect($redirection);
        }

        $form = $this->createForm(RoleType::class, $role);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('role_index', [
                'id' => $role->getId(),
            ]);
        }

        return $this->render('role/edit.html.twig', [
            'role' => $role,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="role_delete", methods={"DELETE"})
     */
    public function delete(Role $role, AccessAuth $accessAuth, EntityManagerInterface $entityManager, Request $request) : Response
    {
        $redirection = $accessAuth->verif($request, $entityManager);
        if($redirection)
        {
            return $this->redirect($redirection);
        }

        if ($this->isCsrfTokenValid('delete'.$role->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($role);
            $entityManager->flush();
        }

        return $this->redirectToRoute('role_index');
    }
}
