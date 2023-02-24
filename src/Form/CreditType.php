<?php

namespace App\Form;

use App\Entity\Compte;
use App\Entity\Credit;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CreditType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('montCredit')
            ->add('datepe')
            ->add('datede')
            ->add('dureeC')
            ->add('echeance')
            ->add('tauxInteret')
            ->add('decision')
            ->add('etatCredit')
            ->add('typeCredit')
            ->add('numerocompte', EntityType::class, ['class' => Compte::class, 'choice_label' => function($compte){return $compte->getNumCompte();}])

        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Credit::class,
        ]);
    }
}