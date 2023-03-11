<?php

namespace App\Form;

use App\Entity\Compte;
use App\Entity\Utilisateur;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class SearchCompteType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('fullname_client', EntityType::class, ['class' => Utilisateur::class, 'choice_label' => function ($user) {
                return $user->getNomU() . ' ' . $user->getPrenomU();
            }, 'attr' => ['class' => 'select-user3']])
            ->add('Rechercher', SubmitType::class, ['attr' => array('class' => 'd-none d-sm-inline-block btn btn-sm btn-primary shadow-sm boutonS')]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Compte::class,
        ]);
    }
}