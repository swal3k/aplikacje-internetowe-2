<?php

namespace App\Form;

use App\Entity\Location;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CountryType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;


class LocationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('country', CountryType::class, [
                'label' => 'Country',
                'placeholder' => 'Select country',
                'preferred_choices' => ['PL'],
            ])
            ->add('city', TextType::class, [
                'label' => 'City',
                'attr' => ['placeholder' => 'Enter city name'],
            ])
            ->add('latitude', NumberType::class, [
                'label' => 'Latitude',
                'input' => 'string',
                'scale' => 7,
                'html5' => true,
                'attr' => ['step' => 'any'],
            ])
            ->add('longitude', NumberType::class, [
                'label' => 'Longitude',
                'input' => 'string',
                'scale' => 7,
                'html5' => true,
                'attr' => ['step' => 'any'],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Location::class,
        ]);
    }
}
