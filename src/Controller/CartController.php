<?php

namespace App\Controller;

use App\Repository\ProgrammationRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;

class CartController extends AbstractController
{
    /**
     * @Route("/cart", name="app_cart")
     */
    public function index(SessionInterface $session, ProgrammationRepository $programmationRepo): Response
    {
        $panier = $session->get('panier');

        // dd($panier);

        return $this->render('cart/index.html.twig', [
            'items' => $panier,
        ]);
    }

    /**
     * @Route("/cart/up/{id})", name="up")
     */
    public function upParticipant($id, SessionInterface $session)
    {
        $panier = $session->get('panier');

        ++$panier[$id]['nb_participant'];

        $panier = $session->set('panier', $panier);

        return $this->redirectToRoute('app_cart');
    }

    /**
     * @Route("/cart/down/{id})", name="down")
     */
    public function downParticipant($id, SessionInterface $session)
    {
        $panier = $session->get('panier');

        --$panier[$id]['nb_participant'];

        $panier = $session->set('panier', $panier);

        return $this->redirectToRoute('app_cart');
    }

    /**
     * @Route("/cart/remove/{id})", name="cart_remove")
     */
    public function remove(int $id, SessionInterface $session)
    {
        $panier = $session->get('panier');

        if (!empty($panier[$id])) {
            unset($panier[$id]);
        }

        $session->set('panier', $panier);

        return $this->redirectToRoute('app_cart');
    }
}
