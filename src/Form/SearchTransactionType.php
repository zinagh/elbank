<?php

namespace App\Form;

use App\Entity\Transaction;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SearchTransactionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('RIB_recepteur')
            ->add('montant_transaction')
            ->add('date_transaction')
            ->add('description_transaction')
            ->add('fullname_recepteur')
            ->add('type_transaction')
            ->add('etat_transaction')
            ->add('RIB_emetteur')
            ->add('fullname_emetteur')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Transaction::class,
        ]);
    }
}
