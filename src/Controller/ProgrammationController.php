<?php

namespace App\Controller;

use App\Entity\Programmation;
use App\Form\ProgrammationType;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ProgrammationController extends AbstractController
{
    /**
     * @IsGranted("ROLE_ADMIN")
     * @Route("/programmation", name="app_programmation")
     */
    public function index(ManagerRegistry $doctrine): Response
    {
        $programmations = $doctrine->getRepository(Programmation::class)->findAll();

        return $this->render('programmation/index.html.twig', [
            'programmations' => $programmations,
        ]);
    }

    /**
     * @IsGranted("ROLE_ADMIN")
     * @Route("/programmation/add", name="add_programmation")
     * @Route("/programmation/update/{id}", name="update_programmation")
     */
    public function add(ManagerRegistry $doctrine, Programmation $programmation = null, Request $request): Response
    {

        if(!$programmation) {
            $programmation = new Programmation();
        }
        
        $entityManager = $doctrine->getManager();
        $form = $this->createForm(ProgrammationType::class, $programmation);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){

            $programmation = $form->getData();
            $entityManager->persist($programmation);
            $entityManager->flush();

            return $this->redirectToRoute('app_programmation');
        }

        return $this->render('programmation/add.html.twig', [
            'formProgrammation' => $form->createView(),
        ]);
    }

    /**
     * @IsGranted("ROLE_ADMIN")
     * @Route("/programmation/delete/{id}", name="delete_programmation")
     */
    public function delete(ManagerRegistry $doctrine, Programmation $programmation)
    {
        $entityManager = $doctrine->getManager();
        $entityManager->remove($programmation);
        $entityManager->flush();

        return $this->redirectToRoute("app_programmation");
    }
}
