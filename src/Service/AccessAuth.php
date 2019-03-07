<?php

namespace App\Service;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

use Doctrine\Common\Persistence\ManagerRegistry;
use Fig\Link\GenericLinkProvider;
use Fig\Link\Link;
use Psr\Container\ContainerInterface;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\HttpKernelInterface;
use Symfony\Component\Messenger\Envelope;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\Security\Csrf\CsrfToken;
use Symfony\Component\WebLink\EventListener\AddLinkHeaderListener;
use Doctrine\ORM\EntityManager;

use App\Entity\UserRole as UserRole;


class AccessAuth extends AbstractController
{

    public function verif(string $test)
    {
        /*if ($test == "141")
        {
            return $this->redirectToRoute('home');
        }
        else {
            return $this->redirectToRoute('account');
        }*/

        //return $this->getUser()->getId();

        /*$entityManager = $this->getDoctrine()->getManager();entityManager*/
        /*$ret = $this->em->getRepository(UserRole::class)->findBy(['user' => 141]);
        
        $test = '';

        foreach ($ret as $value) {
            $test .= $value->getRole()->getNomRole();
        }

        dd($ret);
        dd($test);

        return $test;*/

        $entityManager = $this->getDoctrine()->getManager();
        //$entityManager = $this->getEntityManager();
        $ret = $entityManager->getRepository(UserRole::class)->findBy(['user' => $test]);
        
        $a = '';

        foreach ($ret as $value) {
            $a .= $value->getRole()->getNomRole();
        }

        return $a;
        //return $this->redirect('login');

    }
}