<?php

namespace App\Form;

use App\Entity\Chequier;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use App\Entity\Compte;
use App\Entity\Utilisateur;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;


class ChequierFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            //        ->add('nom_client', EntityType::class, [
            //            'class' => Utilisateur::class,
            //          'choice_label' => function ($user) {
            //         return $user->getNomU() . ' ' . $user->getPrenomU();
            //    },])
            ->add('num_compte',EntityType::class, ['class' => Compte::class, 'choice_label' => function ($compte) {
                return $compte->getRIBCompte() .' - '. $compte->getFullnameClient()->getNomu() . ' ' . $compte->getFullnameClient()->getPrenomu();
            }
//       [
//                'class' => Compte::class,
//                'choice_label' => 'Rib_compte',
            ])
            ->add('motif_chequier')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Chequier::class,
        ]);
    }
}