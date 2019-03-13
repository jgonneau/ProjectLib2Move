<?php

namespace App\Controller;

use App\Entity\Vehicle;
use App\Form\VehicleType;
use App\Service\AccessAuth;
use App\Entity\SubTypeOfVehicle;
use App\Repository\VehicleRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


/**
 * @Route("/vehicle")
 */
class VehicleController extends AbstractController
{
    public function generateUniqueFileName()
    {
        return md5(uniqid());
    }
    /**
     * @Route("/", name="vehicle_index", methods={"GET"})
     */
    public function index(VehicleRepository $vehicleRepository, AccessAuth $AA, Request $request, EntityManagerInterface $entityManager) : Response
    {
        //Verifie l'authorisation des droits d'accès de l'utilisateur courant
        $redirection = $AA->verif($request, $entityManager);
        if($redirection)
        {
            return $this->redirect($redirection);

        }
        return $this->render('vehicle/index.html.twig', [
            'vehicles' => $vehicleRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="vehicle_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $vehicle = new Vehicle();
        //$form = $this->createForm(VehicleType::class, $vehicle);
        $form = $this->createFormBuilder($vehicle)
            ->add('typeOfVehicle')
            ->add('brand')
            ->add('model')
            ->add('serialNumber')
            ->add('color')
            ->add('numberplate')
            ->add('kilometers')
            ->add('dateOfPurchase')
            ->add('buyingPrice')
            ->add('picture', FileType::class, array('mapped' => false))
            ->add('gallery')
            ->getForm();
            
        $form->handleRequest($request);
        $vehicle->setCreatedBy($this->getUser()->getFirstname());

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $file = $form->get('picture')->getData();
            if ($file) {
                $fileName = $this->generateUniqueFileName() . '.' . $file->guessExtension();
                $file->move(
                    $this->getParameter('image_directory'),
                    $fileName
                );
                $vehicle->setPicture($fileName);
            }
            $entityManager->persist($vehicle);
            $entityManager->flush();

            return $this->redirectToRoute('vehicle_index');
        }

        return $this->render('vehicle/new.html.twig', [
            'vehicle' => $vehicle,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="vehicle_show", methods={"GET"})
     */
    public function show(Vehicle $vehicle): Response
    {
        return $this->render('vehicle/show.html.twig', [
            'vehicle' => $vehicle,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="vehicle_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Vehicle $vehicle): Response
    {
        $form = $this->createForm(VehicleType::class, $vehicle);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $file = $form->get('picture')->getData();
            if ($file) {
                if ($vehicle->getPicture())  {
                    if (is_file($this->getParameter('image_directory').'/'.$vehicle->getPicture())) {
                        unlink($this->getParameter('image_directory').'/'.$vehicle->getPicture());
                    }
                }
                $fileName = $this->generateUniqueFileName() . '.' . $file->guessExtension();
                $file->move(
                    $this->getParameter('image_directory'),
                    $fileName
                );
                $vehicle->setPicture($fileName);
            }
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('vehicle_index', [
                'id' => $vehicle->getId(),
            ]);
        }

        return $this->render('vehicle/edit.html.twig', [
            'vehicle' => $vehicle,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="vehicle_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Vehicle $vehicle): Response
    {
        if ($this->isCsrfTokenValid('delete'.$vehicle->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($vehicle);
            $entityManager->flush();
        }

        return $this->redirectToRoute('vehicle_index');
    }
}
