<?php

namespace App\Form;

use App\Entity\Compte;
use App\Entity\Utilisateur;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CompteModifBackType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('fullname_client', EntityType::class, ['class' => Utilisateur::class, 'choice_label' => function ($user) {
                return $user->getNomU() . ' ' . $user->getPrenomU();
            }, 'attr' => ['class' => 'select-user2']
            ])
            ->add('seuil_compte', NumberType::class, array('attr' => array(
                'placeholder' => 'Seuil Compte' )))
            ->add('taux_interet', TextType::class, array('attr' => array(
                'placeholder' => "Taux D'intérêt" )))
            ->add('type_compte', ChoiceType::class,
                [
                    'choices' => [
                        "Courant" => "Courant",
                        'Epargne' => 'Epargne',
//                        'Titre' => 'Titre'
                    ],
                    'expanded' => false,
                    'data' => 'Courant',
                ])
//            ->add('etat_compte', ChoiceType::class,
//                [
//                    'choices' => [
//                        "En cours d'activation" => "0",
//                        'Activé' => '1',
//                        'Désactivé' => '2'
//                    ],
//                    'expanded' => false,
//                    'data' => 0,
//                ])//            ->add('Enregistrer', SubmitType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Compte::class,
        ]);
    }
}