<?php

namespace App\Controller;

use App\Entity\AccessRole;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Router;

class RouteController extends AbstractController
{

    public function routeAction()
    {
        $em = $this->getDoctrine()->getManager();
        /** @var Router $router */
        $router = $this->get('router');
        $routes = $router->getRouteCollection()->all();
        $route_path = [];
        foreach ($routes as $route) {
            $access = new AccessRole();
            $test = $route->getPath();
            //dd($test[1]);
            if(!preg_match('/_./', $test))
            {
                $access->setAuthorizationEspace($test);
                $access->setCreatedAt(new \DateTime());
                $em->persist($access);
                $em->flush();
                array_push($route_path, $test);
            }
        }
    }

}
