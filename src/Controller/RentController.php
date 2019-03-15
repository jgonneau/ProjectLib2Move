<?php

namespace App\Controller;

use App\Entity\Offer;
use App\Entity\Rent;
use Dompdf\Dompdf;
use Dompdf\Options;
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
     * @param Request $request
     * @param Offer $offer
     * @param \Swift_Mailer $mailer
     * @return JsonResponse
     * @throws \Exception
     */
    public function rent(Request $request, Offer $offer, \Swift_Mailer $mailer)
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

        $message = (new \Swift_Message('Location'))
            ->setFrom('contactlibtomove@gmail.com')
            ->setTo($this->getUser()->getEmail())
            ->setBody(
                $this->renderView(
                // templates/emails/registration.html.twig
                    'user/location.html.twig',
                    ['name' => $this->getUser()->getFirstname(),
                        'rent' => $rent
                        ]
                ),
                'text/html'
            )
            /*
             * If you also want to include a plaintext version of the message
            ->addPart(
                $this->renderView(
                    'emails/registration.txt.twig',
                    ['name' => $name]
                ),
                'text/plain'
            )
            */
        ;

        $mailer->send($message);

        // Configure Dompdf according to your needs
        $pdfOptions = new Options();
        $pdfOptions->set('defaultFont', 'Arial');
        // Instantiate Dompdf with our options
        $dompdf = new Dompdf($pdfOptions);
        // Retrieve the HTML generated in our twig file
        $html = $this->renderView('bill/index.html.twig', [
            'title' => "Welcome to our PDF Test",
            'rent' => $rent
        ]);
        // Load HTML to Dompdf
        $dompdf->loadHtml($html);
        // (Optional) Setup the paper size and orientation 'portrait' or 'landscape'
        $dompdf->setPaper('A4', 'portrait');
        // Render the HTML as PDF
        $dompdf->render();
        // Output the generated PDF to Browser (inline view)
        $dompdf->stream("mypdf.pdf", [
            "Attachment" => false
        ]);



        return new JsonResponse([
            "rent" => $rent,
            "offer" => $offer,
            "user" => $this->getUser()
        ]);

    }

}
