<?php

namespace App\Controller;

use App\Form\ContactType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;

class ContactController extends AbstractController
{
    /**
     * @Route("/contact", name="app_contact")
     */
    public function index(Request $request, MailerInterface $mailer): Response
    {
        $form = $this->createForm(ContactType::class);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){

             $data = $form->getData();

             $address = $data['email'];
             $subject = $data['subject'];
             $message = $data['message'];


            $email = (new Email())
            ->from($address)
            ->to('admin@admin.com')
            ->subject($subject)
            ->text($message);
            
            $mailer->send($email);

            // $this->addFlash(
            //     'success',
            //     'Votre demande a bien été envoyé avec succés !'
            // );

            //  return $this->redirectToRoute('app_contact');
            }
            
        return $this->render('contact/index.html.twig', [
            'formContact' => $form->createView(),
        ]);
    }
}
