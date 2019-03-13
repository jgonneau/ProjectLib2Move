<?php

namespace App\Controller;

use App\Repository\RentRepository;
use App\Repository\OfferRepository;
use App\Repository\VehicleRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
* @Route("/account")
*/
class AccountController extends AbstractController
{
    /**
     * @Route("/", name="account")
     */
    public function index(VehicleRepository $VR, Request $request)
    {
        //NOTE: A remplacer VehiclRepo par OfferRepository ..
        //
        if ($request->query->get('search_by'))
        {
            ////MODF
            $all_offers = $VR->findBy([
                ['id' => $request->query->get('search_by')],
                ['creation_date' => 'ASC']
            ]);
        }
        else 
        {
            $all_offers = $VR->findAll();
        }

        //Affichage de la vue
        return $this->render('account/index.html.twig', [
            'offers' => $all_offers
        ]);
    }

    /**
     * @Route("/myrents", name="rents_user")
     */
    public function rentsUser(RentRepository $rentRepository)
    {
        //L'on récupère les actuelles locations de l'utilisateur
        $actual_rents = $rentRepository->findAll();
        /*if($actual_rents == [])
        {
            $actual_rents = null;
        // dd($actual_rents);

        }*/

        //Affichage de la vue
        return $this->render('account/actual_rents.html.twig', [
            'rents' => $actual_rents
        ]);
    }

    /**
     * @Route("/view_offer/{id}", name="view_offer")
     */
    public function offerDetails(OfferRepository $offerRepository, $id = null)
    {
        //Si id de l'offre est inexistant ou bien non numérique, l'on redirige
        if (!$id || !is_numeric($id))
        {   
            $this->redirect('/account');
        }

        //L'on récupère l'offre que l'utilisateur veut voir
        $offer_details = $offerRepository->find($id);

        //Affichage de la vue
        return $this->render('account/offer_view.html.twig', [
            'offer' => $offer_details
        ]);
    }

    /**
     * @Route("/accept_offer/{id}", name="accept_offer")
     */
    public function offerAccepted(OfferRepository $offerRepository, $id = null)
    {
        ///////////////////
        //Affichage de la vue
        return $this->render('account/index.html.twig', [
            'offers' => ''
        ]);
    }

    /**
     * @Route("/revoke_offer/{id}", name="revoke_offer")
     */
    public function offerRevoked(OfferRepository $offerRepository, $id = null)
    {
        ///////////////////
        //Affichage de la vue
        return $this->render('account/index.html.twig', [
            'offers' => ''
        ]);
    }
}
