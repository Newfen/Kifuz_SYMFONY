<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use App\Form\UserPasswordType;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserController extends AbstractController
{
    /**
     * @Route("/user/edition/{id}", name="user_edit")
     */
    public function index(User $user, Request $request, EntityManagerInterface $manager, UserPasswordHasherInterface $hasher): Response
    {
        if(!$this->getUser()){
            return $this->redirectToRoute(('security.login'));
        }

        if ($this->getUser() !== $user){
            return $this->redirectToRoute('app_home');
        }

        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){

            if($hasher->isPasswordValid($user, $form->getData()->gePlainPassword())){

                $user = $form->getData();
                $manager->persist($user);
                $manager->flush();
    
                $this->addFlash(
                    'success',
                    'Les informations de votre compte ont bien été modifiées.'
                );
                return $this->redirectToRoute('app_account');
            }else{
                $this->addFlash(
                    'success',
                    'Le mot de passe renseigné est incorrecte.'
                );        
            }


        }

        return $this->render('user/edit.html.twig', [
            'formUser' => $form->createView(),
            'user' => $user
        ]);
    }


    /**
     * @Route("/user/edition_password/{id}", name="password_user_edit")
     */
    public function editPassword(EntityManagerInterface $manager, User $user, Request $request, UserPasswordHasherInterface $hasher) : Response
    {
        $form = $this->createForm(UserPasswordType::class);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){

            if($hasher->isPasswordValid($user, $form->getData()['plainPassword']))
            {
                $user->setPassword(
                    $hasher->hashPassword(
                        $user,
                        $form->getData()['newPassword']
                    )
                );

                $manager->persist($user);
                $manager->flush();

                $this->addFlash(
                    'success',
                    'Le mot de passe a été modifié.'
                ); 
                return $this->redirectToRoute('app_home');
            } else {

            $this->addFlash(
                'warning',
                'Le mot de passe renseigné est incorrect.'
            );
            }
        }

        return $this->render('user/edit_password.html.twig', [
            'formPassword' => $form->createView(),
        ]);
    }

    /**
     * @Route("/user/delete/{id}", name="delete_user")
     */
    public function delete(ManagerRegistry $doctrine, User $user)
    {
        $entityManager = $doctrine->getManager();
        $entityManager->remove($user);
        $entityManager->flush();

        return $this->redirectToRoute('app_home');
    }
}
