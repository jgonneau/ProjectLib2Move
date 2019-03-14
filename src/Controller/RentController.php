<?php

namespace App\Controller;

use App\Entity\Offer;
use App\Entity\Rent;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
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


    public function rent(Request $request, Offer $offer)
    {
        $rent = new Rent();

        $rent->setUser($this->getUser()->getId());
        $rent->setOffer($offer);
        $rent->setStatut('En cours');
        $rent->setValidationDate(new \DateTime());

    }

}
