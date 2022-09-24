<?php

namespace App\Controller;

use App\Entity\Creneau;
use App\Form\CreneauType;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class CreneauController extends AbstractController
{
    /**
     * @IsGranted("ROLE_ADMIN")
     * @Route("/creneau", name="app_creneau")
     */
    public function index(ManagerRegistry $doctrine): Response
    {
        $creneaux = $doctrine->getRepository(Creneau::class)->findAll();
        
        return $this->render('creneau/index.html.twig', [
            'creneaux' => $creneaux,
        ]);
    }
    
    /**
     * @IsGranted("ROLE_ADMIN")
     * @Route("/creneau/add", name="add_creneau")
     * @Route("/creneau/update/{id}", name="update_creneau")
     */
    public function add(ManagerRegistry $doctrine, Creneau $creneau = null, Request $request): Response
    {

        if(!$creneau) {
            $creneau = new Creneau();
        }
        
        $entityManager = $doctrine->getManager();
        $form = $this->createForm(CreneauType::class, $creneau);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){

            $creneau = $form->getData();
            $entityManager->persist($creneau);
            $entityManager->flush();

            return $this->redirectToRoute('app_creneau');
        }

        return $this->render('creneau/add.html.twig', [
            'formCreneau' => $form->createView(),
        ]);
    }

    /**
     * @IsGranted("ROLE_ADMIN")
     * @Route("/creneau/delete/{id}", name="delete_creneau")
     */
    public function delete(ManagerRegistry $doctrine, Creneau $creneau)
    {
        $entityManager = $doctrine->getManager();
        $entityManager->remove($creneau);
        $entityManager->flush();

        return $this->redirectToRoute("app_creneau");
    }

}