<?php

namespace App\Form;

use App\Entity\Forecast;
use App\Entity\Location;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ForecastType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $decimal = ['input' => 'string', 'html5' => true, 'attr' => ['step' => 'any']];

        $builder
            ->add('location', EntityType::class, [
                'class' => Location::class,
                'choice_label' => fn (Location $l) => $l->getCity() . ', ' . $l->getCountry(),
                'placeholder' => 'Choose a location',
                'label' => 'Location',
            ])
            ->add('date', DateType::class, [
                'widget' => 'single_text',
                'label' => 'Date',
            ])
            ->add('temperature_celsius', NumberType::class, $decimal + ['label' => 'Temperature [°C]', 'scale' => 0])
            ->add('humidity', NumberType::class, $decimal + ['label' => 'Humidity [%]', 'scale' => 2])
            ->add('uv_index', IntegerType::class, ['label' => 'UV Index'])
            ->add('wind_speed', NumberType::class, $decimal + ['label' => 'Wind Speed [km/h]', 'scale' => 2])
            ->add('precipitation', NumberType::class, $decimal + ['label' => 'Precipitation [mm]', 'scale' => 2])
            ->add('pressure', IntegerType::class, ['label' => 'Pressure [hPa]'])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Forecast::class,
        ]);
    }
}