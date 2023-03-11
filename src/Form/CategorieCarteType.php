<?php

namespace App\Form;

use App\Entity\CategorieCarte;
use App\Entity\Carte;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormInterface;





class CategorieCarteType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('type')
            ->add('description')
            ->add('prix')
            ->add('montant_max')
            /* ->add('cartes', EntityType::class, [
                'class' => 'App\Entity\Carte',
                'multiple' => true,
                'expanded' => false,
                'attr' => ['class' => 'select2'],
                'choice_label' => function($carte) {
                    return $carte->__toString();
                },

                'placeholder' => 'Choose cartes',
                'required' => false,
            ]) */
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => CategorieCarte::class,
        ]);
    }
}