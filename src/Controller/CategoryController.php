<?php

namespace App\Controller;

use App\Entity\Category;
use App\Form\CategoryType;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class CategoryController extends AbstractController
{
    /**
     * @IsGranted("ROLE_ADMIN")
     * @Route("/category", name="app_category")
     */
    public function index(ManagerRegistry $doctrine): Response
    {
        $categories = $doctrine->getRepository(Category::class)->findAll();
        
        return $this->render('Category/index.html.twig', [
            'categories' => $categories,
        ]);
    }

    /**
     * @IsGranted("ROLE_ADMIN")
     * @Route("/category/add", name="add_category")
     * @Route("/category/update/{id}", name="update_category")
     */
    public function add(ManagerRegistry $doctrine, Category $category = null, Request $request): Response
    {

        if(!$category) {
            $category = new Category();
        }

        $entityManager = $doctrine->getManager();
        $form = $this->createForm(CategoryType::class, $category);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){

            $category = $form->getData();

            $entityManager->persist($category);
            $entityManager->flush();

            return $this->redirectToRoute('app_category');
        }

        return $this->render('category/add.html.twig', [
            'formCategory' => $form->createView()
        ]);  
    } 

    /**
     * @IsGranted("ROLE_ADMIN")
     * @Route("/category/delete/{id}", name="delete_category")
     */
    public function delete(ManagerRegistry $doctrine, Category $category)
    {
        $entityManager = $doctrine->getManager();
        $entityManager->remove($category);
        $entityManager->flush();

        return $this->redirectToRoute("app_category");
    }
}
