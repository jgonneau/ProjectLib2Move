<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\VehicleRepository;

class AccountController extends AbstractController
{
    /**
     * @Route("/account", name="account")
     */
    public function index(VehicleRepository $VR)
    {
        //NOTE: A remplacer VehiclRepo par OfferRepository ..
        $all_offers = $VR->findAll();

        return $this->render('account/index.html.twig', [
            'offers' => $all_offers
        ]);
    }
}
