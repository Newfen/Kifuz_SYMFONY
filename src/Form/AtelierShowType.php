<?php

namespace App\Form;

use App\Entity\Creneau;
use App\Repository\CreneauRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AtelierShowType extends AbstractType
{ 

    public function __construct(CreneauRepository $creneauRepository)
    {
         $this->creneauRepository = $creneauRepository;
    
    }
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {     
        $builder
            ->add('nb_participant', ChoiceType::class, [
                'choices' => [
                    '1' => 1,
                    '2' => 2,
                    '3' => 3,
                    '4' => 4,
                    '5' => 5,
                    '6' => 6,
                    '7' => 7,
                    '8' => 8,
                    '9' => 9,
                    '10' => 10,
                ],
            ])
            ->add('horaire', EntityType::class, [
                'class' => Creneau::class,
                'choices' => $this->creneauRepository->dateAfter()
            ])

            ->add('atelier', HiddenType::class)
            ->add('submit', SubmitType::class, [
                'label' => 'Ajouter au panier'
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            // Configure your form options here
        ]);
   
    }
    
}
