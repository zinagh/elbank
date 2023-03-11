<?php

namespace App\Form;

use App\Entity\Credit;
use App\Entity\OperationCredit;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class OperationCreditType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('dateOp')
            ->add('montPayer')
            ->add('echeance')
            ->add('tauxInteret')
            ->add('solvabilite')
            ->add('typeOperation')
            ->add('Credit', EntityType::class, ['class' => Credit::class, 'choice_label' => 'Id'])


        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => OperationCredit::class,
        ]);
    }
}