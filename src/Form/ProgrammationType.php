<?php

namespace App\Form;

use App\Entity\Atelier;
use App\Entity\Creneau;
use App\Entity\Programmation;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class ProgrammationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('creneau', EntityType::class, [
                'class' => Creneau::class,
                'choice_label' => 'jourFormat',
            ])
            ->add('atelier', EntityType::class, [
                'class' => Atelier::class,
                'choice_label' => 'title'
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'Confirmer'
            ]);
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Programmation::class,
        ]);
    }
}
