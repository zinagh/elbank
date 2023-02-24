<?php

namespace App\Form;

use App\Entity\Utilisateur;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UtilisateurBackType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('cin_u')
            ->add('nom_u')
            ->add('prenom_u')
            ->add('date_naissance',DateType::Class, array(
                'widget' => 'choice',
                'years' => range(date('Y')-18, date('Y')-100),

            ))
            ->add('email_u')
            ->add('num_tel')
            ->add('role', ChoiceType::class,
                [
                    'choices' => [
                        "Client" => "ROLE_USER",
                        "Admin" => "ROLE_ADMIN",
                        "Employee" => "ROLE_EMPLOYEE"
                    ],
                    'expanded' => false,
                ])
            ->add('mot_de_passe', PasswordType::class)
            ->add('confirme_mot_de_passe',PasswordType::class)

        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Utilisateur::class,
        ]);
    }
}