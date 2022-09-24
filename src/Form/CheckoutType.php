<?php

namespace App\Form;

use App\Entity\Reservation;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use App\Form\DataTransformer\ProgrammationToNumberTransformer;
use App\Form\DataTransformer\UserToNumberTransformer;

class CheckoutType extends AbstractType
{

    public function __construct(ProgrammationToNumberTransformer $programmationTransformer, UserToNumberTransformer $userTransformer)
    {
        $this->programmationTransformer = $programmationTransformer;
        $this->userTransformer = $userTransformer;
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nb_participant', HiddenType::class)
            ->add('reserved_at', HiddenType::class)
            ->add('client', HiddenType::class)
            ->add('programmation', HiddenType::class, [
                'invalid_message' => 'That is not a valid issue number',
            ])
            ->add('submit', SubmitType::class);
        
        $builder
            ->get('programmation')
            ->addModelTransformer($this->programmationTransformer);

        $builder
            ->get('client')
            ->addModelTransformer($this->userTransformer);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Reservation::class,
        ]);
    }
}
