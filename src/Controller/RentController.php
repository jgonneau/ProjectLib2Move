<?php

namespace App\Controller;

use App\Entity\Offer;
use App\Entity\Rent;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class RentController extends AbstractController
{
    /**
     * @Route("/rent", name="rent")
     */
    public function index()
    {
        return $this->render('rent/index.html.twig', [
            'controller_name' => 'RentController',
        ]);
    }


    /**
     * @Route("/rent/offer/{id}",  name="rentOffer")
    */
    public function rent(Request $request, Offer $offer)
    {
        $em = $this->getDoctrine()->getManager();
        $rent = new Rent();

        $rent->setUser($this->getUser());
        $rent->setOffer($offer);
        $rent->setStatut('En cours');
        $rent->setValidationDate(new \DateTime());
        $rent->setCreatedAt(new \DateTime());

        $em->persist($rent);
        $em->flush();



        return new JsonResponse([
            "rent" => $rent,
            "offer" => $offer,
            "user" => $this->getUser()
        ]);

    }

}
