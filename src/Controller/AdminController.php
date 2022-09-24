<?php

namespace App\Controller;

use App\Entity\Reservation;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AdminController extends AbstractController
{
    // /**
    //  * @Route("/admin", name="app_admin")
    //  */
    // public function index(ManagerRegistry $doctrine): Response
    // {


    //     return $this->render('admin/index.html.twig', [
    //     ]);
    // }

    /**
     * @Route("/admin", name="app_admin_reservation")
     */
    public function adminReservation(ManagerRegistry $doctrine): Response
    {
        $reservations = $doctrine->getRepository(Reservation::class)->findAll();


        return $this->render('admin/index.html.twig', [
            'reservations' => $reservations,
        ]);
    }
}
