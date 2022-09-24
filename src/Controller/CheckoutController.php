<?php

namespace App\Controller;

use App\Entity\Programmation;
use App\Entity\Reservation;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;

class CheckoutController extends AbstractController
{
    /**
     * @Route("/checkout", name="app_checkout")
     */
    public function index(SessionInterface $session)
    {
        $panier = $session->get('panier');

        return $this->render('checkout/index.html.twig', [
            'items' => $panier,
        ]);
    }

    /**
     * @Route("/add/reservation", name="add_reservation")
     */
    public function newReservation(SessionInterface $session, ManagerRegistry $doctrine)
    {
        $entityManager = $doctrine->getManager();

        $panier = $session->get('panier');
        $user = $this->getUser();
        $now = new \DateTime();

        foreach ($panier as $item) {
            $r = new Reservation();
            $pr = $doctrine->getRepository(Programmation::class)->find($item['programmation']->getId());
            $r->setClient($user);
            $r->setProgrammation($pr);
            $r->setNbParticipant($item['nb_participant']);
            $r->setReservedAt($now);

            $entityManager->persist($r);
            $entityManager->flush();

            $session->clear();
        }

        return $this->redirectToRoute('stripe_thanks');
    }
}
