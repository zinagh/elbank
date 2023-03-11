<?php

namespace App\Form;

use App\Entity\Classroom;
use App\Entity\Compte;
use App\Entity\Utilisateur;
use phpDocumentor\Reflection\PseudoTypes\Numeric_;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CompteType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
//            ->add('num_compte', TextType::class, array('attr' => array(
//        'placeholder' => 'Numéro compte (11 chiffres)' )))
//            ->add('RIB_Compte', TextType::class,array('attr' => array(
//                'placeholder' => 'RIB (23 caractères)' )))
            ->add('fullname_client', EntityType::class, ['class' => Utilisateur::class, 'choice_label' => function ($user) {
                return $user->getNomU() . ' ' . $user->getPrenomU();
            },'attr' => ['class' => 'select-user']
            ])
            ->add('solde_compte', NumberType::class, array('attr' => array(
                'placeholder' => ' Solde Compte ')))
            ->add('seuil_compte', NumberType::class, array('attr' => array(
                'placeholder' => 'Seuil Compte')))
            ->add('taux_interet', TextType::class, array('attr' => array(
                'placeholder' => "Taux D'intérêt")))
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