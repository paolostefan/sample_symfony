<?php

namespace App\Form;

use App\Entity\Poi;
use App\Entity\PoiCategory;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CountryType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PoiType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
          ->add('title')
          ->add('description')
          ->add('coords')
          ->add('city')
          ->add('address')
          ->add('zip')
          ->add('province')
          ->add('region')
          ->add('country', CountryType::class)
          ->add(
            'category',
            EntityType::class,
            [
              'class'        => PoiCategory::class,
              'choice_label' => 'title',
            ]
          );
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
          [
            'data_class' => Poi::class,
          ]
        );
    }
}
