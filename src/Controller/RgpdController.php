<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class RgpdController extends AbstractController
{
    /**
     * @Route("/rgpd/mention_legal", name="app_legal")
     */
    public function index_legal(): Response
    {
        return $this->render('rgpd/legal.html.twig', [
            'controller_name' => 'RgpdController',
        ]);
    }

    /**
     * @Route("/rgpd/confidentialite", name="app_confidentialite")
     */
    public function index_condidentialite(): Response
    {
        return $this->render('rgpd/confidentialite.html.twig', [
            'controller_name' => 'RgpdController',
        ]);
    }
}
