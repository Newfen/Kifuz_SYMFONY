<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Reservation;
use App\Form\RegistrationFormType;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AccountController extends AbstractController
{
    /**
     * @Route("/account", name="app_account")
     */
    public function index(): Response
    {
        $user = $this->getUser();

        return $this->render('account/index.html.twig', [
            'controller_name' => 'AccountController',
            'user' => $user,
        ]);
    }

    /**
     * @Route("reservation", name="app_reservation")
     */
    public function indexHistory(ManagerRegistry $doctrine): Response
    {
        $repository = $doctrine->getRepository(Reservation::class);
        $user = $this->getUser();

        $reservation = $repository->findBy(
            ['client' => $user]
        );

        return $this->render('account/reservation.html.twig', [
            'controller' => 'Account',
            'reservations' => $reservation,
            'user' => $user,
        ]);
    }
}
