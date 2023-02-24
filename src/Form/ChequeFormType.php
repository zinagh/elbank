<?php

namespace App\Form;

use App\Entity\Cheques;
use App\Entity\Chequier;
use App\Entity\Compte;
use App\Entity\Utilisateur;
use Doctrine\DBAL\Types\TextType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;

class ChequeFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
//            ->add('proprietaire', EntityType::class, [
//                'class' => Utilisateur::class,
//                'choice_label' => function ($user)
//                {
//                    return $user->getNomU() . ' ' . $user->getPrenomU();
//                } ,])

            ->add('montant',NumberType::class)
            ->add('lieu', ChoiceType::class, [
                'label' => ' Address',
                'choices' => array(
                    'Tunis' => 'Tunis',
                    'Ariana' => 'Ariana',
                    'Ben Arous' => 'Ben Arous',
                    'Manouba' => 'Manouba',
                    'Nabeul' => 'Nabeul',
                    'Zaghouan' => 'Zaghouan',
                    'Bizerte' => 'Bizerte',
                    'Béja' => 'Béja',
                    'Jendouba' => 'Jendouba',
                    'Siliana' => 'Siliana',
                    'Sousse' => 'Sousse',
                    'Monastir' => 'Monastir',
                    'Mahdia' => 'Mahdia',
                    'Sfax' => 'Sfax',
                    'Kairouan' => 'Kairouan',
                    'Kasserine' => 'Kasserine',
                    'Sidi Bouzid' => 'Sidi Bouzid',
                    'Gabès' => 'Gabès',
                    'Mednine' => 'Mednine',
                    'Tataouine' => 'Tataouine',
                    'Gafsa' => 'Gafsa',
                    'Tozeur' => 'Tozeur',
                    'Kebili' => 'Kebili',
                ),
                'placeholder' => '',
                'required' => true,
            ])
            ->add('signature',PasswordType::class)
            ->add('destinataire',EntityType::class,[
                'class' => Compte::class,
                'choice_label'=>'fullname_client'])
//            ->add('proprietaire',EntityType::class,[
//                'class' => Compte::class,
//                'choice_label'=>'fullname_client'
//            ])
            ->add('idchequiers',EntityType::class,[
                'class' => Chequier::class,
                'choice_label'=>'motif_chequier'
            ]);
        // ->add('Valider',SubmitType::class)


    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Cheques::class,
        ]);
    }



}