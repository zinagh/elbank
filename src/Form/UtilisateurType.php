<?php

namespace App\Form;

use App\Entity\Utilisateur;

use Gregwar\CaptchaBundle\Type\CaptchaType;
use phpDocumentor\Reflection\DocBlock\Tags\Property;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\PropertyAccess\PropertyPath;
use Symfony\Component\Validator\Constraints\EqualTo;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class UtilisateurType extends AbstractType
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
                        "Client" => "ROLE_USER"
                    ],
                    'expanded' => false,
                ])
            //,[
            //            // instead of being set onto the object directly,
            //            // this is read and encoded in the controller
            //        'mapped' => false,
            //                'constraints' => [
            //        new NotBlank([
            //            'message' => 'Please enter a password',
            //        ]),
            //        new Length([
            //            'min' => 6,
            //            'minMessage' => 'Your password should be at least {{ limit }} characters',
            //            // max length allowed by Symfony for security reasons
            //            'max' => 4096,
            //        ]),
            //    ],
            //            ]
            ->add('mot_de_passe', PasswordType::class )
            // ,[
            //                // instead of being set onto the object directly,
            //                // this is read and encoded in the controller
            //                'mapped' => false,
            //                'constraints' => [
            //                    new EqualTo('mot_de_passe'),]
            //            ])
            ->add('confirme_mot_de_passe',PasswordType::class)
            ->add('captcha', CaptchaType::class, array(
                'width' => 200,
                'height' => 50,
                'length' => 6,
            ))
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Utilisateur::class,
        ]);
    }
}