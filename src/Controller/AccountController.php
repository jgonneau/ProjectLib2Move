<?php

namespace App\Controller;

use App\Entity\User;
use App\Service\AccessAuth;
use App\Repository\RentRepository;
use App\Repository\OfferRepository;
use App\Repository\VehicleRepository;
use Doctrine\ORM\EntityManagerInterface;
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
    public function index(OfferRepository $VR, AccessAuth $accessAuth, EntityManagerInterface $entityManager, Request $request)
    {
        $redirection = $accessAuth->verif($request, $entityManager);
        if($redirection)
        {
            return $this->redirect($redirection);
        }

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
    public function rentsUser(RentRepository $rentRepository, AccessAuth $accessAuth, EntityManagerInterface $entityManager, Request $request)
    {
        $redirection = $accessAuth->verif($request, $entityManager);
        if($redirection)
        {
            return $this->redirect($redirection);
        }

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
     * @Route("/viewOffer/{id}", name="view_offer")
     */
    public function offerDetails(OfferRepository $offerRepository, $id = null, AccessAuth $accessAuth, EntityManagerInterface $entityManager, Request $request)
    {
        $redirection = $accessAuth->verif($request, $entityManager);
        if($redirection)
        {
            return $this->redirect($redirection);
        }

        //Si id de l'offre est inexistant ou bien non numérique, l'on redirige
        if (!$id || !is_numeric($id))
        {   
            $this->redirect('/account');
        }

        //L'on récupère l'offre que l'utilisateur veut voir
        $offer_details = $offerRepository->find($id);

        $duration = $offer_details->getStartDate()->diff($offer_details->getEndDate());


        //Affichage de la vue
        return $this->render('account/offer_view.html.twig', [
            'offer' => $offer_details,
            'duration' => $duration->format('%R%a journé')
        ]);
    }

    /**
     * @Route("/acceptOffer/{id}", name="accept_offer")
     */
    public function offerAccepted(OfferRepository $offerRepository, $id = null, AccessAuth $accessAuth, EntityManagerInterface $entityManager, Request $request)
    {
        $redirection = $accessAuth->verif($request, $entityManager);
        if($redirection)
        {
            return $this->redirect($redirection);
        }

        ///////////////////
        //Affichage de la vue
        return $this->render('account/index.html.twig', [
            'offers' => ''
        ]);
    }

    /**
     * @Route("/revoke_offer/{id}", name="revoke_offer")
     */
    public function offerRevoked(OfferRepository $offerRepository, $id = null, AccessAuth $accessAuth, EntityManagerInterface $entityManager, Request $request)
    {
        $redirection = $accessAuth->verif($request, $entityManager);
        if($redirection)
        {
            return $this->redirect($redirection);
        }
        
        ///////////////////
        //Affichage de la vue
        return $this->render('account/index.html.twig', [
            'offers' => ''
        ]);
    }

    /**
     * @Route("/{id}", name="user_view", methods={"GET"})
     */
    public function show(User $user, AccessAuth $accessAuth, EntityManagerInterface $entityManager, Request $request)
    {
        $redirection = $accessAuth->verif($request, $entityManager);
        if($redirection)
        {
            return $this->redirect($redirection);
        }

        return $this->render('user/show.html.twig', [
            'user' => $user,
        ]);
    }
}
