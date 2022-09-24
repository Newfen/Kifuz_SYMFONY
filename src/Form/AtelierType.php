<?php

namespace App\Form;

use App\Entity\Atelier;
use App\Entity\Category;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;

class AtelierType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title', TextType::class)
            ->add('description', TextType::class)
            ->add('price', IntegerType::class)
            ->add('category', EntityType::class,[
                'class' => Category::class,
                'choice_label' => 'category_name'
                ])
            ->add('images', FileType::class, [
                'label' => false,
                'multiple' => true, 
                'mapped' => false,
                'required' => false
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'Confirmer'
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Atelier::class,
        ]);
    }
}
