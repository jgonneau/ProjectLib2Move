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
    public function index(AccessRoleRepository $accessRoleRepository): Response
    {
        ////Router $route
        /*$router = $this->get('router');
        $routes = $router->getRouteCollection();

        foreach ($routes as $route) {
            $this->convertController($route);
        }*/

        return $this->render('access_role/index.html.twig', [
            'access_roles' => $accessRoleRepository->findAll(),
        ]);
    }

        /*
public function routeAction()
{
     @var Router $router 
    $router = $this->get('router');
    $routes = $router->getRouteCollection();

    foreach ($routes as $route) {
        $this->convertController($route);
    }

    return [
        'routes' => $routes
    ];
}*/


/*private function convertController(Router $route)
{
    $nameParser = $this->get('controller_name_converter');
    if ($route->hasDefault('_controller')) {
        try {
            $route->setDefault('_controller', $nameParser->build($route->getDefault('_controller')));
        } catch (\InvalidArgumentException $e) {
        }
    }
}*/

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
}
