<?php

namespace App\Controller;

use Stripe\Checkout\Session;
use Stripe\Stripe;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;

class StripeController extends AbstractController
{
    /**
     * @Route("/commande/create-session", name="stripe_create_session")
     */
    public function index(SessionInterface $session)
    {
        $product_for_stripe = [];

        Stripe::setApiKey('sk_test_nvWKKjEks5PY3kym7WmSqI5K00gTqsZIJ1');

        $YOUR_DOMAIN = 'http://127.0.0.1:8000';

        $panier = $session->get('panier');

        foreach ($panier as $item) {
            // Je récupères les informations de mon panier pour construire le produit qui passera par le paiement plus tard
            $product_for_stripe[] = [
                'price_data' => [
                    'currency' => 'eur',
                    'unit_amount' => $item['programmation']->getAtelier()->getPrice() * 100,
                    'product_data' => [
                        'name' => $item['programmation']->getAtelier()->getTitle(),
                    ],
                ],
                'quantity' => $item['nb_participant'],
            ];
        }


        $checkout_session = Session::create([
            'payment_method_types' => ['card'],
            'line_items' => [
                $product_for_stripe,
            ],
            'mode' => 'payment',
            'success_url' => $YOUR_DOMAIN.'/add/reservation',
            'cancel_url' => $YOUR_DOMAIN.'/commande/erreur/',
          ]);

        $response = new JsonResponse(['id' => $checkout_session->id]);

        return $response;
    }

    /**
     * @Route("/commande/merci", name="stripe_thanks")
     */
    public function merci(): Response
    {
        return $this->render('commande/merci.html.twig', [
        ]);
    }

    /**
     * @Route("/commande/erreur", name="stripe_error")
     */
    public function error(): Response
    {
        return $this->render('commande/erreur.html.twig', [
        ]);
    }
}
