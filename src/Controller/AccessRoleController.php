<?php

namespace App\Controller;

use App\Entity\AccessRole;
use App\Service\AccessAuth;
use App\Form\AccessRoleType;
use Symfony\Component\Routing\Router;
use App\Repository\AccessRoleRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @Route("/access/role")
 */
class AccessRoleController extends AbstractController
{
    /**
     * @Route("/", name="access_role_index", methods={"GET"})
     */
    public function index(AccessRoleRepository $accessRoleRepository, AccessAuth $accessAuth, EntityManagerInterface $entityManager, Request $request)
    {
        $redirection = $accessAuth->verif($request, $entityManager);
        if($redirection)
        {
            return $this->redirect($redirection);
        }

        $baseurl = $request->getScheme() . '://' . $request->getHttpHost() . $request->getBasePath() . $request->server->get('REQUEST_URI');

        $testpathinfo = $request->server->get('REQUEST_URI');

        //dd($request);
        $rp = $request->attributes->get('_route_params');
        $tt = [];
        foreach ($rp as $keyi => $value)
        {
            $tt = $keyi . '--' . $value;
            $testpathinfo = str_replace($value, "%", $testpathinfo); ///"{".$keyi."}", $testpathinfo);
            
            array_push($tt, $testpathinfo);
        }
        dd($rp);

        //Permet de regenerer toutes les routes permettant les accès dans la database
        $this->generateAllAccessRoute($accessRoleRepository, $request);

        return $this->render('access_role/index.html.twig', [
            'access_roles' => $accessRoleRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="access_role_new", methods={"GET","POST"})
     */
    public function new(AccessAuth $accessAuth, EntityManagerInterface $entityManager, Request $request)
    {
        $redirection = $accessAuth->verif($request, $entityManager);
        if($redirection)
        {
            return $this->redirect($redirection);
        }

        $accessRole = new AccessRole();
        $form = $this->createFormBuilder($accessRole)
            ->add('authorizationEspace', TextType::class)
            ->add('descriptionEspace', TextType::class)
            ->getForm();



        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($accessRole);
            $entityManager->flush();

            return $this->redirectToRoute('access_role_index');
        }

        return $this->render('access_role/new.html.twig', [
            'access_role' => $accessRole,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="access_role_show", methods={"GET"})
     */
    public function show(AccessRole $accessRole, AccessAuth $accessAuth, EntityManagerInterface $entityManager, Request $request)
    {
        $redirection = $accessAuth->verif($request, $entityManager);
        if($redirection)
        {
            return $this->redirect($redirection);
        }

        return $this->render('access_role/show.html.twig', [
            'access_role' => $accessRole,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="access_role_edit", methods={"GET","POST"})
     */
    public function edit(AccessRole $accessRole, AccessAuth $accessAuth, EntityManagerInterface $entityManager, Request $request)
    {
        $redirection = $accessAuth->verif($request, $entityManager);
        if($redirection)
        {
            return $this->redirect($redirection);
        }

        $form = $this->createForm(AccessRoleType::class, $accessRole);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('access_role_index', [
                'id' => $accessRole->getId(),
            ]);
        }

        return $this->render('access_role/edit.html.twig', [
            'access_role' => $accessRole,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="access_role_delete", methods={"DELETE"})
     */
    public function delete(AccessRole $accessRole, AccessAuth $accessAuth, EntityManagerInterface $entityManager, Request $request)
    {
        $redirection = $accessAuth->verif($request, $entityManager);
        if($redirection)
        {
            return $this->redirect($redirection);
        }

        if ($this->isCsrfTokenValid('delete'.$accessRole->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($accessRole);
            $entityManager->flush();
        }

        return $this->redirectToRoute('access_role_index');
    }

    private function generateAllAccessRoute(AccessRoleRepository $accessRoleRepository, Request $request)
    {
        //Recupere l'entity manager
        $em = $this->getDoctrine()->getManager();
        /** @var Router $router */
        
        //Recupere l'instance router
        $router = $this->get('router');

        //Recupere les routes
        $routes = $router->getRouteCollection()->all();
        
        //Tableau des routes, chemins
        $all_route_path = [];
        

        foreach ($routes as $route) {

            $access = new AccessRole();
            $routePath = $route->getPath();

            //Verification de l'existence de la route
            $existingRoutePath = $accessRoleRepository->findBy([
                'authorizationEspace' => $routePath
            ]);

            //dd($test[1]);
            if (!preg_match('/_./', $routePath) && $existingRoutePath == null) {
                    
                    //Remplacer les {id} par % pour checker n'importe laquelle de valeur
                    $routePath = str_replace("{id}", "%", $routePath);
                    
                    $access->setAuthorizationEspace($routePath);
                    $access->setCreatedAt(new \DateTime());
                    $access->setCreatedBy("_generated");
                    $em->persist($access);
                    $em->flush();
                }
        }
    }
}
