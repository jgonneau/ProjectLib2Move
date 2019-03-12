<?php

namespace App\Controller;

use App\Entity\AccessRole;
use App\Form\AccessRoleType;
use App\Repository\AccessRoleRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Router;

/**
 * @Route("/access/role")
 */
class AccessRoleController extends AbstractController
{
    /**
     * @Route("/", name="access_role_index", methods={"GET"})
     */
    public function index(AccessRoleRepository $accessRoleRepository, Request $request): Response
    {
        $baseurl = $request->getScheme() . '://' . $request->getHttpHost() . $request->getBasePath() . $request->server->get('REQUEST_URI');

        $testpathinfo = $request->server->get('REQUEST_URI');

        //dd($request);
        $rp = $request->attributes->get('_route_params');
        $tt = [];
        foreach ($rp as $keyi => $value)
        {
            $tt = $keyi . '--' . $value;
            $testpathinfo = str_replace($value, "{".$keyi."}", $testpathinfo);
        }
        dd($testpathinfo, $tt);
        dd($tt);
        //dd($baseurl);
        dd($request);

        $this->generateAllAccessRoute($accessRoleRepository, $request);

        /*$em = $this->getDoctrine()->getManager();
        $router = $this->get('router');
        $routes = $router->getRouteCollection()->all();
        $all_route_path = [];

        foreach ($routes as $route) {

            $access = new AccessRole();
            $routePath = $route->getPath();

            $existingRoutePath = $accessRoleRepository->findBy([
                'authorizationEspace' => $routePath
            ]);

            //dd($test[1]);
            if( !preg_match('/_./', $routePath) && $existingRoutePath == null )
            {
                /*$access->setAuthorizationEspace($routePath);
                $access->setCreatedAt(new \DateTime());
                $em->persist($access);
                $em->flush();
                array_push($all_route_path, $routePath);
            }
        }

        dd($all_route_path);*/

        return $this->render('access_role/index.html.twig', [
            'access_roles' => $accessRoleRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="access_role_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
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
    public function show(AccessRole $accessRole): Response
    {
        return $this->render('access_role/show.html.twig', [
            'access_role' => $accessRole,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="access_role_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, AccessRole $accessRole): Response
    {
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
    public function delete(Request $request, AccessRole $accessRole): Response
    {
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
                    $access->setAuthorizationEspace($routePath);
                    $access->setCreatedAt(new \DateTime());
                    $access->setCreatedBy("_generated");
                    $em->persist($access);
                    $em->flush();
                    //array_push($all_route_path, $routePath);
                }
        }
    }
}
