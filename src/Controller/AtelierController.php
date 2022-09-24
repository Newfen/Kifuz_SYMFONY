<?php

namespace App\Controller;

use App\Entity\Image;
use DateTimeImmutable;
use App\Entity\Atelier;
use App\Entity\Comments;
use App\Form\AtelierType;
use App\Form\CommentsType;
use App\Entity\Reservation;
use App\Form\AtelierShowType;
use Doctrine\ORM\EntityManager;
use App\Repository\ImageRepository;
use App\Repository\CreneauRepository;
use Doctrine\Persistence\ManagerRegistry;
use App\Repository\ProgrammationRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AtelierController extends AbstractController
{
    /**
     * @IsGranted("ROLE_ADMIN")
     * @Route("/atelier/admin", name="app_admin_atelier")
     */
    public function adminIndex(ManagerRegistry $doctrine): Response
    {
        $ateliers = $doctrine->getRepository(Atelier::class)->findAll();
        $reservations = $doctrine->getRepository(Reservation::class)->findAll();

        return $this->render('atelier/admin_index.html.twig', [
            'ateliers' => $ateliers,
            'reservations' => $reservations,
        ]);
    }

    /**
     * @IsGranted("ROLE_ADMIN")
     * @Route("/atelier/add", name="add_atelier")
     * @Route("/atelier/update/{id}", name="update_atelier")
     */
    public function add(ManagerRegistry $doctrine, Atelier $atelier = null, Request $request): Response
    {
        if (!$atelier) {
            $atelier = new Atelier();
        }
        
        $entityManager = $doctrine->getManager();
        $form = $this->createForm(AtelierType::class, $atelier);
        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) {
            // On récupère les images transmises
            $images = $form->get('images')->getData();
            // On boucle sur les images
            foreach($images as $image){
                // On génère un nouveau nom de fichier
                $fichier = md5(uniqid()) . '.' .$image->guessExtension();
                
                // On copie le fichier dans le dossier uploads
                $image->move(
                    $this->getParameter('images_directory'),
                    $fichier
                );
                
                // On stocke l'image dans la base de données (son nom)
                $img = new Image();
                $img->setImgName($fichier);
                $atelier->addImage($img);
            }
            
            $atelier = $form->getData();
            $entityManager->persist($atelier);
            $entityManager->flush();
            
            return $this->redirectToRoute('app_admin_atelier');
        }
        
        $ateliers = $doctrine->getRepository(Atelier::class)->findAll();
        // dd($ateliers);

        return $this->render('atelier/add.html.twig', [
            'formAtelier' => $form->createView(),
            'atelier' => $ateliers,
        ]);
    }
    
    /**
     * @Route("/supprime/image/{id}", name="annonces_delete_image", methods={"DELETE"})
     */
    public function deleteImage(Image $image, Request $request, ManagerRegistry $doctrine)
    {
        $data = json_decode($request->getContent(), true);
        
        //On vérifie si le token est valide
        if($this->isCsrfTokenValid('delete', $image->getId(), $data['_token']))
        {
            // On récupère le nom de l'image
            $nom = $image->getimgName();
            
            // On supprime le fichier
            unlink($this->getParameter('images_directory').'/'.$nom);
            
            // On supprime l'entrée de la base
            $entityManager = $doctrine->getManager();
            $entityManager->remove($image);
            $entityManager->flush();
            
            // On répond en json
            return new JsonResponse(['success' => 1]);
        } else{
            return new JsonResponse(['error' => 'Token Invalide'], 400);
        }
        
    }
    
    /**
     * @Route("/atelier", name="app_atelier")
     */
    public function index(ManagerRegistry $doctrine): Response
    {
        $ateliers = $doctrine->getRepository(Atelier::class)->findAll();
        
        return $this->render('atelier/index.html.twig', [
            'ateliers' => $ateliers,
        ]);
    }
    
    /**
     * @Route("/atelier/{id}", name="show_atelier")
     */
    public function addProgrammationToCart(Atelier $ateliers, Request $request, ProgrammationRepository $programmationRepo = null, ManagerRegistry $doctrine, CreneauRepository $creneau)
    {
        $session = $request->getSession();
        
        $form = $this->createForm(AtelierShowType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $dataTable = $form->getData(); // Je récupère les données rentrées dans le formulaire sous forme de tableau

            // J'instancie les données pour les utilisées par la suite
            $creneau_id = $dataTable['horaire']->getId();
            $atelier_id = $dataTable['atelier'];

            // Grâce aux données instanciées, je retrouve la programmation associés à celle-ci
            $programmation = $programmationRepo->findOneBy([
                'atelier' => $atelier_id,
                'creneau' => $creneau_id,
            ]);

            $programmation_id = $programmation->getId(); // Je récupère l'id de programmation pour récupérer ses données associées par la suite
            $nb_participant = $session->get('nb_participant', $dataTable['nb_participant']);

            $panier = $session->get('panier', []); // J'instancie mon futur panier
            // Je construit mon panier en lui assignant programmation_id comme index et je le remplit d'un tableau de données récupérer précédemment
            $panier[$programmation_id] = [
                'programmation' => $programmationRepo->find($programmation_id),
                'nb_participant' => $nb_participant,
            ];
            $session->set('panier', $panier); // Je set le tout dans la session

            return $this->redirectToRoute('app_cart');
        }

        // Partie commentaires
        // On crée le commentaire "vierge"

        $comment = new Comments();
        $entityManager = $doctrine->getManager();

        // On génère le formuaire
        $commentForm = $this->createForm(CommentsType::class, $comment);
        $commentForm->handleRequest($request);

        // Traitement du formulaire

        if ($commentForm->isSubmitted() && $commentForm->isValid()) {
            $user = $this->getUser();

            $comment->setCreatedAt(new DateTimeImmutable());
            $comment->setAtelier($ateliers);
            $comment->setUser($user);

            // On récupère le contenu du champ parentid
            $parentid = $commentForm->get('parentid')->getdata();
			
			// On va chercher le commentaire correspondant
            if($parentid != null){
			    $parent = $entityManager->getRepository(Comments::class)->find($parentid);
            }

			// On définit le parent
			$comment->setParent($parent ?? null);

            $entityManager->persist($comment);
            $entityManager->flush();

            $this->addFlash(
                'success',
                'Votre commentaire a bien été envoyé !'
             );

            return $this->redirectToRoute('show_atelier', ['id' => $ateliers->getId()]);
        }

        return $this->render('atelier/show.html.twig', [
            'atelier' => $ateliers,
            'formAtelier' => $form->createView(),
            'formComment' => $commentForm->createView(),
        ]);
    }


    /**
     * @IsGranted("ROLE_ADMIN")
     * @Route("/atelier/delete/{id}", name="delete_atelier")
     */
    public function delete(ManagerRegistry $doctrine, Atelier $atelier)
    {
        $entityManager = $doctrine->getManager();
        $entityManager->remove($atelier);
        $entityManager->flush();

        return $this->redirectToRoute('app_admin_atelier');
    }
}
