<?php

namespace App\Form;

use App\Entity\Reclamation;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ReclamationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('type_rec', ChoiceType::class,
                [
                    'choices' => [
                        "Carte" => "carte",
                        "Cheque" => "cheque",
                        "Service" => "service"

                    ],
                    'expanded' => false,
                ])
            //->add('date_rec')

            ->add('desc_rec')
            //->add('nom_u')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Reclamation::class,
        ]);
    }
}