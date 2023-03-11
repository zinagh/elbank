<?php

namespace App\Form;

use App\Entity\Compte;
use App\Entity\Transaction;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TransactionFrontType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
//            ->add('RIB_emetteur', EntityType::class, ['class' => Compte::class, 'choice_label' => function ($compte) {
//                return $compte->getRIBCompte();
//            }, 'error_bubbling' => true,
//            ])
//            ->add('fullname_emetteur', EntityType::class, ['class' => Compte::class, 'choice_label' => function ($user) {
//                return $user->getFullnameClient()->getNomu() . ' ' . $user->getFullnameClient()->getPrenomu();
//            }, 'error_bubbling' => true,
//            ])
//                return $user->getNomU() . ' ' . $user->getPrenomU();}])
            ->add('RIB_recepteur', TextType::class, array('attr' => array(
                'placeholder' => 'RIB Récepteur (23 caractères)', 'error_bubbling' => true,
            )))
            ->add('fullname_recepteur', TextType::class, array('attr' => array(
                'placeholder' => 'Nom et Prénom Récepteur', 'error_bubbling' => true,
            )))
            ->add('montant_transaction', NumberType::class, array('attr' => array(
                'placeholder' => 'Montant transaction', 'error_bubbling' => true,
            )))
            ->add('description_transaction', TextareaType::class, array('attr' => array(
                'placeholder' => 'Objet de la transaction', 'error_bubbling' => true,
            )))
            ->add('type_transaction', ChoiceType::class,
                [
                    'choices' => [
                        "Virement" => "virement",
                        'Encaissement' => 'encaissement',
                        'Investissement' => 'investissement'
                    ],
                    'expanded' => false,
                    'data' => 'Virement',
                    'error_bubbling' => true,
                ])
//            ->add('etat_transaction', ChoiceType::class,
//                [
//                    'choices' => [
//                        "En cours" => "0",
//                        'Aboutie' => '1',
//                        'Annulée' => '2'
//                    ],
//                    'expanded' => false,
//                    'data' => 0,
//                ])
//            ->add('Enregistrer', SubmitType::class)
        ;
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Transaction::class,
        ]);
    }
}
