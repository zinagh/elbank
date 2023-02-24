<?php

namespace App\Form;

use App\Entity\Carte;
use App\Entity\CategorieCarte;
use App\Entity\Utilisateur;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Vich\UploaderBundle\Form\Type\VichImageType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;



class CarteType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('idclient')

          /*  ->add('idclient') */
            ->add('date_ex')
            ->add('mp', PasswordType::class )
            // ,[
            //                // instead of being set onto the object directly,
            //                // this is read and encoded in the controller
            //                'mapped' => false,
            //                'constraints' => [
            //                    new EqualTo('mot_de_passe'),]
            //            ])
            ->add('login')
            ->add('num_carte')


          ->add('categorieCartes', EntityType::class, [
              'class' => 'App\Entity\CategorieCarte',
              'multiple' => true,
              'expanded' => false,
              'attr' => ['class' => 'select2'],
              'choice_label' => function($categorieCarte) {
                  return $categorieCarte->__toString();
              },

              'placeholder' => 'Choose categorie',
              'required' => false,
          ])

        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Carte::class,
        ]);

    }
}